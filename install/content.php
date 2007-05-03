<?php
$content = <<<STAMP
<?php
//stop the direct browsing to this file - let index.php handle which files get displayed

//checkLogin();

/*Enter your database information */
\$db_host = '$db_host';
\$db_name = '$db_name';
\$db_user = '$db_user';
\$db_password = '$db_password';
\$tb_prefix = '$tb_prefix';

/*mysql version. basically if your using a version less than mysql 5 some features will be disabled*/
#$mysql = 4;
\$mysql = 5;

/*Select language for Simple Invoices to use*/
#$language = "castellano_spanish";
#$language = "catala_catalan";
#$language = "deutsch_german";
\$language = "english_UK";
#$language = "galego_galician";
#$language = "portugues_portuguese";
#$language = "romana_romanian";
#$language = "suomi_finnish";
#$language = "francais_french";

/*PDF configs*/
#installation path relative to document root of webserver 
\$install_path = "/simpleinvoices";

/*Email configs*/
\$email_host = "localhost";  // specify main and backup server - separating with ;
\$email_smtp_auth = false;     // turn on SMTP authentication
#$SMTPAuth = true;     // turn on SMTP authentication
#if authentication is required for the smtp server please add the username and password in the two options below
\$email_username = "put your username here";  // SMTP username
\$email_password = "put your password here"; // SMTP password

/*Javascript MD5 login */
/*If you want JavaScript MD5 hashing to occur so that you can run Simple Invoices on a Non-Https Server with better security Turn Uncomment MD5Auth, Generally if you do that you should turn on ChallengeLife too. ChallengeLife sets how long before a Challenge leaving the server expires in minutes (480 is a good number I think). Defaults to Off (Please don't use Simple invoices with this off, on an non- https internet server) */
#$MD5Auth = True; /*To turn of md5 auth set $MD5Auth to True*/
\$MD5Auth = FALSE; /*To Turn off md5 auth set $MD5Auth to FALSE */
#$ChallengeLife = 480; /*To turn on ChallengeLife set this to 480*/
\$ChallengeLife = 0; /*To turn off ChallengeLife set this to 0 */


/*To change the theme, enter the name of the theme folder - default is google*/
\$theme = "google";

\$version = "20070425 unstable";

/*
'Y-m-d h:m'; //Internalional format date and time 
'm-d-Y'; //US format just date 
'm-d-Y h:m'; //US format with date and time
'd-m-Y'; //UK format just date 
'd-m-Y h:m'; //UK format with date and time
'j.n.Y'; //CZ format
'Y-m-d'; //International format just the date
*/
\$config['date_format']  = 'Y-m-d'; //International format just the date

/*Export to excel/word/openoffice etc. config*/
\$spreadsheet = "xls"; //MS Excel format
#$spreadsheet = "ods"; //Open Document Format spreadsheet
\$word_processor = "doc"; //MS Word format
#$word_processor = "odt"; //Open Document Format text



#size in pixels (640,800,1024)
\$pdf_screen_size = 800;
#paper size (Letter,Legal,Executive,A0Oversize,A0,A1,A2,A3,A4,A5,B5,Folio,A6,A7,A8,A9,A10)
\$pdf_paper_size = "A4";
#left margin of the pdf
\$pdf_left_margin = 15;
#right margin of the pdf
\$pdf_right_margin = 15;
#top margin of the pdf
\$pdf_top_margin = 15;
#bottom margin of the pdf
\$pdf_bottom_margin = 15;

#Error reporting
#error_reporting(E_ALL);
#error_reporting(E_WARNING);
#error_reporting(E_ERROR);
#error_reporting(E_ALL & ~E_NOTICE);
#error_reporting(0);
?>
STAMP;

?>
