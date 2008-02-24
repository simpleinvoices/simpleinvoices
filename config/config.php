<?php
//checkLogin();

######################
/* Database connection info
/* Enter your database information */
######################
$db_server = "mysql"; /* Can be either mysql or pgsql */
$db_host = "localhost";
$db_name = "simple_invoices";
$db_user = "root";
$db_password = "";
define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";
//ini_set("display_errors","On");
// Different DB Abstraction methods can be used by replacing the sql_queries.php file for now
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

#################
/* PDF config options */
#################
#size in pixels (640,800,1024)
$pdf_screen_size 	= 800;
#paper size (Letter,Legal,Executive,A0Oversize,A0,A1,A2,A3,A4,A5,B5,Folio,A6,A7,A8,A9,A10)
$pdf_paper_size 	= "A4";
#margins of the pdf
$pdf_left_margin 	= 15;
$pdf_right_margin 	= 15;
$pdf_top_margin 	= 15;
$pdf_bottom_margin 	= 15;

####################
/* PHP.ini setting*/
####################
ini_set('include_path',ini_get('include_path').';./library/pear/;');//for pear
ini_set('session.use_trans_sid', false); //so session ids arent put in the url by php

####################
/* Other stuff*/
####################
//security thing: $authSessionIdentifier is used by auth process to ensure that user once logged in can not log into another install of Simple Invoices 
$authSessionIdentifier = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
//$authSessionIdentifier = "something unique to name this install of Simple nvoics";
/*You can also concat stuff to the start or end to make it harder(assuming they are not doing packet sniffing on non https line) to guess/hack your session info */
//$authSessionIdentifier .= "any random stuff you want";

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
/* Environment*/
####################
/*
This allows you to have another local config file for your dev or other purposes
ie. dev.config.php 
any config.php setting in this extra file(which wont be kept in svn) will overwrite config.php values
- this way everyone can have there own conf setting without messing with anyones setting
RELEASE TODO: make sure $environment is set back to live
*/
$environment = "local"; //test,staging,dev,live etc..
if($environment != "live")
{
	@include("./config/".htmlspecialchars($environment).".config.php");
}


?>
