<?php

class db
{
	public $connection = null;

	function __construct()
	{
		global $config;
		/*
		* strip the pdo_ section from the adapter
		*/
		$pdoAdapter = substr($config->database->adapter, 4);
		
		if(defined('PDO::MYSQL_ATTR_INIT_COMMAND') AND $pdoAdapter == "mysql")
		{ 
			$pdoAdapter ="mysql_utf8";
		}
		
		try
		{
			
			switch ($pdoAdapter) 
			{

			    case "pgsql":
			    	$this->connection = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
					);
			    	break;
			    	
			    case "sqlite":
			    	$connlink = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
					);
					break;
				
			    case "mysql_utf8":
				   	$this->connection = new PDO(
						'mysql:host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname, $config->database->params->username, $config->database->params->password,  array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET utf8;")
					);
					break;
					
			    case "mysql":
			    default:
			    	//mysql
			    	$this->connection = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true)
					);
					break;
			}
			
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
			$this->connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			
		}
		catch( PDOException $exception )
		{
			//simpleInvoicesError("dbConnection",$exception->getMessage());
			die($exception->getMessage());
		}
			
	
		//return $this->connnection;
		
		
	}
	
	function query($sqlQuery)
	{
		//dbQuery($sql);
		
		//$dbh = $this->connection;
		var_dump($this->connection);
		$argc = func_num_args();
		$binds = func_get_args();
		//$sth = false;
		// PDO SQL Preparation
		$sth = $this->connection->prepare($sqlQuery);
		if ($argc > 1) {
			array_shift($binds);
			for ($i = 0; $i < count($binds); $i++) {
				$sth->bindValue($binds[$i], $binds[++$i]);
			}
		}
		
				
		try {	
			var_dump($this->connection);
			$sth->execute();
		} catch(Exception $e){
			echo $e->getMessage();
			echo "Dude, what happened to your query?:<br /><br /> ".htmlspecialchars($sqlQuery)."<br />".htmlspecialchars(end($this->connection->errorInfo()));
		}
		//$this->connection->closeCursor();
		$sth->closeCursor();
		return $sth;
		
		$sth = NULL;
			
	}
	
	function __destruct() 
	{
	 //$this->connection->closeCursor();
	 //$this->connection = null;
	}
	
}