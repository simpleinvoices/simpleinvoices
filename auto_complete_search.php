<?php
define("BROWSE","browse");
//if this page has error with auth remove the above line and figure out how to do it right
include_once('./include/include_main.php');


$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

$sql = "SELECT * FROM {$tb_prefix}invoices";


$result = mysql_query($sql, $conn) or die(mysql_error());


$q = strtolower($_GET["q"]);
if (!$q) return;


while ($invoice = mysql_fetch_array($result)) {

	$sql_biller = "SELECT name FROM {$tb_prefix}biller WHERE b_id = $invoice[inv_biller_id] ";
	$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

	$biller = mysql_fetch_array($result_biller);

	$sql_customers = "SELECT c_name FROM {$tb_prefix}customers WHERE c_id = $invoice[inv_customer_id] ";
	$result_customers = mysql_query($sql_customers, $conn) or die(mysql_error());

	$customer = mysql_fetch_array($result_customers);

	$sql_invoice_type = "SELECT inv_ty_description FROM {$tb_prefix}invoice_type WHERE inv_ty_id = $invoice[inv_type] ";
	$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());
		
	$invoice_type = mysql_fetch_array($result_invoice_type);
		
	#invoice total calc - start
       $invoice['total_field'] = calc_invoice_total($invoice['inv_id']);
       $invoice['total_field_formatted'] = number_format($invoice['total_field'],2);
	#invoice total calc - end

	#amount paid calc - start
	$invoice['paid_field'] = calc_invoice_paid($invoice['inv_id']);
	$invoice['paid_field_formatted'] = number_format($invoice['paid_field'],2);
	#amount paid calc - end

	#amount owing calc - start
	$invoice['owing_field'] = $invoice['total_field'] - $invoice['paid_field'];
	$invoice['owing_field_formatted'] = number_format($invoice['total_field'] - $invoice['paid_field'],2);
	#amount owing calc - end


	if (strpos(strtolower($invoice['inv_id']), $q) !== false) {
		echo "$invoice[inv_id]|<table><tr><td class='details_screen'>Invoice:</td><td> $invoice[inv_id] </td><td  class='details_screen'>Total: </td><td>$invoice[total_field_formatted] </td></tr><tr><td class='details_screen'>Biller: </td><td>$biller[name] </td><td class='details_screen'>Paid: </td><td>$invoice[paid_field_formatted] </td></tr><tr><td class='details_screen'>Customer: </td><td>$customer[c_name] </td><td class='details_screen'>Owing: </td><td><u>$invoice[owing_field_formatted]</u></td></tr></table>\n";
	}
}


/*

foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	}
}
*/
?>
