<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#if coming from another page where you want to filter by just one invoice
if (!empty($_GET['id'])) {


	$sql = "select {$tb_prefix}account_payments.*, {$tb_prefix}customers.name as CNAME, {$tb_prefix}biller.name as BNAME from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  where ac_inv_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id and {$tb_prefix}invoices.biller_id = {$tb_prefix}biller.id and {$tb_prefix}account_payments.ac_inv_id='$_GET[id]' ORDER BY {$tb_prefix}account_payments.id DESC";
}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET['c_id'])) {


	$sql = "SELECT {$tb_prefix}account_payments.*, {$tb_prefix}customers.name as CNAME, {$tb_prefix}biller.name as BNAME from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  where ac_inv_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id and {$tb_prefix}invoices.biller_id = {$tb_prefix}biller.id and {$tb_prefix}customers.id='$_GET[c_id]' ORDER BY {$tb_prefix}account_payments.id DESC ";
}
#if you want to show all invoices - no filters
else {


	$sql = "SELECT {$tb_prefix}account_payments.*, {$tb_prefix}customers.name as CNAME, {$tb_prefix}biller.name as BNAME from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  WHERE ac_inv_id = {$tb_prefix}invoices.id and {$tb_prefix}invoices.customer_id = {$tb_prefix}customers.id and {$tb_prefix}invoices.biller_id = {$tb_prefix}biller.id ORDER BY {$tb_prefix}account_payments.id DESC";
}

$query = mysqlQuery($sql);
$datas = null;

for($i=0;$data = mysql_fetch_array($query);$i++) {

		$sql = "SELECT pt_description FROM {$tb_prefix}payment_types WHERE pt_id = {$data['ac_payment_type']}";
		$query2 = mysqlQuery($sql);

		$pt = mysql_fetch_array($query2);
		
		$datas[$i] = $data;
		$datas[$i]['description'] = $pt['pt_description'];
		
}




getRicoLiveGrid("rico_payment","{ type:'number', decPlaces:0, ClassName:'alignleft' },{ type:'number', decPlaces:0, ClassName:'alignleft' },,,{ type:'number', decPlaces:2, ClassName:'alignleft' }");

$smarty -> assign("datas",$datas);

?>
