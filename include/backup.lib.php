<?php 

class database{
	
	var $db_link;

	function __construct(){
		$this->database();
	}

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
		throw new RuntimeException("Database backup query failed.", 0, $e);
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
					throw new RuntimeException("Unsupported database adapter for backups: " . $pdoAdapter);
			}
			
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			throw new RuntimeException("There was an error connecting to the database server.", 0, $e);
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
    function __construct(){ 
        $this->output = array(); 
        if (!isset($this->filename)) { 
            $this->filename = "db_backup.sql"; 
        } 
    } 
    #-------------------------------------------------------------------- 
    # @name: backup_db::start_backup() 
    # @required: database.class 
    # like the name says, begins the backup 
    #------------------------------------------------------------------- 
    function start_backup($output_handle = null){
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

        $query  = "SHOW TABLES";
        $result = $oDB->sqlQuery($query, $oDB->db_link);
        if ($result === false) {
            if ($close_handle) fclose($output_handle);
            throw new RuntimeException("Unable to list database tables for backup.");
        }
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $tablename = $row[0];
            $this->_show_create($tablename, $oDB->db_link, $output_handle);
        }
        if ($close_handle) fclose($output_handle);
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
        $query = "SHOW CREATE TABLE `".$tablename."`"; 
        $oDB = new database();
        $result = $oDB->sqlQuery($query,$db_link); 
        if ($row = $result->fetch(PDO::FETCH_NUM)) { 
            fwrite($fh, "DROP TABLE IF EXISTS `".$tablename."`;\n");
            fwrite($fh,$row[1] . ";\n"); 
            $insert           = $this->_retrieve_data($tablename, $db_link); 
            fwrite($fh,$insert); 
            $this->output[] = $tablename;
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
        $columns = array();
        $i            = 0; 
        while($row = $result->fetch(PDO::FETCH_NUM)){ 
            $columns[$i][0] = $row[0]; 
            $i++; 
        } // while 
         
        $query     = "SELECT * FROM `" . $tablename . "`"; 
        $result = $oDB->sqlQuery($query,$db_link) ; 
        $tmp_query = ""; 
        while($row = $result->fetch(PDO::FETCH_ASSOC)){ 
            $columnNames = array();
            foreach ($columns as $column) {
                $columnNames[] = "`" . $column[0] . "`";
            }
            $tmp_query .= "INSERT INTO `" . $tablename . "` (" . implode(", ", $columnNames) . ") VALUES("; 
            for ($i = 0; $i < count($columns); $i++){ 
                $value = $row[$columns[$i][0]];
                if ($value === null) {
                    $escaped = "NULL";
                } else {
                    $escaped = $db_link->quote($value);
                }
                if ($i == count($columns) - 1) { 
                    $tmp_query .= $escaped.");\n"; 
                }else{ 
                    $tmp_query .= $escaped.","; 
                } 
            } 
        } // while     
        return $tmp_query; 
    } 
}
