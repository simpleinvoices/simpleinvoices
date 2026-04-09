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

        switch ($oDB->db_type) {
            case 'mysql':
                $tableQuery = "SHOW TABLES";
                break;
            case 'sqlite':
                $tableQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name";
                break;
            case 'pgsql':
                $tableQuery = "SELECT tablename FROM pg_tables WHERE schemaname='public' ORDER BY tablename";
                break;
            default:
                throw new RuntimeException("Unsupported database type for backup: " . $oDB->db_type);
        }

        $result = $oDB->sqlQuery($tableQuery);
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $this->_backup_table($row[0], $oDB, $output_handle);
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
