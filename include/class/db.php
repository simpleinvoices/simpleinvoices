<?php

class db
{

	function connect()
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
			    	$connlink = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
					);
			    	break;
			    	
			    case "sqlite":
			    	$connlink = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.';	dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password
					);
					break;
				
			    case "mysql_utf8":
				   	$connlink = new PDO(
						'mysql:host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname, $config->database->params->username, $config->database->params->password,  array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET utf8;")
					);
					break;
					
			    case "mysql":
			    default:
			    	//mysql
			    	$connlink = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname,	$config->database->params->username, $config->database->params->password, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true)
					);
					break;
			}
			
			
		$connlink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		$connlink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

			
		}
		catch( PDOException $exception )
		{
			//simpleInvoicesError("dbConnection",$exception->getMessage());
			die($exception->getMessage());
		}
				
				
		return $connlink;
		
	}
	
	function query($sqlQuery)
	{
		//dbQuery($sql);
		
		$dbh = db::connect();
		$argc = func_num_args();
		$binds = func_get_args();
		$sth = false;
		// PDO SQL Preparation
		$sth = $dbh->prepare($sqlQuery);
		if ($argc > 1) {
			array_shift($binds);
			for ($i = 0; $i < count($binds); $i++) {
				$sth->bindValue($binds[$i], $binds[++$i]);
			}
		}

		try {	
			$sth->execute();
		} catch(Exception $e){
			echo $e->getMessage();
			echo "Dude, what happenjklkjled to your query?:<br /><br /> ".htmlspecialchars($sqlQuery)."<br />".htmlspecialchars(end($sth->errorInfo()));
		}
		
		return $sth;
			
	}
	
}