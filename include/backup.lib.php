<?php 

class database{
	
	var $db_link;

	function sqlQuery($sqlQuery,$conn = null) {

	//error_log($sqlQuery);
	if (!$this->db_link) {
		$this->database();	
	}
	try {
		$query = $this->db_link->query($sqlQuery);
		
		//error_log("Insert_id: ".$this->db_link->lastInsertId());

		return $query;
	}
	catch(PDOException $e) {
		echo "Dude, what happened to your query?:<br><br> ".$sqlQuery."<br />".$e->getMessage();
		return false;
	}
	}

    #-- Class Constructor ------------------------------------------------
    function database(){
        $this->db_link = $this->open_database();
    }
    
    #--------------------------------------------------------------------
    # @name: database::open_database
    # creates a connection to the mysql database using PDO
    #-------------------------------------------------------------------
    function open_database(){
    	
		global $config;
    	
		try {
			$pdoAdapter = substr($config->database->adapter, 4);
			
			switch ($pdoAdapter) {
				case "mysql":
					if ($config->database->utf8 == true) {
						$db = new PDO(
							'mysql:host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname, 
							$config->database->params->username, 
							$config->database->params->password,  
							array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;")
						);
					} else {
						$db = new PDO(
							'mysql:host='.$config->database->params->host.'; port='.$config->database->params->port.'; dbname='.$config->database->params->dbname, 
							$config->database->params->username, 
							$config->database->params->password
						);
					}
					break;
				case "pgsql":
					$db = new PDO(
						$pdoAdapter.':host='.$config->database->params->host.'; dbname='.$config->database->params->dbname,	
						$config->database->params->username, 
						$config->database->params->password
					);
					break;
				default:
					die("<font color=\"#ff0000\">Unsupported database adapter: ".$pdoAdapter."</font>");
			}
			
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			die("<font color=\"#ff0000\">There was an error connecting to the database server: ".$e->getMessage()."</font>");
		}
        
        return $db;
    }
    #--------------------------------------------------------------------
    # @name: database::close_database($db)
    # closes a connection to the mysql database
    #-------------------------------------------------------------------
    function close_database(){
        $this->db_link = null;
    }
} 



class backup_db{ 
    var $output; 
    var $filename; 
    #-- Class Constructor ------------------------------------------------ 
    function backup_db(){ 
        $this->output = ""; 
        if (!isset($this->filename)) { 
            $this->filename = "db_backup.sql"; 
        } 
    } 
    #-------------------------------------------------------------------- 
    # @name: backup_db::start_backup() 
    # @required: database.class 
    # like the name says, begins the backup 
    #------------------------------------------------------------------- 
    function start_backup(){ 
        $oDB         = new database(); 
        $file_handle    = fopen($this->filename,"w"); 
        $query            = "SHOW TABLES"; 
        $result            = $oDB->sqlQuery($query,$oDB->db_link); 
        while($row = $result->fetch(PDO::FETCH_NUM)){ 
            $tablename    = $row[0]; 
            $this->_show_create($tablename,$oDB->db_link,$file_handle); 
        } // while 
        fclose($file_handle); 
        $oDB->close_database(); 
    } 
    #-------------------------------------------------------------------- 
    # @name: backup_db::_show_create($tablename,$db_link,$fh) 
    # @param: $tablename - name of the table 
    # @param: $db_link - live database connection 
    # @param: $fh - open file handle 
    # executes the "SHOW CREATE TABLE statement to retrieve the table structure 
    # calls $this->_retrieve_data($tablename, $db_link) 
    #------------------------------------------------------------------- 
    function _show_create($tablename,$db_link,$fh){ 
        $oDB         = new database(); 
        $query = "SHOW CREATE TABLE `".$tablename."`"; 
        $result = $oDB->sqlQuery($query,$db_link); 
        if ($row = $result->fetch(PDO::FETCH_NUM)) { 
            fwrite($fh,$row[1] . ";\n"); 
            $insert           = $this->_retrieve_data($tablename, $db_link); 
            fwrite($fh,$insert); 
            $this->output .= "<tr><td>Table: $tablename backed up successfully</td></tr>" ; 
        } 
    } 
    #-------------------------------------------------------------------- 
    # @name: backup_db::_retrieve_data($tablename,$db_link) 
    # @param: $tablename - name of the table 
    # @param: $db_link - live database connection 
    # retrieves the data and creates insert statement 
    #------------------------------------------------------------------- 
    function _retrieve_data($tablename,$db_link){ 
        $oDB         = new database(); 
        $query         = "SHOW COLUMNS FROM `" . $tablename . "`"; 
        $result        = $oDB->sqlQuery($query,$db_link); 
        $i            = 0; 
        while($row = $result->fetch(PDO::FETCH_NUM)){ 
            $columns[$i][0] = $row[0]; 
            $i++; 
        } // while 
         
        $query     = "SELECT * FROM `" . $tablename . "`"; 
        $result = $oDB->sqlQuery($query,$db_link) ; 
        $tmp_query = ""; 
        while($row = $result->fetch(PDO::FETCH_ASSOC)){ 
            $tmp_query     .= "INSERT INTO `" . $tablename . "` VALUES("; // create a temporary holder; 
            for ($i = 0; $i < count($columns); $i++){ 
                if ($i == count($columns) - 1) { 
                    $tmp_query .= "'".addslashes($row[$columns[$i][0]])."');\n"; 
                }else{ 
                    $tmp_query .= "'".addslashes($row[$columns[$i][0]])."',"; 
                } 
            } 
        } // while     
        return $tmp_query; 
    } 
}
