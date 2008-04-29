<?php

// install
$LANG['welcome'] = "Welcome to Simple Invoices Installer !";
$LANG['intro'] = "Thank you for choosing Simple Invoices. This wizard will help you configure and install our software."; 
$LANG['requirements'] = "Pay atention to requirements on the left side. Things have to be all in green. If you have some problem try to use our troubleshooter.";
$LANG['download'] = "Download the latest Simple Invoices package from ";
$LANG['download2'] = "Current software version you can see on left side.";
$LANG['next'] = "After full fill all requirements proceed to the next page. Next step a database configuration.";

// left side
$LANG['info'] = "Info: ";
$LANG['softver'] = "Software Version: ";
$LANG['phpver'] = "PHP Version: ";
$LANG['mysqlmod'] = "MySQL Module: ";
$LANG['memorylim'] = "Memory limit: ";
$LANG['gdsup'] = "GD support: ";
$LANG['xslsup'] = "XSL support: ";

$LANG['php5_true1'] = "PHP version ";
$LANG['php5_true2'] = ". OK!";
$LANG['php5_false'] = "You need PHP5";

$LANG['mysql_true'] = "MYSQL support OK!";
$LANG['mysql_false'] = "You have not MYSQL support";

$LANG['memory_caution_1'] = "You have only ";
$LANG['memory_caution_2'] = " of memory. That is not sufficient.";

$LANG['memory_valid_1'] = "You have ";
$LANG['memory_valid_2'] = ". OK!";

$LANG['GD_true'] = "GD version is 2. OK!";
$LANG['GD_2false'] = "You need higer version of GD";
$LANG['GD_false'] = "You need the library GD to make function Simple Invoices ";
$LANG['xslt_true'] = "XSL option. OK!";
$LANG['xslt_false'] = "You need the option xslt to generate documentation";

$LANG['cachedir'] = "Cache directory: ";
$LANG['cachedir_true'] ="is writeble. ";
$LANG['cachedir_false'] = "is not writeble!";

$LANG['config'] = "Config file: ";
$LANG['config_true'] ="is writeble. ";
$LANG['config_false'] = "is not writeble!";

$LANG['backup'] = "Backup folder: ";
$LANG['ok_backup'] ="is writeble. ";
$LANG['no_backup'] = "is not writeble!";

//step1
$LANG['Msg1'] = "<p>To set up your Simple Invoices database, enter the following information.</p>
<p>Simple Invoices requires access to a database in order to be installed. Your database
   user will need sufficient privileges to run programm.
   To create a database using PHPMyAdmin or a web-based control panel consult
   the documentation or ask your webhost service provider.

   Take note of the username, password, database name and hostname as you
   create the database. You will enter these items in the install script.The name of the database
    your data will be stored in. You must to have administrator rights to create a new database.</p>";

$LANG['Msg2'] = "<p>These options are only necessary for some sites. If you're not sure what you should enter here,
 leave the default settings or check with your hosting provider.</p>";
$LANG['DBHost'] = "Database Host:";
$LANG['DBName'] = "Database Name:";
$LANG['DBUsername'] = "Database Username:";
$LANG['DBPassword'] = "Database Password:";
$LANG['DBPort'] = "Database Port:";
$LANG['prefix'] = "Prefix:";

$LANG['defdbname'] = "simple_invoices";
$LANG['defdbuser'] = "root";
$LANG['defdbpass'] = "";
$LANG['defdbhost'] = "localhost";
$LANG['defdbport'] = "3306";
$LANG['defdbprefix'] = "";

// connection
$LANG['Connect'] = "Connection to server: ";
$LANG['ConnectDB_true'] = "Connected successfully";
$LANG['unableConnectDb'] = "Unable to connect";

$LANG['DBexists'] = "Database exists: ";
$LANG['ok_DBexists'] = "Yes, go back and choose another name.";
$LANG['no_DBexists'] = "No";

$LANG['DBcreate'] = "Creating database: ";
$LANG['ok_DBcreate'] = "Database created successfully.";
$LANG['no_DBcreate'] = "This user has no rights to create new database.";

$LANG['TABLES'] = "Creating tables: ";
$LANG['ok_TABLES'] = "All tables created successfully.";
$LANG['no_TABLES'] = "There is some problems to create tables.";

$LANG['backupdb'] = "Do you want to make backup of your existing data? ";
$LANG['yes_backup'] = "Yes";
$LANG['no_backup'] = "No, all data will be lost !!!";

$LANG['Msg3'] = "In next step youll need to add some more extra parametres to be writen to config file.";









$LANG['SelectDB'] = "Database selected:";
$LANG['SelectDB_true'] = "Selected";

$LANG['no_DB']= "There is some problems to select database.";
$LANG['ok_DB']= "Database selected.";
$LANG['CreateDB_true'] = "Database created and selected successfully.";
$LANG['no_userDB'] = "This user has no rights to create new database.";



$LANG['error_DBHost'] = "You must fill this field";
$LANG['error_DBName'] = "You must fill this field";
$LANG['error_DBUsername'] = "You must fill this field";
$LANG['error_DBPassword'] = "You must fill this field";
$LANG['error_prefix'] = "You must fill this field";


$LANG['unableSelectDb'] = "Unable to select database";
$LANG['existingDb'] = "The data base is existing";
$LANG['dropDbError'] = "request sql invalid";

$LANG['createDB'] = "Creation of the data base";
$LANG['replaceDB'] = "Replacement of the data base";
$LANG['insertDataYes'] = "Insert data in the data base";
$LANG['insertDataNo'] = "No insertions";

//insertion
$LANG['OpenFileFailure'] = "Impossible to open the file";
$LANG['writingSuccess'] = "The writing succeeded";
$LANG['writingNoSuccess'] = "Impossible to write in the file";
$LANG['thanks'] = "Thank you to use Simple Invoices";

//troble
$LANG['trouble'] = "We are sorry.<br> Something is wrong. Contact us.Click <b>HERE</b> to see if you can find help in our troubleshooter."

?>

