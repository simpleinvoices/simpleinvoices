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
		$pdoAdapter = substr($config->resources->db->adapter, 4);
		
		if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql" AND $config->resources->db->params->charset === 'utf8')
		{ 
            simpleInvoicesError("PDO_mysql_attr");
		}

		try
		{
			
			switch ($pdoAdapter) 
			{

			    case "pgsql":
			    	$this->_db = new PDO(
						$pdoAdapter.':host='.$config->resources->db->params->host.';	dbname='.$config->resources->db->params->dbname,	$config->resources->db->params->username, $config->resources->db->params->password
					);
			    	break;
			    	
			    case "sqlite":
			    	$connlink = new PDO(
						$pdoAdapter.':host='.$config->resources->db->params->host.';	dbname='.$config->resources->db->params->dbname,	$config->resources->db->params->username, $config->resources->db->params->password
					);
					break;
				
                case "mysql":
                    if ($config->resources->db->params->charset === 'utf8') {
                        $this->_db = new PDO(
                            'mysql:host='.$config->resources->db->params->host.'; port='.$config->resources->db->params->port.'; dbname='.$config->resources->db->params->dbname, $config->resources->db->params->username, $config->resources->db->params->password,  array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
                        );
                        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } else {
                        $this->_db = new PDO(
                            $pdoAdapter.':host='.$config->resources->db->params->host.'; port='.$config->resources->db->params->port.'; dbname='.$config->resources->db->params->dbname,    $config->resources->db->params->username, $config->resources->db->params->password
                        );
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
//				simpleInvoicesError('sql',$sth->errorInfo(),$sqlQuery);
                throw new Exception($sth->errorInfo().' '.$sqlQuery);
			}
		} catch(Exception $e){

            /*Simple Invoice to stop if sql error*/
			simpleInvoicesError('sql',$sth->errorInfo(),$sqlQuery);
            die(0);

			echo $e->getMessage();
			echo "Dude, what happened to your query?:<br /><br /> ".htmlsafe($sqlQuery)."<br />".htmlsafe(end($this->_db->errorInfo()));
			$sth = NULL;
            // re- throw
            throw new Exception($e->getMessage);
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
        $pdoAdapter = substr($config->resources->db->adapter, 4);
        
        if ($pdoAdapter == 'pgsql') {
            $sql = 'SELECT lastval()';
        } elseif ($pdoAdapter == 'mysql') {
            $sql = 'SELECT last_insert_id()';
        }
        //echo $sql;
        $sth = $this->query($sql);
        return $sth->fetchColumn();
    }

	
	function __destruct() 
	{
	//$this->connection->closeCursor();
	 //$this->connection = null;
	}
	
}
