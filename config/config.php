<?php

define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";

$db_layer = ""; // phpreports - database abstraction layer type "", "pdo", "adodb", "pear" etc..- currently "" works and "pdo" is in implementation

#####################
/* Authentication options */
#####################
//if you want to make Simple Invoices secure and require a username and password set this to true
//$authenticationOn = "true";
$authenticationOn = "false";
//if you are using a .httaccess file
$http_auth = ""; //value: "name:password@"

#####################
/* Email configs */
#####################
$email_host = "localhost";  // specify main and backup server - separating with ;
$email_smtp_auth = false;	// turn off SMTP authentication
// $email_smtp_auth = true;	// turn on SMTP authentication
#if authentication is required for the smtp server please add the username and password in the two options below
$email_username = "";  	// SMTP username
$email_password = ""; 	// SMTP password

// The following two variables are referenced in /modules/invoices/email.php which uses the new PHPMailer v2.10 Beta 1 (internal version still at v2.0 RC 2)
// Backwards compatibility maintained even if the following 2 variables are omitted as with retaining an old config.php - Ap.Muthu
$email_smtpport = 25; 	// Default 25 - use 465 for secure ssl
$email_secure = "";		// one among '', 'ssl', or 'tls' - used by PHPMailer class in modules/include/mail
$email_ack = false;		// true means sender's EMail ID will be used as the Return receipt EMail ID - used by PHPMailer class in modules/include/mail

####################
/* General configs */
####################
$config['date_format']  = 'Y-m-d'; #International format just the date
#$config['date_format']  = 'Y-m-d h:m'; #Internalional format date and time 
#$config['date_format']  = 'm-d-Y'; #US format just date 
#$config['date_format']  = 'm-d-Y h:m'; #US format with date and time
#$config['date_format']  = 'd-m-Y'; #UK format just date 
#$config['date_format']  = 'd-m-Y h:m'; #UK format with date and time
#$config['date_format']  = 'j.n.Y'; #CZ format

/*Export to excel/word/openoffice etc. config*/
$spreadsheet = "xls"; #MS Excel format
#$spreadsheet = "ods"; #Open Document Format spreadsheet
$word_processor = "doc"; #MS Word format
#$word_processor = "odt"; #Open Document Format text

/* Version Info*/
$version = "200801 unstable";
$versionFriendlyName ="start moving to extjs";
$versionSeries = "Flying Doormat"; 
/*
versionSeries to be choosen from Carlton 'Team of the Century' players nicknames
refer:
http://en.wikipedia.org/wiki/Carlton_FC
http://en.wikipedia.org/wiki/Carlton_FC#Carlton.27s_Team_of_the_Century
http://en.wikipedia.org/wiki/Bruce_Doull
*/

//TODO remove this before release
//dev stuff
$config_inc_style 	= "true";
$config_inc_old_js 	= "true";
if ($smarty) {
	$smarty->assign('config_inc_style', $config_inc_style);
	$smarty->assign('config_inc_old_js', $config_inc_old_js);
}

#Error reporting
#error_reporting(E_ALL);
#error_reporting(E_WARNING);
#error_reporting(E_ERROR);
#error_reporting(E_ALL & ~E_NOTICE);
#error_reporting(0);

//To turn logging on set the below to true - not needed as it is set in System Defaults
define("LOGGING",false);
#define("LOGGING",true);

####################
/* Extensions */
####################
//1 = enabled 0 = disabled
//name is the director in ./extensions which it lives
$extension['gene']['name'] = "gene";
$extension['gene']['description'] = "gene's purchase order based system extension";
$extension['gene']['enabled'] = "0";
$extension['test']['name'] = "test extension";
$extension['test']['description'] = "this is a test";
$extension['test']['enabled'] = "0";
// dev guys to enable extensions via your local.config.php file

####################
/* Environment*/
####################
/*
This allows you to have another local config file for your dev or other purposes
ie. dev.config.php 
any config.php setting in this extra file(which wont be kept in svn) will overwrite config.php values
- this way everyone can have there own conf setting without messing with anyones setting
RELEASE TODO: make sure $environment is set back to live
*/
$environment = "dev"; //test,staging,dev,live etc..

if($environment != "production")
{
    @include("./config/".htmlspecialchars($environment).".config.php");
}

?>
