<?php
include('./config/config.php');


$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

$sql = "select * from si_invoices";


$result = mysql_query($sql, $conn) or die(mysql_error());



$q = strtolower($_GET["q"]);
if (!$q) return;


/*
$items = mysql_fetch_array($result);

$items = array(
"Great Bittern"=>"Botaurus stellaris",
);
*/

while ($Array = mysql_fetch_array($result)) {
	$inv_idField = $Array['inv_id'];
	$inv_biller_idField = $Array['inv_biller_id'];
	$inv_customer_idField = $Array['inv_customer_id'];
	$inv_typeField = $Array['inv_type'];
	$inv_preferenceField = $Array['inv_preference'];
	$inv_dateField = date( $config['date_format'], strtotime( $Array['inv_date'] ) );
	$inv_noteField = $Array['inv_note'];

	$sql_biller = "select b_name from si_biller where b_id = $inv_biller_idField ";
	$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

	while ($billerArray = mysql_fetch_array($result_biller)) {
		$b_nameField = $billerArray['b_name'];


	$sql_customers = "select c_name from si_customers where c_id = $inv_customer_idField ";
	$result_customers = mysql_query($sql_customers, $conn) or die(mysql_error());

	while ($customersArray = mysql_fetch_array($result_customers)) {
		$c_nameField = $customersArray['c_name'];


	$sql_invoice_type = "select inv_ty_description from si_invoice_type where inv_ty_id = $inv_typeField ";
	$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

	while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
		$inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	

#invoice total calc - start
	$print_invoice_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$inv_idField";
	$result_print_invoice_total = mysql_query($print_invoice_total, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_print_invoice_total)) {
                $invoice_total_Field = $Array['total'];
#invoice total calc - end

#amount paid calc - start
	$x1 = "select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) as amount from si_account_payments where ac_inv_id = $inv_idField";
	$result_x1 = mysql_query($x1, $conn) or die(mysql_error());
	while ($result_x1Array = mysql_fetch_array($result_x1)) {
		$invoice_paid_Field = $result_x1Array['amount'];
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
#amount owing calc - end




	if (strpos(strtolower($inv_idField), $q) !== false) {
		echo "$inv_idField|<table><tr><td class='details_screen'>Invoice:</td><td> $inv_idField </td><td  class='details_screen'>Total: </td><td>$invoice_total_Field </td></tr><tr><td class='details_screen'>Biller: </td><td>$b_nameField </td><td class='details_screen'>Paid: </td><td>$invoice_paid_Field </td></tr><tr><td class='details_screen'>Customer: </td><td>$c_nameField </td><td class='details_screen'>Owing: </td><td><u>$invoice_owing_Field</u></td></tr></table>\n";
	}
	
}}}}}
}


/*

foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
		echo "$key|$value\n";
	}
}
*/
?>
