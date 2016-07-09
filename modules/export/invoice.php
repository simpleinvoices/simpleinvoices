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
// @formatter:off
$invoiceID     = (isset($_GET['id']      ) ? $_GET['id']       : "");
$get_format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$get_file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");

$export                = new export();
$export->format        = $get_format;
$export->file_type     = $get_file_type;
$export->file_location = 'download';
$export->module        = 'invoice';
$export->id            = $invoiceID;
$export->execute();
// @formatter:on
