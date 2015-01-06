<?php
/*
* Script: functions.php
*	Contain all non db query functions used in Simple Invoices
*
* Authors:
*	- Justin Kelly
*
* License:
*	GNU GPL2 or above
*
* Date last edited:
*	Mon Oct 28 12:00:00 IST 2013
**/

function checkLogin() {
	if (!defined("BROWSE")) {
		echo "You Cannot Access This Script Directly, Have a Nice Day.";
		exit();
	}
}

function getLogoList() {
	$dirname="./templates/invoices/logos";
	$ext = array("jpg", "png", "jpeg", "gif");
	$files = array();
	if($handle = opendir($dirname)) {
		while(false !== ($file = readdir($handle)))
		for($i=0;$i<sizeof($ext);$i++)
		if(stristr($file, ".".$ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
		$files[] = $file;
		closedir($handle);
	}

	sort($files);
	
	return $files;
}

function getLogo($biller) {

	$url = getURL();

	if(!empty($biller['logo'])) {
		return $url."/templates/invoices/logos/$biller[logo]";
	}
	else {
		return $url."/templates/invoices/logos/_default_blank_logo.png";
	}
}

/**
* Function: get_custom_field_name
* 
* Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie, biller/product/customer)
*
* Arguments:
* field         - The custom field in question
**/

function get_custom_field_name($field) {

    global $LANG;

		//grab the first character of the field variable
        $get_cf_letter = $field[0];
        //grab the last character of the field variable
       	$get_cf_number = $field[strlen($field)-1];
	
/*
	if ($get_cf_letter == "b") {
		$custom_field_name = $LANG['biller'];
	}
	if ($get_cf_letter == "c") {
		$custom_field_name = $LANG['customer'];
	}
	if ($get_cf_letter == "i") {
		$custom_field_name = $LANG['invoice'];
	}
	if ($get_cf_letter == "p") {
		$custom_field_name = $LANG['product'];
	}
*/

// functon to return false if invalid custom_field
	$custom_field_name = "";
	switch ($get_cf_letter) {
		case "b":  $custom_field_name = $LANG['biller'];	break;
		case "c":  $custom_field_name = $LANG['customer'];	break;
		case "i":  $custom_field_name = $LANG['invoice'];	break;
		case "p":  $custom_field_name = $LANG['products'];	break;
		default :  $custom_field_name = false;
	}
	//if (!$custom_field_name) $custom_field_name .= " :: " . $LANG["custom_field"] . " " . $get_cf_number ;
	$custom_field_name .= " :: " . $LANG["custom_field"] . " " . $get_cf_number ;

    return $custom_field_name;
}

function dropDown($choiceArray, $defVal) {

	$dropDown = '<select name="value">' . "\n";

	foreach ($choiceArray as $key => $value)
	{
		if ($key == $defVal) {
			$dropDown .= "\n" . '<OPTION SELECTED style="font-weight: bold" value='.$key.'>'.$value.'</OPTION>';
		} else {
			$dropDown .= "\n" . '<OPTION '.$selected.' value='.$key.'>'.$value.'</OPTION>';
		}
	}
	$dropDown .= "\n</select>";

	return $dropDown;
}

function simpleInvoicesError($type, $info1 = "", $info2 = "") 
{

    switch ($type)
    {

        case "notWriteable":

            $error = exit("
            <br />
            ===========================================<br />
            Simple Invoices error<br />
            ===========================================<br />
            The ".$info1." <b>".$info2."</b> has to be writeable");
        break;
        
        case "dbConnection":
        
            $error = exit("
            <br />
            ===========================================<br />
            Simple Invoices database connection problem<br />
            ===========================================<br />
            <br />
            Could not connect to the Simple Invoices database<br /><br />
            For information on how to fix this pease refer to the following database error: <br /> --> <b>$info1</b><br /><br />
            If this is an Access denied error please enter the correct database connection details config/config.php
            <br />
            <br />
            <b>Note:</b> If you are installing Simple Invoices please follow the below steps: 
            <br />1. Create a blank MySQL database
            <br />2. Enter the correct database connection details in the config/config.php file
            <br />3. Refresh this page
        
            <br />
            <br />
            ===========================================<br />
            ");
        break;

        case "PDO":
        
            $error = exit("
            <br />
            ===========================================<br />
            Simple Invoices - PDO problem<br />
            ===========================================<br />
            <br />
            PDO is not configured in your PHP installation.<br />  
            This means that Simple Invoices can't be used.<br /><br />

            To fix this please installed the pdo_mysql php extension.<br />
            If you are using a webhost please email them and get them to <br />
            install PDO for PHP with the MySQL extension<br /><br />
            ===========================================<br />
            ");
        break;

        case "sql":
        
            $error = exit("
            <br />
            ===========================================<br />
            Simple Invoices - SQL problem<br />
            ===========================================<br />
            <br />
            The following sql statement:<br />  
            ".$info2."<br /><br />

            had the following error code: ".$info1['1']."<br />
            with the message of:".$info1['2']."<br />
            <br />
            ===========================================<br />
            ");
        break;
        case "PDO_mysql_attr":
        
            $error = exit("
            <br />
            ===========================================<br />
            Simple Invoices - PDO - MySQL problem<br />
            ===========================================<br />
            <br />
            Your Simple Invoices installation can't use the<br />
            database settings 'database.utf8'.<br /><br />  

            To fix this please edit config/config.php and<br />
            set 'database.utf8' to 'false'<br />
            <br />
            ===========================================<br />
            ");
        break;

    }

    return $error;

}

function checkConnection() {
	global $dbh;
	global $db_server;
	
	if(!$dbh) {
		simpleInvoicesError("dbConnection",$db_server,$dbh->errorInfo());
	}
}

function getLangList() {
 $startdir = './lang/';
 $ignoredDirectory[] = '.';
 $ignoredDirectory[] = '..';
 $ignoredDirectory[] = '.svn';
  if (is_dir($startdir)){
      if ($dh = opendir($startdir)){
          while (($folder = readdir($dh)) !== false){
              if (!(array_search($folder,$ignoredDirectory) > -1)){
                if (filetype($startdir . $folder) == "dir"){
					  $folderList[] = $folder;
                  }
              }
          }
          closedir($dh);
      }
  }
  sort($folderList);
  return($folderList);
}

function sql2xml($sth, $count) {

	//you can choose any name for the starting tag
	$xml = ("<result>");
	$xml .= "<page>1</page>";
	$xml .= "<total>".$count."</total>";
	//while($row = $sth->fetch(PDO::FETCH_ASSOC) )
	foreach($sth as $row)
	{
		//count the no. of  columns in the table
		$fcount = count($row);

		$xml .= ("<tablerow>");
/*
		if(isset($actions))
		{
			$xml .= ("<actions><a href='index.php'>TEST</a>
</actions>");
		}	
*/
		//for($i=0; $i < $fcount; $i++)
		foreach($row as $key => $value)
		{
		//	$tag = mysql_field_name( $query4xml, $i );
		//	$xml .= ("<$tag>". $row[$i]. "</$tag>");
			$xml .= ("<$key>". htmlsafe($value). "</$key>");
		}
		$xml .= ("</tablerow>");
	}
	$xml .= ("</result>");

	return $xml;
}

/**
* Function: si_truncate
* 
* Trucate a given string
* 
* Parameters:
* string	- the string to truncate
* max		- the max length in characters to truncate the string to 
* rep		- characters to be added at end of truncated string

*
* Returns:
* The array sorted as you want
**/
function si_truncate($string, $max = 20, $rep = '')
{
    if (strlen($string) <= ($max + strlen($rep)))
    {
        return $string;
    }
    $leave = $max - strlen ($rep);
    return substr_replace($string, $rep, $leave);
}


/* Escapes HTML stuff */
function htmlsafe($str) {
#    if (get_magic_quotes_gpc())
#    {
#        return stripslashes(htmlentities($str, ENT_QUOTES, 'UTF-8'));
#    } else {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
#    }
}

/* Makes a string to be put inside a href="" safe */
function urlsafe($str) {
    $str = preg_replace('/[^a-zA-Z0-9@;:%_\+\.~#\?\/\=\&\/\-]/','',$str);
    if(preg_match('/^\s*javascript/i', $str)) //no javascript urls
    {
        return false;
    }
    $str = htmlsafe($str);
    return $str;
}

/* Sanitises HTML for output stuff */
function outhtml($html) {

    $config = HTMLPurifier_Config::createDefault();

    // configuration goes here:
    $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $config->set('HTML.Doctype', 'XHTML 1.0 Strict'); // replace with your doctype

    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);

}

//Generates a token to be used on forms that change something
function siNonce($action = false, $userid = false, $tickTock = false)
{
    global $config;
    global $auth_session;
    
    $tickTock = ($tickTock) ? $tickTock : floor(time()/$config->nonce->timelimit);
    
    if(!$userid)
    {
        $userid = $auth_session->id; 
    }
    
    $hash = md5($tickTock.':'.$config->nonce->key.':'.$userid.':'.$action);
    
    return $hash;
}

//Verify a nonce token
function verifySiNonce($hash, $action, $userid = false)
{
    global $config;
    
    $tickTock = floor(time()/$config->nonce->timelimit);
    if(!isempty($hash) AND ($hash === siNonce($action, $userid) OR $hash === siNonce($action, $userid, $tickTock-1)))
    {
        return true;
    }
    
    //else
    return false;
}

//Put this before an action is commited make sure to put a unique $action
function requireCSRFProtection($action = 'all', $userid = false)
{
    verifySiNonce($_REQUEST['csrfprotectionbysr'], $action, $userid) or die('CSRF Attack Detected');      
}

function antiCSRFHiddenInput($action = 'all', $userid = false)
{
    return '<input type="hidden" name="csrfprotectionbysr" value="'.htmlsafe(siNonce($action, $userid)).'" />';
}
