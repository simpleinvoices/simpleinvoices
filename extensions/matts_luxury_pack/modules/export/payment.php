<?php
/*
* Script: ./extensions/matts_luxury_pack/modules/export/payment.php
* 	payment export page
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-29
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
$invoiceID = $_GET['id'];
$get_format = $_GET['format'];
$get_file_type = $_GET['filetype'];

#get the invoice id
$export = new export();
$export -> format = $get_format;
$export -> file_type = $get_file_type;
$export -> file_location = 'download';
$export -> module = 'payment';
$export -> id = $invoiceID;
$export -> execute();
