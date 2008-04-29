<?php
/*All functions sliced into little ones for better understanding :), a lot of code repeats, its not so pro,
 but better for me to find after few beers*/

//check curret version of package, reads from config.php file
function cur(){
	include("../config/config.php");
	return "<font id=\"blue\">$version</font>";
	}

//check php version
function phpvers() {
	$version=phpversion();
	if(substr ($version, 2, 0) < 5){
	$res=array(1,$version);
	return $res;	
	} else {
		$res=array(0,$version);
		return $res;
		}
} 

//check if mysql is available
function mysql_is_available() {
	if(function_exists('mysql_connect')){
	return 1;
	} else {
		return 0;
		}
}

//memory limit check
function memorysheck() {
	$memory=get_cfg_var ( memory_limit);
	if($memory >= 24 ){
	$res=array(1,$memory);
	return $res;
	echo "<font id=\"green\">";
	echo $LANG['memory_valid_1'];
	echo $memory;
	echo $LANG['memory_valid_2'];	
	echo "</font>";
	} else {
		echo "<font id=\"red\">";
		echo $LANG['memory_caution_1'];		
		echo $memory;
		echo $LANG['memory_caution_2'];		
		echo "</font>";
		}
}

//return gd version
function gdVersion($user_ver = 0)
 {
     if (! extension_loaded('gd')) { return; }
     static $gd_ver = 0;
     // Just accept the specified setting if it's 1.
     if ($user_ver == 1) { $gd_ver = 1; return 1; }
     // Use the static variable if function was called previously.
     if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
     // Use the gd_info() function if possible.
     if (function_exists('gd_info')) {
         $ver_info = gd_info();
         preg_match('/\d/', $ver_info['GD Version'], $match);
         $gd_ver = $match[0];
         return $match[0];
     }
     // If phpinfo() is disabled use a specified / fail-safe choice...
     if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
         if ($user_ver == 2) {
             $gd_ver = 2;
             return 2;
         } else {
             $gd_ver = 1;
             return 1;
         }
     }
     // ...otherwise use phpinfo().
     ob_start();
     phpinfo(8);
     $info = ob_get_contents();
     ob_end_clean();
     $info = stristr($info, 'gd version');
     preg_match('/\d/', $info, $match);
     $gd_ver = $match[0];
     return $match[0];
 } 
 
//simple check of xsl module 
function xsl(){
	if (function_exists('xsl')){
	return 1;
	} else {
		return 0;
		}
 }

//end of left container functions


//test connection to the server
function testconn($DBHost, $DBUsername, $DBPassword){
	$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
	if (!$link) {
		return 0;
		} else {
			return 1;
	}
	mysql_close($link);
	}

//select database
function dbselect($DBHost, $DBUsername, $DBPassword, $DBName){
	$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
	$db_selected = mysql_select_db($DBName, $link);
	if (!$db_selected){
		return 0;
		} else {
			return 1;
	}
	mysql_close($link);
	}
	
//database exists
function dbexists($DBHost, $DBUsername, $DBPassword, $DBName){
	$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
	$db_selected = mysql_select_db($DBName, $link);
	if (!$db_selected) {
    	return 0;
		} else {
			return 1;
	}
	mysql_close($link);
	}
	
//create database	
function dbcreate($DBHost, $DBUsername, $DBPassword, $DBName){
	$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
	$db_selected = mysql_select_db($DBName, $link);
		if (!$db_selected) {
    	$sql = "CREATE DATABASE $DBName";
		if (!mysql_query($sql, $link)) {
			return 0;
   	 	}else {
   	 		return 1;
   	 	}
   	 	}
   	 	mysql_close($link);
	}

//delete database	
function dbdelete($DBHost, $DBUsername, $DBPassword, $DBName){
	$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
	$db_selected = mysql_select_db($DBName, $link);
		if (!$db_selected) {
    	$sql = "DROP DATABASE $DBName";
		if (!mysql_query($sql, $link)) {
			return 0;
   	 	}else {
   	 		return 1;
   	 	}
   	 	}
   	 	mysql_close($link);
	}

function dbexist($DBHost, $DBUsername, $DBPassword, $DBName){
	$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
	$db_selected = mysql_select_db($DBName, $link);
	$a = array();
	if (!$db_selected) {
    	$sql = "CREATE DATABASE $DBName";
		if (!mysql_query($sql, $link)) {
			$a[] = 0;
			return $a;
   	 	}else {
   	 	$db_selected = mysql_select_db($DBName, $link) or die('Not connected : ' . mysql_error());
   	 	if (!$db_selected) {
				echo "<font id=\"red\">";
		    	echo $LANG['no_DB'];	
				echo "</font>";
				} else {						    					
				echo "<font id=\"green\">";   	 	
		    	echo $LANG['CreateDB_true'];
				echo "</font>";
				}
				}
		} else {
		echo "<font id=\"green\">";   	 	
    	echo $LANG['ok_DB'];	
		echo "</font>";
		}
		mysql_close($link);
		}

function tbexist(){}

//read sql queries from file, and execute them. Need modifications ;) i think so
function parse_mysql_dump($DBHost, $DBUsername, $DBPassword, $DBName, $url, $ignoreerrors = false){
				global $_SESSION;
				$link = mysql_connect($DBHost, $DBUsername, $DBPassword);
				$db_selected = mysql_select_db($DBName, $link);
            $file_content = file($url);
            //print_r($file_content);
            $query = "";
            foreach($file_content as $sql_line) {
                $tsl = trim($sql_line);
                if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
                    $query .= $sql_line;
                    if (preg_match("/;\s*$/", $sql_line)) {
                        $result = mysql_query($query);
                        if (!$result && !$ignoreerrors) {
                            die(mysql_error());
                        }
                        $query = "";
                    }
                }
            }
				return 1;
            }
            


/*function checkdir($dir){
	 $folder = opendir($dir);
    while($file = readdir( $folder )) 
     if($file != '.' && $file != '..' && 
         ( !is_writable(  $dir."/".$file  ) || 
         (  is_dir(   $dir."/".$file   ) && !is_removeable(   $dir."/".$file   )  ) ))
    {
     closedir($dir);
     return 0;
    }
    closedir($dir);
    return 1;
 }*/
 
/*function is__writable($path) {
 
 if ($path{strlen($path)-1}=='/')
     return is__writable($path.uniqid(mt_rand()).'.tmp');
 
 if (file_exists($path)) {
     if (!($f = @fopen($path, 'r+')))
         return false;
     fclose($f);
     return true;
 }
 if (!($f = @fopen($path, 'w')))
     return false;
 fclose($f);
 unlink($path);
 return "aaaa";
 }*/

function testdir($path){

if (is_writable($path)) {
    return 1;
} else {
	return 0;
}
}



?>