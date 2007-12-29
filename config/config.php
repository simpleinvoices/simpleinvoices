<?php
//checkLogin();

######################
/* Database connection info
/* Enter your database information */
######################
$db_host = "localhost";
$db_name = "simple_invoices";
$db_user = "root";
$db_password = "";
define("TB_PREFIX","si_"); // default tabbbble prefix si_ -  Old variable: $tb_prefix = "si_";

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
$email_smtp_auth = false;   // turn on SMTP authentication
//$email_smtp_auth = true;     // turn on SMTP authentication
#if authentication is required for the smtp server please add the username and password in the two options below
$email_username = "";  	// SMTP username
$email_password = ""; 	// SMTP password

// The following two variables are referenced in /modules/invoices/email.php which uses the new PHPMailer v2.10 Beta 1 (internal version still at v2.0 RC 2)
// Backwards compatibility maintained even if the following 2 variables are omitted as with retaining an old config.php - Ap.Muthu
$email_smtpport = 25; 	// Default 25 - use 465 for secure ssl
$email_secure = "";  // one among '', 'ssl', or 'tls' - used by PHPMailer class in modules/include/mail
$email_ack = false;   // true means sender's EMail ID will be used as the Return receipt EMail ID - used by PHPMailer class in modules/include/mail

####################
/* General configs */
####################
$version = "20071229 stable";
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

#################
/* PDF config options */
#################
#size in pixels (640,800,1024)
$pdf_screen_size = 800;
#paper size (Letter,Legal,Executive,A0Oversize,A0,A1,A2,A3,A4,A5,B5,Folio,A6,A7,A8,A9,A10)
$pdf_paper_size = "A4";
#left margin of the pdf
$pdf_left_margin = 15;
#right margin of the pdf
$pdf_right_margin = 15;
#top margin of the pdf
$pdf_top_margin = 15;
#bottom margin of the pdf
$pdf_bottom_margin = 15;

####################
/* Other stuff*/
####################
#Error reporting
#error_reporting(E_ALL);
#error_reporting(E_WARNING);
#error_reporting(E_ERROR);
#error_reporting(E_ALL & ~E_NOTICE);
#error_reporting(0);

//To turn loggin on set the below to true
#define("LOGGING",false);
#define("LOGGING",true);
?>
