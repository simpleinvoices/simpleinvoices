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
#define("BROWSE","browse");
#$menu=false;
checkLogin();

$biller_id   = (int)$_GET['biller_id'];
$customer_id = (int)$_GET['customer_id'];

// Verify both records exist in the current session domain before exporting
$biller   = getBiller($biller_id);
$customer = getCustomer($customer_id);
si_check_record_access($biller);
si_check_record_access($customer);

$start_date      = $_GET['start_date'];
$end_date        = $_GET['end_date'];
$show_only_unpaid = $_GET['show_only_unpaid'];
$filter_by_date  = $_GET['filter_by_date'];
$get_format      = $_GET['format'];
$get_file_type   = $_GET['filetype'];

$export = new export();
$export -> format = $get_format;
$export -> file_type = $get_file_type;
$export -> file_location = 'inline';
$export -> module = 'statement';
$export -> biller_id = $biller_id;
$export -> customer_id = $customer_id;
$export -> start_date = $start_date;
$export -> end_date = $end_date;
$export -> show_only_unpaid = $show_only_unpaid;
$export -> filter_by_date = $filter_by_date;
$export -> execute();


?>
