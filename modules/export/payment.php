<?php
/*
 *  Script: template.php
 * 	    invoice export page
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 * 	    http://www.simpleinvoices.org
 */
<<<<<<< HEAD
// @formatter:off
$id        = $_GET['id'];
$format    = $_GET['format'];
$file_type = $_GET['filetype'];

$export = new export();
$export->id        = $id;
$export->format    = $format;
$export->file_type = $file_type;
$export->module    = 'payment';
$export->setDownload(true);
$export->execute();
// @formatter:on
=======

$invoiceID = $_GET['id'];
$get_format = $_GET['format'];
$get_file_type = $_GET['filetype'];

// get the invoice id
$export = new export();
$export -> format = $get_format;
$export -> file_type = $get_file_type;
$export -> file_location = 'download';
$export -> module = 'payment';
$export -> id = $invoiceID;
$export -> execute();

?>
>>>>>>> refs/remotes/simpleinvoices/master
