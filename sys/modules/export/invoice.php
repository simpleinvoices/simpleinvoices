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

$invoiceID = $_GET['id'];
$get_format = $_GET['format'];
$get_file_type = (isset($_GET['filetype']))?$_GET['filetype']:'file';

#get the invoice id
$export = new export();
$export -> format = $get_format;
$export -> file_type = $get_file_type;
$export -> file_location = 'download';
$export -> module = 'invoice';
$export -> id = $invoiceID;
$export -> execute();
