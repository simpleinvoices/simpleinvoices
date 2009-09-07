<?php 

class database{
	
	var $db_link;

	function sqlQuery($sqlQuery,$conn) {

	//error_log($sqlQuery);
	$this->database();	
	if($query = mysql_query($sqlQuery)) {
		
		//error_log("Insert_id: ".mysql_insert_id($conn));

		return $query;
	}
		else {
			echo "Dude, what happened to your query?:<br><br> ".$sqlQuery."<br />".mysql_error();
		}
	}

    #-- Class Constructor ------------------------------------------------
    function database(){
        $this->db_link = $this->open_database();
    }
    
    #--------------------------------------------------------------------
    # @name: database::open_database
    # creates a connection to the mysql database
    #-------------------------------------------------------------------
    function open_database(){
    	
		global $config;
    	
        $db = mysql_connect($config->database->params->host.':'.$config->database->params->port,$config->database->params->username,$config->database->params->password)
            or die("<font color=\"#ff0000\">There was an error connecting to the database server</font>");
        mysql_select_db($config->database->params->dbname)
            or die("<font color=\"#ff0000\">There was an error selecting the database</font>");
        
        return $db;
    }
    #--------------------------------------------------------------------
    # @name: database::close_database($db)
    # closes a connection to the mysql database
    #-------------------------------------------------------------------
    function close_database(){
        mysql_close($this->db_link)
            or die("<font color=\"#ff0000\">There was an error terminating the database link</font>");
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
        while($row = mysql_fetch_array($result)){ 
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
        if ($row = mysql_fetch_array($result)) { 
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
        while($row = mysql_fetch_array($result)){ 
            $columns[$i][0] = $row[0]; 
            $i++; 
        } // while 
         
        $query     = "SELECT * FROM `" . $tablename . "`"; 
        $result = $oDB->sqlQuery($query,$db_link) ; 
        $tmp_query = ""; 
        while($row = mysql_fetch_array($result)){ 
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
