<?php
/*
 * Script: template.php
 * invoice export page
 *
 * License:
 * GPL v3 or above
 *
 * Website:
 * http://www.simpleinvoices.org
 */
// @formatter:off
$biller_id        = (isset($_GET ['biller_id'])        ? $_GET ['biller_id']        : "");
$customer_id      = (isset($_GET ['customer_id'])      ? $_GET ['customer_id']      : "");
$start_date       = (isset($_GET ['start_date'])       ? $_GET ['start_date']       : "");
$end_date         = (isset($_GET ['end_date'])         ? $_GET ['end_date']         : "");
$show_only_unpaid = (isset($_GET ['show_only_unpaid']) ? $_GET ['show_only_unpaid'] : "");
$filter_by_date   = (isset($_GET ['filter_by_date'])   ? $_GET ['filter_by_date']   : "");
$get_format       = (isset($_GET ['format'])           ? $_GET ['format']           : "");
$get_file_type    = (isset($_GET ['filetype'])         ? $_GET ['filetype']         : "");

// get the invoice id
$export = new export ();
$export->format           = $get_format;
$export->file_type        = $get_file_type;
$export->file_location    = 'download';
$export->module           = 'statement';
$export->biller_id        = $biller_id;
$export->customer_id      = $customer_id;
$export->start_date       = $start_date;
$export->end_date         = $end_date;
$export->show_only_unpaid = $show_only_unpaid;
$export->filter_by_date   = $filter_by_date;
$export->execute ();
// @formatter:on
