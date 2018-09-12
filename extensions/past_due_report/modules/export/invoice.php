<?php
/*
 *  Script: template.php
 *      invoice export page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
require_once 'extensions/past_due_report/include/class/export.php';
// @formatter:off
$export            = new export();
$export->id        = (isset($_GET['id']      ) ? $_GET['id']       : "");
$export->format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$export->file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");
$export->module    = 'invoice';
$export->setDownload(true);
$export->execute();
// @formatter:on
