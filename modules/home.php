<?php


include('./include/sql_patches.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#Largest debtor query - start
if ($mysql > 4) {
	$sql = "
	SELECT	
	        {$tb_prefix}customers.c_id as ID,
	        {$tb_prefix}customers.c_name as Customer,
	        (select sum(inv_it_total) from {$tb_prefix}invoice_items,{$tb_prefix}invoices where  {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Total,
	        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where {$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Paid,
	        (select (Total - Paid)) as Owing
	FROM
	        {$tb_prefix}customers,{$tb_prefix}invoices,{$tb_prefix}invoice_items
	WHERE
	        {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = c_id
	GROUP BY
	        Owing DESC
	LIMIT 1;
	";

	$result = mysql_query($sql) or die(mysql_error());

	$debtor = mysql_fetch_array($result);
}
#Largest debtor query - end

#Top customer query - start

if ($mysql > 4) {
	$sql2 = "
	SELECT
		{$tb_prefix}customers.c_id as ID,
	        {$tb_prefix}customers.c_name as Customer,
       		(select sum(inv_it_total) from {$tb_prefix}invoice_items,{$tb_prefix}invoices where  {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Total,
	        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from {$tb_prefix}account_payments,{$tb_prefix}invoices where {$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = ID) as Paid,
	        (select (Total - Paid)) as Owing

	FROM
       		{$tb_prefix}customers,{$tb_prefix}invoices,{$tb_prefix}invoice_items
	WHERE
	        {$tb_prefix}invoice_items.inv_it_invoice_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = c_id
	GROUP BY
	        Total DESC
	LIMIT 1;
";

	$result2 = mysql_query($sql2) or die(mysql_error());

	$customer = mysql_fetch_array($result2);
}
#Top customer query - end

#Top biller query - start
if ($mysql > 4) {
	
	$sql3 = "
	SELECT
		{$tb_prefix}biller.name,  
		sum({$tb_prefix}invoice_items.inv_it_total) as Total 
	FROM 
		{$tb_prefix}biller, {$tb_prefix}invoice_items, {$tb_prefix}invoices 
	WHERE 
		{$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.id and {$tb_prefix}invoices.inv_id = {$tb_prefix}invoice_items.inv_it_invoice_id GROUP BY name ORDER BY Total DESC LIMIT 1;
	";

	$result3 = mysql_query($sql3) or die(mysql_error());

	$biller = mysql_fetch_array($result3);
}
#Top biller query - start



$smarty -> assign("mysql",$mysql);
/*
$smarty -> assign("patch",count($patch));
$smarty -> assign("max_patches_applied", $max_patches_applied);
*/
$smarty -> assign("biller", $biller);
$smarty -> assign("customer", $customer);
$smarty -> assign("debtor", $debtor);
$smarty -> assign("title", $title);
?>
