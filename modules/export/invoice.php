<?php
/*
 *  Script: template.php
 *      invoice export page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
<<<<<<< HEAD
// @formatter:off
$export            = new export();
$export->id        = (isset($_GET['id']      ) ? $_GET['id']       : "");
$export->format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$export->file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");
$export->module    = 'invoice';
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
$export -> module = 'invoice';
$export -> id = $invoiceID;
$export -> execute();

?>
>>>>>>> refs/remotes/simpleinvoices/master
