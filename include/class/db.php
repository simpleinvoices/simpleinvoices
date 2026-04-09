<?php

class db
{

    private $_db;
    static $_instance;

//	public $connection = null;

	function __construct()
	{

		global $config;
        //check if PDO is availbel

        class_exists('PDO',false) ? "" : simpleInvoicesError("PDO");
		/*
		* strip the pdo_ section from the adapter
		*/
		$pdoAdapter = substr($config->database->adapter, 4);

		if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql" AND $config->database->utf8 == true)
		{
            simpleInvoicesError("PDO_mysql_attr");
		}

		try
		{

			switch ($pdoAdapter)
			{

			    case "pgsql":
			    	$port = !empty($config->database->params->port) ? ';port='.$config->database->params->port : '';
			    	$this->_db = new PDO(
						'pgsql:host='.$config->database->params->host.$port.';dbname='.$config->database->params->dbname,
						$config->database->params->username,
						$config->database->params->password
					);
					$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    	break;

			    case "sqlite":
			    	$dsn = $config->database->params->dbname;
			    	// If not an absolute path or :memory:, resolve relative to databases/sqlite/
			    	if ($dsn !== ':memory:' && (strlen($dsn) === 0 || $dsn[0] !== '/')) {
			    		$base = preg_replace('/\.sqlite$/', '', $dsn);
			    		$dir  = realpath('.') . '/databases/sqlite';
			    		if (!is_dir($dir)) {
			    			mkdir($dir, 0755, true);
			    		}
			    		$dsn = $dir . '/' . $base . '.sqlite';
			    	}
			    	$this->_db = new PDO('sqlite:' . $dsn);
					$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// WAL mode for better concurrency; enforce FK constraints
					$this->_db->exec('PRAGMA journal_mode=WAL');
					$this->_db->exec('PRAGMA foreign_keys=ON');
					break;

                case "mysql":
                    switch ($config->database->utf8)
                    {
                        case true:

                            $this->_db = new PDO(
                                'mysql:host='.$config->database->params->host.';port='.$config->database->params->port.';dbname='.$config->database->params->dbname, $config->database->params->username, $config->database->params->password,  array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
                            );
				$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            break;

                        case false:
                            $this->_db = new PDO(
                                'mysql:host='.$config->database->params->host.';port='.$config->database->params->port.';dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
                            );
                        break;
                    }
                    break;
			}



		}
		catch( PDOException $exception )
		{
			simpleInvoicesError("dbConnection",$exception->getMessage());
			die($exception->getMessage());
		}


		//return $this->connnection;


	}

    //private __clone() {};

    public static function getInstance()
    {
        if( ! (self::$_instance instanceof self) )
        {
            self::$_instance = new self();
        }

		  return self::$_instance;
    }


	function query($sqlQuery)
	{
		//dbQuery($sql);

		try {
		//$dbh = $this->connection;
		//var_dump($this->_db);
		$argc = func_num_args();
		$binds = func_get_args();
		//$sth = false;
		// PDO SQL Preparation
		$sth = $this->_db->prepare($sqlQuery);
		if ($argc > 1) {
			array_shift($binds);
			for ($i = 0; $i < count($binds); $i++) {
				$sth->bindValue($binds[$i], $binds[++$i]);
			}
		}


			//var_dump($this->_db);
			$result = $sth->execute();
			//$sth->closeCursor();
			if ($sth->errorCode() > '0')
			{
				simpleInvoicesError('sql',$sth->errorInfo(),$sqlQuery);
			}
		} catch(Exception $e){
			echo $e->getMessage();
			echo "Dude, what happened to your query?:<br /><br /> ".htmlsafe($sqlQuery)."<br />".htmlsafe(end($this->_db->errorInfo()));
			$sth = NULL;
		}
		//$this->connection->closeCursor();
		return $sth;
		#return $result;

		$sth->closeCursor();

		$sth = NULL;

	}
    /*
     * lastInsertId returns the id of the most recently inserted row by the session
     * used by $dbh whose id was created by AUTO_INCREMENT (MySQL) or a sequence
     * (PostgreSQL).  This is a convenience function to handle the backend-
     * specific details so you don't have to.
     *
     */
    function lastInsertId() {
        global $config;
        $pdoAdapter = substr($config->database->adapter, 4);

        if ($pdoAdapter == 'pgsql') {
            $sql = 'SELECT lastval()';
            $sth = $this->query($sql);
            return $sth->fetchColumn();
        } elseif ($pdoAdapter == 'sqlite') {
            // PDO::lastInsertId() works natively for SQLite
            return $this->_db->lastInsertId();
        } else {
            // MySQL
            $sql = 'SELECT last_insert_id()';
            $sth = $this->query($sql);
            return $sth->fetchColumn();
        }
    }


	function __destruct()
	{
	//$this->connection->closeCursor();
	 //$this->connection = null;
	}

}
