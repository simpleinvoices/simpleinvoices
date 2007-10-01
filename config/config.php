<?php
//checkLogin();

/*Enter your database information */
$db_host = "localhost";
$db_name = "simple_invoices";
$db_user = "root";
$db_password = "";

//if you want to make Simple Invoices secure and require a username and password set this to true
//$authenticationOn = "true";
$authenticationOn = "false";

//if you are using a .httaccess file
$http_auth = "";	//value: "name:password@"

//$tb_prefix = "si_";
define("TB_PREFIX","si_");

/*Email configs*/
$email_host = "localhost";  // specify main and backup server - separating with ;
$email_smtp_auth = true;     // turn on SMTP authentication
//$email_smtp_auth = false;     // turn on SMTP authentication
#if authentication is required for the smtp server please add the username and password in the two options below
$email_username = "";  // SMTP username
$email_password = ""; // SMTP password

/*Javascript MD5 login */
/*If you want JavaScript MD5 hashing to occur so that you can run Simple Invoices on a Non-Https Server with better security Turn Uncomment MD5Auth */
$MD5Auth = True; /*To turn of md5 auth set $MD5Auth to True*/
#$MD5Auth = FALSE; /*To Turn off md5 auth set $MD5Auth to FALSE */
#$ChallengeLife = 480; /*To turn on ChallengeLife set this to 480*/
$ChallengeLife = 0; /*To turn off ChallengeLife set this to 0 */


$version = "200711 unstable";

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

#Error reporting
#error_reporting(E_ALL);
#error_reporting(E_WARNING);
#error_reporting(E_ERROR);
#error_reporting(E_ALL & ~E_NOTICE);
#error_reporting(0);

/*Select language for Simple Invoices to use*/
#$language = "cs"; //castellano_spanish
#$language = "ca"; //catala_catalan
#$language = "cs"; //cestina_czech
#$language = "de"; //deutsch_german
#$language = "de-ch"; //deutsch_german swiss
#$language = "english_UK"; //english_UK
#$language = "fr"; //francais_french
#$language = "gl"; //galego_galician
#$language = "ls"; //slovenščina_slovenian
#$language = "nl"; //nederlands_dutch
#$language = "no"; //norwegian_NB
#$language = "pt"; //portugues_portuguese
#$language = "ro"; //romana_romanian
#$language = "fi"; //suomi_finnish

/*PDF configs*/
#installation path relative to document root of webserver 
//$install_path = "/simpleinvoices";
//$install_path = $path['dirname'];

//To turn loggin on set the below to true
#define("LOGGING",false);
#define("LOGGING",true);
?>
