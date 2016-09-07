<?php
class db {
    private $_db;
    private $_pdoAdapter;
    private static $_instance;

    // public $connection = null;
    function __construct() {
        global $config;
        // check if PDO is availble
        class_exists('PDO', false) ? "" : simpleInvoicesError("PDO");

        // Strip the pdo_ section from the adapter
        $this->_pdoAdapter = substr($config->database->adapter, 4);
        if (!defined('PDO::MYSQL_ATTR_INIT_COMMAND') &&
            $this->_pdoAdapter == "mysql" and $config->database->utf8 == true) {
            simpleInvoicesError("PDO_mysql_attr");
        }

        // @formatter:off
        try {
            switch ($this->_pdoAdapter) {
                case "pgsql":
                    $this->_db = new PDO($this->_pdoAdapter .
                                    ':host=' .  $config->database->params->host . '; ' .
                                    'dbname=' . $config->database->params->dbname,
                                                $config->database->params->username,
                                                $config->database->params->password);
                    break;

                case "sqlite":
                    $this->db = new PDO($this->_pdoAdapter .
                                    ':host=' .  $config->database->params->host . '; ' .
                                    'dbname=' . $config->database->params->dbname,
                                                $config->database->params->username,
                                                $config->database->params->password);
                    break;

                case "mysql":
                    switch ($config->database->utf8) {
                        case true:
                            $this->_db = new PDO(
                                    'mysql:host=' . $config->database->params->host . '; ' .
                                    'port='       . $config->database->params->port . '; ' .
                                    'dbname='     . $config->database->params->dbname,
                                                    $config->database->params->username,
                                                    $config->database->params->password,
                                                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"));
                            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            break;

                        case false:
                            $this->_db = new PDO($this->_pdoAdapter .
                                    ':host='  . $config->database->params->host . '; ' .
                                    'port='   . $config->database->params->port . '; ' . 
                                    'dbname=' . $config->database->params->dbname,
                                                $config->database->params->username,
                                                $config->database->params->password);
                            break;
                    }
                    break;
            }
        } catch (PDOException $exception) {
            simpleInvoicesError("dbConnection", $exception->getMessage());
            die($exception->getMessage());
        }
    }

    // Instantiate the class object if it isn't already instantiated.
    // Otherwise return the existing instance.
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function query($sqlQuery) {
        try {
            $argc = func_num_args();
            $binds = func_get_args();
            $sth = $this->_db->prepare($sqlQuery);
            if ($argc > 1) {
                array_shift($binds);
                for ($i = 0; $i < count($binds); $i++) {
                    $sth->bindValue($binds[$i], $binds[++$i]);
                }
            }

            $sth->execute();
            if ($sth->errorCode() > '0') {
                simpleInvoicesError('sql', $sth->errorInfo(), $sqlQuery);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Dude, what happened to your query?:<br /><br /> " .
                  htmlsafe($sqlQuery) . "<br />" .
                  htmlsafe(end($this->_db->errorInfo()));
            $sth = NULL;
        }
        return $sth;
    }

    /*
     * lastInsertId returns the id of the most recently inserted row by the session
     * used by $dbh whose id was created by AUTO_INCREMENT (MySQL) or a sequence
     * (PostgreSQL). This is a convenience function to handle the backend-
     * specific details so you don't have to.
     */
    public function lastInsertId() {
        if ($this->_pdoAdapter == 'pgsql') {
            $sql = 'SELECT lastval()';
        } elseif ($this->_pdoAdapter == 'mysql') {
            $sql = 'SELECT last_insert_id()';
        }
        // echo $sql;
        $sth = $this->query($sql);
        return $sth->fetchColumn();
    }
}
