<?php
require_once 'include/class/PdoDb.php';

class BackupDb {
    private $output;
    private $pdoDb;

    public function __construct() {
        $this->output = "";

        global $config;
        // @formatter:off
        $type = substr($config->database->adapter, 4);
        $host = $config->database->params->host . ':' .
                $config->database->params->port;
        $name = $config->database->params->dbname;
        $user = $config->database->params->username;
        $pwrd = $config->database->params->password;
        // @formatter:on
        $this->pdoDb = new PdoDb(new DbInfo($type, $name, $host, $pwrd, $user));
    }

    public function start_backup($filename) {
        $fh = fopen($filename, "w");
        $rows = $this->pdoDb->query("SHOW TABLES");
        foreach ($rows as $row) {
            $this->show_create($row[0], $fh);
        }
        fclose($fh);
    }

    private function show_create($tablename, $fh) {
        $query = "SHOW CREATE TABLE `$tablename`";
        $row = $this->pdoDb->query($query);
        fwrite($fh, $row[0][1] . ";\n");
        $insert = $this->retrieve_data($tablename);
        fwrite($fh, $insert);
        $this->output .= "<tr><td>Table: $tablename backed up successfully</td></tr>";
    }

    private function retrieve_data($tablename) {
        $query = "SHOW COLUMNS FROM `" . $tablename . "`";
        $rows = $this->pdoDb->query($query);
        $i = 0;
        $columns = array();
        foreach($rows as $row) {
            $columns[$i++][0] = $row[0];
        }
        $colcnt = count($columns);
        $query = "";
        $rows = $this->pdoDb->request("SELECT", $tablename);
        foreach($rows as $row) {
            $query .= "INSERT INTO `" . $tablename . "` VALUES(";
            for ($i = 0; $i < $colcnt; $i++) {
                $query .= "'" . addslashes($row[$columns[$i][0]]) . "'" .
                         ($i + 1 == $colcnt ? ");\n" : ",");
            }
        }
        $query .= "\n";
        return $query;
    }

    public function getOutput() {
        return $this->output;
    }
}
