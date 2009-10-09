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
		/*
		if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql" AND $config->database->utf8 == true)
		{ 
            simpleInvoicesError("PDO_mysql_attr");
		}
*/
		try
		{
			
			switch ($pdoAdapter) 
			{

			    case "pgsql":
			    	$this->_db = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
					);
			    	break;
			    	
			    case "sqlite":
			    	$connlink = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
					);
					break;
				
                case "mysql":
                    switch ($config->database->utf8)
                    {
                        case true:
        
                            $connlink = new PDO(
                                'mysql:host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname, $config->database->params->username, $config->database->params->password,  array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
                            );
                            break;
                    
                        case false:
                        default:
                            //mysql
                            $connlink = new PDO(
                                $pdoAdapter.':host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
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
		
				
		try {	
			//var_dump($this->_db);
			$sth->execute();
			//$sth->closeCursor();
		} catch(Exception $e){
			echo $e->getMessage();
			echo "Dude, what happened to your query?:<br /><br /> ".htmlspecialchars($sqlQuery)."<br />".htmlspecialchars(end($this->_db->errorInfo()));
			$sth = NULL;
		}
		//$this->connection->closeCursor();
		return $sth;

		$sth->closeCursor();
		
		$sth = NULL;
			
	}
	
	function __destruct() 
	{
	//$this->connection->closeCursor();
	 //$this->connection = null;
	}
	
}
