<?php
/*
* Script: ./extensions/matts_luxury_pack/modules/export/deliverynote.php
* 	deliverynote export page
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
#define("BROWSE","browse");

// @formatter:off
$invoiceID     = (isset($_GET['id']      ) ? $_GET['id']       : "");
$get_format    = (isset($_GET['format']  ) ? $_GET['format']   : "");
$get_file_type = (isset($_GET['filetype']) ? $_GET['filetype'] : "");

#get the invoice id
$export 				= new myDNexport();
$export->format 		= $get_format;
if (isset($get_file_type))
	$export->file_type 	= $get_file_type;
$export->file_location 	= 'download';
$export->module 		= 'deliverynote';
$export->id 			= $invoiceID;
$export->execute();
// @formatter:on
