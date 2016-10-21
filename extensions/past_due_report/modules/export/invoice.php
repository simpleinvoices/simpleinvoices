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
include 'extensions/past_due_report/include/class/export.php';
// @formatter:off
$id        = (isset($_GET['id']      ) ? $_GET['id']       : "");
$format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");

$export                = new export();
$export->id            = $id;
$export->format        = $format;
$export->file_type     = $file_type;
$export->module        = 'invoice';
$export->setDownload(true);
$export->execute();
// @formatter:on
