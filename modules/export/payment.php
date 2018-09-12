<?php
/*
 *  Script: template.php
 * 	    invoice export page
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 * 	    https://simpleinvoices.group
 */
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
