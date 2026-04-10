<?php

class database {

    var $db_link;
    var $db_type; // 'mysql', 'pgsql', 'sqlite'

    function __construct() {
        $this->database();
    }

    function sqlQuery($sqlQuery) {
        if (!$this->db_link) {
            $this->database();
        }
        try {
            return $this->db_link->query($sqlQuery);
        } catch (PDOException $e) {
            throw new RuntimeException("Database backup query failed.", 0, $e);
        }
    }

    function database() {
        $this->db_link = $this->open_database();
    }

    function open_database() {
        global $config;
        try {
            $pdoAdapter = substr($config->database->adapter, 4);
            $this->db_type = $pdoAdapter;

            switch ($pdoAdapter) {
                case "mysql":
                    $opts = $config->database->utf8
                        ? [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"]
                        : [];
                    $db = new PDO(
                        'mysql:host='.$config->database->params->host.';port='.$config->database->params->port.';dbname='.$config->database->params->dbname,
                        $config->database->params->username,
                        $config->database->params->password,
                        $opts
                    );
                    break;

                case "pgsql":
                    $port = !empty($config->database->params->port) ? ';port='.$config->database->params->port : '';
                    $db = new PDO(
                        'pgsql:host='.$config->database->params->host.$port.';dbname='.$config->database->params->dbname,
                        $config->database->params->username,
                        $config->database->params->password
                    );
                    break;

                case "sqlite":
                    $dsn = $config->database->params->dbname;
                    if ($dsn !== ':memory:' && (strlen($dsn) === 0 || $dsn[0] !== '/')) {
                        $base = preg_replace('/\.sqlite$/', '', $dsn);
                        $dir  = realpath('.') . '/databases/sqlite';
                        $dsn  = $dir . '/' . $base . '.sqlite';
                    }
                    $db = new PDO('sqlite:' . $dsn);
                    break;

                default:
                    throw new RuntimeException("Unsupported database adapter for backups: " . $pdoAdapter);
            }

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new RuntimeException("There was an error connecting to the database server.", 0, $e);
        }

        return $db;
    }

    function close_database() {
        $this->db_link = null;
    }

    // Return a properly-quoted identifier for the current database type
    function quoteIdent($name) {
        if ($this->db_type === 'mysql') {
            return '`' . str_replace('`', '``', $name) . '`';
        }
        return '"' . str_replace('"', '""', $name) . '"';
    }
}


class backup_db {

    var $output;
    var $filename;

    // Base table names (without TB_PREFIX), in parent-before-child order.
    // Used by restore_from_json() to insert rows without FK violations.
    static $IMPORT_TABLE_ORDER = [
        'index', 'user_role', 'user', 'preferences', 'invoice_type', 'tax',
        'payment_types', 'system_defaults', 'extensions', 'custom_fields',
        'sql_patchmanager', 'products_attribute_type', 'products_values',
        'products', 'biller', 'customers', 'user_domain', 'inventory',
        'invoices', 'cron', 'invoice_items', 'invoice_item_tax', 'payment',
        'cron_log', 'log',
    ];

    function __construct() {
        $this->output   = array();
        $this->filename = $this->filename ?? "db_backup.sql";
    }

    function start_backup($output_handle = null) {
        $oDB         = new database();
        $close_handle = false;

        if ($output_handle === null) {
            $directory = dirname($this->filename);
            if (!is_dir($directory) && !mkdir($directory, 0775, true)) {
                throw new RuntimeException("Backup directory could not be created: " . $directory);
            }
            if (!is_writable($directory)) {
                throw new RuntimeException("Backup directory is not writable: " . $directory);
            }
            $output_handle = fopen($this->filename, "wb");
            if ($output_handle === false) {
                throw new RuntimeException("Backup file could not be opened for writing: " . $this->filename);
            }
            $close_handle = true;
        }

        fwrite($output_handle, "-- Simple Invoices database backup\n");
        fwrite($output_handle, "-- Database type: " . $oDB->db_type . "\n");
        fwrite($output_handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n\n");

        foreach ($this->_get_tables($oDB) as $table) {
            $this->_backup_table($table, $oDB, $output_handle);
        }

        if ($close_handle) fclose($output_handle);
        $oDB->close_database();
    }

    // Write DROP + CREATE + INSERT statements for one table
    function _backup_table($tablename, $oDB, $fh) {
        $qi         = $oDB->quoteIdent($tablename);
        $cascade    = ($oDB->db_type === 'pgsql') ? ' CASCADE' : '';

        fwrite($fh, "\n-- Table: $tablename\n");
        fwrite($fh, "DROP TABLE IF EXISTS $qi$cascade;\n");
        fwrite($fh, $this->_get_create_table($tablename, $oDB) . ";\n");
        fwrite($fh, $this->_retrieve_data($tablename, $oDB));

        $this->output[] = $tablename;
    }

    // Return the CREATE TABLE DDL for a table
    function _get_create_table($tablename, $oDB) {
        switch ($oDB->db_type) {
            case 'mysql':
                $result = $oDB->sqlQuery("SHOW CREATE TABLE " . $oDB->quoteIdent($tablename));
                $row    = $result->fetch(PDO::FETCH_NUM);
                return $row[1]; // MySQL returns full DDL in column 1

            case 'sqlite':
                $stmt = $oDB->db_link->prepare(
                    "SELECT sql FROM sqlite_master WHERE type='table' AND name = :name"
                );
                $stmt->execute([':name' => $tablename]);
                $row = $stmt->fetch(PDO::FETCH_NUM);
                return $row ? $row[0] : "-- WARNING: could not retrieve DDL for $tablename";

            case 'pgsql':
                return $this->_reconstruct_pgsql_create($tablename, $oDB);
        }
    }

    // Reconstruct a CREATE TABLE statement from information_schema for PostgreSQL
    function _reconstruct_pgsql_create($tablename, $oDB) {
        $stmt = $oDB->db_link->prepare(
            "SELECT column_name, udt_name, data_type, character_maximum_length,
                    numeric_precision, numeric_scale, is_nullable, column_default
             FROM information_schema.columns
             WHERE table_schema = 'public' AND table_name = :name
             ORDER BY ordinal_position"
        );
        $stmt->execute([':name' => $tablename]);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get primary key columns
        $stmt2 = $oDB->db_link->prepare(
            "SELECT kcu.column_name
             FROM information_schema.table_constraints tc
             JOIN information_schema.key_column_usage kcu
                 ON tc.constraint_name = kcu.constraint_name
                AND tc.table_schema    = kcu.table_schema
             WHERE tc.table_schema = 'public'
               AND tc.table_name   = :name
               AND tc.constraint_type = 'PRIMARY KEY'
             ORDER BY kcu.ordinal_position"
        );
        $stmt2->execute([':name' => $tablename]);
        $pk_cols = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);

        $col_defs = [];
        foreach ($columns as $col) {
            $col_name  = '"' . $col['column_name'] . '"';
            $is_serial = ($col['column_default'] !== null &&
                          strpos($col['column_default'], 'nextval(') !== false);

            if ($is_serial) {
                // Map to SERIAL/BIGSERIAL so the sequence is created automatically
                $type    = ($col['udt_name'] === 'int8') ? 'BIGSERIAL' : 'SERIAL';
                $default = '';
                $notnull = '';
            } else {
                $type    = $this->_pgsql_col_type($col);
                $notnull = ($col['is_nullable'] === 'NO') ? ' NOT NULL' : '';
                $default = ($col['column_default'] !== null)
                           ? ' DEFAULT ' . $col['column_default']
                           : '';
            }

            $col_defs[] = "    $col_name $type$notnull$default";
        }

        if (!empty($pk_cols)) {
            $pk_quoted  = array_map(fn($c) => '"' . $c . '"', $pk_cols);
            $col_defs[] = "    PRIMARY KEY (" . implode(', ', $pk_quoted) . ")";
        }

        $qi = '"' . $tablename . '"';
        return "CREATE TABLE IF NOT EXISTS $qi (\n" . implode(",\n", $col_defs) . "\n)";
    }

    // Map a PostgreSQL information_schema column record to a SQL type string
    function _pgsql_col_type($col) {
        switch ($col['udt_name']) {
            case 'int2':      return 'SMALLINT';
            case 'int4':      return 'INTEGER';
            case 'int8':      return 'BIGINT';
            case 'float4':    return 'REAL';
            case 'float8':    return 'DOUBLE PRECISION';
            case 'numeric':
                return ($col['numeric_precision'] !== null)
                    ? "NUMERIC({$col['numeric_precision']},{$col['numeric_scale']})"
                    : 'NUMERIC';
            case 'varchar':
            case 'character varying':
                return ($col['character_maximum_length'] !== null)
                    ? "VARCHAR({$col['character_maximum_length']})"
                    : 'VARCHAR';
            case 'bpchar':
                return ($col['character_maximum_length'] !== null)
                    ? "CHAR({$col['character_maximum_length']})"
                    : 'CHAR';
            case 'text':        return 'TEXT';
            case 'bool':        return 'BOOLEAN';
            case 'date':        return 'DATE';
            case 'timestamp':   return 'TIMESTAMP';
            case 'timestamptz': return 'TIMESTAMP WITH TIME ZONE';
            case 'time':        return 'TIME';
            default:
                // Fall back to the information_schema data_type name
                return strtoupper($col['data_type']);
        }
    }

    // Return all table names from the current database
    function _get_tables($oDB) {
        switch ($oDB->db_type) {
            case 'mysql':
                $result = $oDB->sqlQuery("SHOW TABLES");
                break;
            case 'sqlite':
                $result = $oDB->sqlQuery("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
                break;
            case 'pgsql':
                $result = $oDB->sqlQuery("SELECT tablename FROM pg_tables WHERE schemaname='public' ORDER BY tablename");
                break;
            default:
                throw new RuntimeException("Unsupported database type: " . $oDB->db_type);
        }
        $tables = [];
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $tables;
    }

    // Export all table data as a JSON file (database-independent format).
    // The JSON structure mirrors databases/json/sample_data.json so it can
    // be imported into any supported database type via restore_from_json().
    function export_json($output_handle) {
        $oDB   = new database();
        $data  = [];

        foreach ($this->_get_tables($oDB) as $table) {
            $qi   = $oDB->quoteIdent($table);
            $rows = $oDB->db_link->query("SELECT * FROM $qi")->fetchAll(PDO::FETCH_ASSOC);
            $data[$table] = $rows; // include empty tables so restore clears them
        }

        $oDB->close_database();

        fwrite($output_handle, json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ));
    }

    // Restore all data from a JSON string produced by export_json().
    // Clears existing rows in all tables present in the JSON, then re-inserts
    // them in dependency order.  Schema must already exist (run the installer
    // or SQL backup first when migrating to a new database type).
    function restore_from_json($json_string) {
        $data = json_decode($json_string, true);
        if (!is_array($data)) {
            throw new RuntimeException("Invalid JSON: " . json_last_error_msg());
        }

        $oDB     = new database();
        $dbh     = $oDB->db_link;
        $db_type = $oDB->db_type;

        // Build ordered list of tables to restore: known dependency order first,
        // then any extra tables present in the JSON (future-proofing).
        $ordered = array_map(fn($base) => TB_PREFIX . $base, self::$IMPORT_TABLE_ORDER);
        foreach (array_keys($data) as $t) {
            if (!in_array($t, $ordered)) {
                $ordered[] = $t;
            }
        }
        // Keep only tables that appear in the JSON
        $to_restore = array_values(array_filter($ordered, fn($t) => array_key_exists($t, $data)));

        $dbh->beginTransaction();
        try {
            // Disable FK constraint checking for the session
            if ($db_type === 'mysql') {
                $dbh->exec("SET FOREIGN_KEY_CHECKS=0");
            } elseif ($db_type === 'sqlite') {
                $dbh->exec("PRAGMA foreign_keys=OFF");
            }

            // Clear existing rows — children before parents (reverse import order).
            // PostgreSQL uses TRUNCATE CASCADE so order does not matter there.
            foreach (array_reverse($to_restore) as $table) {
                $qi = $oDB->quoteIdent($table);
                try {
                    if ($db_type === 'pgsql') {
                        $dbh->exec("TRUNCATE $qi RESTART IDENTITY CASCADE");
                    } else {
                        $dbh->exec("DELETE FROM $qi");
                    }
                } catch (\Exception $e) {
                    // Table may not exist in the target schema — skip it
                }
            }

            // Insert rows — parents before children
            foreach ($to_restore as $table) {
                if (empty($data[$table])) {
                    continue;
                }
                $qi = $oDB->quoteIdent($table);
                foreach ($data[$table] as $row) {
                    $cols     = array_keys($row);
                    $col_list = implode(', ', array_map([$oDB, 'quoteIdent'], $cols));
                    $vals     = [];
                    foreach ($row as $v) {
                        $vals[] = ($v === null) ? 'NULL' : $dbh->quote((string)$v);
                    }
                    $dbh->exec("INSERT INTO $qi ($col_list) VALUES (" . implode(', ', $vals) . ")");
                }
            }

            // Re-enable FK constraints
            if ($db_type === 'mysql') {
                $dbh->exec("SET FOREIGN_KEY_CHECKS=1");
            } elseif ($db_type === 'sqlite') {
                $dbh->exec("PRAGMA foreign_keys=ON");
            }

            // PostgreSQL: advance sequences past the highest restored ID so that
            // new auto-insert rows do not collide with the imported data.
            if ($db_type === 'pgsql') {
                foreach (array_keys($data) as $table) {
                    $this->_reset_pgsql_sequences($table, $oDB);
                }
            }

            $dbh->commit();
        } catch (\Exception $e) {
            $dbh->rollBack();
            $oDB->close_database();
            throw $e;
        }

        $oDB->close_database();
    }

    // After a PostgreSQL restore, advance each SERIAL/BIGSERIAL sequence so
    // its next value is MAX(id)+1, preventing duplicate-key errors on insert.
    function _reset_pgsql_sequences($table, $oDB) {
        $stmt = $oDB->db_link->prepare(
            "SELECT column_name
             FROM information_schema.columns
             WHERE table_schema = 'public'
               AND table_name   = :name
               AND column_default LIKE 'nextval(%'"
        );
        $stmt->execute([':name' => $table]);
        $serial_cols = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($serial_cols as $col) {
            $qi_table = '"' . str_replace('"', '""', $table) . '"';
            $qi_col   = '"' . str_replace('"', '""', $col)   . '"';
            $esc_t    = str_replace("'", "''", $table);
            $esc_c    = str_replace("'", "''", $col);
            try {
                // setval(seq, val) with is_called=true → next nextval() returns val+1
                $oDB->db_link->exec(
                    "SELECT setval(
                         pg_get_serial_sequence('$esc_t', '$esc_c'),
                         COALESCE((SELECT MAX($qi_col) FROM $qi_table), 1)
                     )"
                );
            } catch (\Exception $e) {
                // Sequence may not exist — ignore
            }
        }
    }

    // Generate INSERT statements for all rows in a table
    function _retrieve_data($tablename, $oDB) {
        $qi = $oDB->quoteIdent($tablename);

        // Get ordered column names
        switch ($oDB->db_type) {
            case 'mysql':
                $result  = $oDB->sqlQuery("SHOW COLUMNS FROM $qi");
                $columns = [];
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $columns[] = $row[0];
                }
                break;

            case 'sqlite':
                // PRAGMA table_info returns: cid, name, type, notnull, dflt_value, pk
                $result  = $oDB->sqlQuery("PRAGMA table_info($tablename)");
                $columns = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $columns[] = $row['name'];
                }
                break;

            case 'pgsql':
                $stmt = $oDB->db_link->prepare(
                    "SELECT column_name FROM information_schema.columns
                     WHERE table_schema = 'public' AND table_name = :name
                     ORDER BY ordinal_position"
                );
                $stmt->execute([':name' => $tablename]);
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                break;
        }

        if (empty($columns)) {
            return '';
        }

        $col_list = implode(', ', array_map([$oDB, 'quoteIdent'], $columns));
        $result   = $oDB->sqlQuery("SELECT * FROM $qi");
        $sql      = '';

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $values = [];
            foreach ($columns as $col) {
                $v        = $row[$col];
                $values[] = ($v === null) ? 'NULL' : $oDB->db_link->quote($v);
            }
            $sql .= "INSERT INTO $qi ($col_list) VALUES(" . implode(', ', $values) . ");\n";
        }

        return $sql;
    }
}
