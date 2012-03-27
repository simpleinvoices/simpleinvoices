<?php
/**
* This is a dumb class just to get some information about our Database
* *
* No inheritance from Zend.
*/

class SimpleInvoices_Db
{
    public static function performBackup()
    {
        
        
        $adapter = SimpleInvoices_Db_Table_Abstract::getDefaultAdapter();
        $table_list = $adapter->listTables();
        
        foreach($table_list as $table)
        {
            $adapter->query("OPTIMIZE TABLE " . $table);
        }
        
        // ToDo: Try with exec for faster response
        
        // If exec not working let's do it step by step
        $config = Zend_Registry::get('config');
        
        $sql_backup = "--\n-- Database: `" . $config->resources->db->params->dbname . "`\n--\n-- --------------------------------------------------------\n";

        foreach($table_list as $table)
        {
            $sql_backup .= "\n--\n-- Table structure for table `" . $table . "`\n--\n\n";

            $create = $adapter->query("SHOW CREATE TABLE `" . $table . "`")->fetch();
            $sql_backup .= preg_replace('/^CREATE TABLE /', 'CREATE TABLE IF NOT EXISTS ', $create['Create Table']) . ";\n";

            $columns = $adapter->query("SHOW COLUMNS FROM `" . $table . "`")->fetchAll();
            $column_text = "";
            
            foreach($columns as $column) {
                if (!empty($column_text)) {
                    $column_text .= ", `" . $column['Field'] . "`";
                } else {
                    $column_text .= "`" . $column['Field'] . "`";
                }
            }
            
            
            $rows = $adapter->query("SELECT " . $column_text . " FROM `" . $table . "`")->fetchAll();
            if (count($rows) > 0) {
                $sql_backup .= "\n--\n-- Dumping data for table `" . $table . "`\n--\n\n";
                $sql_backup .= "INSERT INTO `" . $table . "` (" . $column_text .") VALUES ";
                $isFirst = true;
                foreach($rows as $row) {
                    $tmp_query = "";
                    foreach($row as $value) {
                        if (!empty($tmp_query)) {
                            $tmp_query .= ", '".addslashes($value)."'"; 
                        } else {
                            $tmp_query .= "'".addslashes($value)."'"; 
                        } 
                    }
                    
                    if (!$isFirst) {
                        $sql_backup .= ",\n(" .$tmp_query . ")";
                    } else {
                        $sql_backup .= "\n(" .$tmp_query . ")";
                        $isFirst = false;
                    }
                }
                
                $sql_backup .= ";\n";    
                
            }
            
            
            
        }        
    
        return $sql_backup;
    }
    
    /**
    * Check if data exits
    *
    * @return bool
    */
    public static function dataExists()
    {
        $patch_manager = new SimpleInvoices_Db_Table_SQLPatchManager();
        if ($patch_manager->getCount() > 0) return true;
        else return false;
    }
    
    /**
    * Check if a table exists in our database
    * 
    * @param mixed $tableName
    * @return bool
    */
    public static function tableExists($tableName) 
    {
        $config = Zend_Registry::get('config');
        
        if (isset($config->simpleinvoices->db->table_prefix)) {
            $tableName = $config->simpleinvoices->db->table_prefix . $tableName;    
        }
        
        $table_list = Zend_Db_Table::getDefaultAdapter()->listTables();
        return in_array($tableName, $table_list);
    }
    
    
}
?>
