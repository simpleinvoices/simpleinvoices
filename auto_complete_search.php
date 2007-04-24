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


while ($Array = mysql_fetch_array($result)) {
	$inv_idField = $Array['inv_id'];
	$inv_biller_idField = $Array['inv_biller_id'];
	$inv_customer_idField = $Array['inv_customer_id'];
	$inv_typeField = $Array['inv_type'];
	$inv_preferenceField = $Array['inv_preference'];
	$inv_dateField = date( $config['date_format'], strtotime( $Array['inv_date'] ) );
	$inv_noteField = $Array['inv_note'];

	$sql_biller = "select name from {$tb_prefix}biller where b_id = $inv_biller_idField ";
	$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

	while ($billerArray = mysql_fetch_array($result_biller)) {
		$b_nameField = $billerArray['name'];


	$sql_customers = "select c_name from {$tb_prefix}customers where c_id = $inv_customer_idField ";
	$result_customers = mysql_query($sql_customers, $conn) or die(mysql_error());

	while ($customersArray = mysql_fetch_array($result_customers)) {
		$c_nameField = $customersArray['c_name'];


	$sql_invoice_type = "select inv_ty_description from {$tb_prefix}invoice_type where inv_ty_id = $inv_typeField ";
	$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

	while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
		$inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	

#invoice total calc - start
        $invoice_total_Field = calc_invoice_total($inv_idField);
        $invoice_total_Field_formatted = number_format($invoice_total_Field,2);
#invoice total calc - end

#amount paid calc - start
	$invoice_paid_Field = calc_invoice_paid($inv_idField);
	$invoice_paid_Field_formatted = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
	$invoice_owing_Field_formatted = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end




	if (strpos(strtolower($inv_idField), $q) !== false) {
		echo "$inv_idField|<table><tr><td class='details_screen'>Invoice:</td><td> $inv_idField </td><td  class='details_screen'>Total: </td><td>$invoice_total_Field_formatted </td></tr><tr><td class='details_screen'>Biller: </td><td>$b_nameField </td><td class='details_screen'>Paid: </td><td>$invoice_paid_Field_formatted </td></tr><tr><td class='details_screen'>Customer: </td><td>$c_nameField </td><td class='details_screen'>Owing: </td><td><u>$invoice_owing_Field_formatted</u></td></tr></table>\n";
	}
	
}}}
}


/*

foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	}
}
*/
?>
