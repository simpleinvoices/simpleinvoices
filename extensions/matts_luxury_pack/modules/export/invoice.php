<?php
/*
* Script: ./extensions/matts_luxury_pack/modules/export/invoice.php
* 	invoice export page
*
* Authors:
*	 git0matt@gmail.com
*
* Last edited:
* 	 2016-09-15
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

 #define("BROWSE","browse");

// @formatter:off
$invoiceID     = (isset($_GET['id']      ) ? $_GET['id']       : "");
$get_format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$get_file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");
/**/
#get the invoice id
$export 				= new myexport();
$export->format 		= $get_format;
if (isset($get_file_type))
	$export->file_type 	= $get_file_type;
$export->file_location 	= 'download';
$export->module 		= 'invoice';
$export->id 			= $invoiceID;
$export->execute();
// @formatter:on
