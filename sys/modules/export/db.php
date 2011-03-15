<?php
/*
* Script: template.php
* 	invoice export page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
#define("BROWSE","browse");

$get_format = $_GET['format'];
$get_file_type = $_GET['filetype'];

#get the invoice id
$export = new export();
$export -> format = $get_format;
$export -> file_type = $get_file_type;
$export -> module = 'database';
$export -> execute();


?>
