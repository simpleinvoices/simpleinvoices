<?php


include('./include/sql_patches.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#Largest debtor query - start
if ($mysql > 4) {
	$sql = "SELECT	
	        ".TB_PREFIX."customers.id as CID,
	        ".TB_PREFIX."customers.name as Customer,
	        (select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items,".TB_PREFIX."invoices where  ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = CID) as Total,
	        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where ".TB_PREFIX."account_payments.ac_inv_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = CID) as Paid,
	        (select (Total - Paid)) as Owing
	FROM
	        ".TB_PREFIX."customers,".TB_PREFIX."invoices,".TB_PREFIX."invoice_items
	WHERE
	        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id
	GROUP BY
	        Owing DESC
	LIMIT 1;
	";

	$result = mysqlQuery($sql) or die(mysql_error());

	$debtor = mysql_fetch_array($result);
}
#Largest debtor query - end

#Top customer query - start

if ($mysql > 4) {
	$sql2 = "SELECT
		".TB_PREFIX."customers.id as CID,
	        ".TB_PREFIX."customers.name as Customer,
       		(select sum(".TB_PREFIX."invoice_items.total) from ".TB_PREFIX."invoice_items,".TB_PREFIX."invoices where  ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = CID) as Total,
	        (select IF ( isnull(sum(ac_amount)), '0', sum(ac_amount)) from ".TB_PREFIX."account_payments,".TB_PREFIX."invoices where ".TB_PREFIX."account_payments.ac_inv_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = CID) as Paid,
	        (select (Total - Paid)) as Owing

	FROM
       		".TB_PREFIX."customers,".TB_PREFIX."invoices,".TB_PREFIX."invoice_items
	WHERE
	        ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id and ".TB_PREFIX."invoices.customer_id = ".TB_PREFIX."customers.id
	GROUP BY
	        Total DESC
	LIMIT 1;
";

	$result2 = mysqlQuery($sql2) or die(mysql_error());

	$customer = mysql_fetch_array($result2);
}
#Top customer query - end

#Top biller query - start
if ($mysql > 4) {
	
	$sql3 = "SELECT
		".TB_PREFIX."biller.name,  
		sum(".TB_PREFIX."invoice_items.total) as Total 
	FROM 
		".TB_PREFIX."biller, ".TB_PREFIX."invoice_items, ".TB_PREFIX."invoices 
	WHERE 
		".TB_PREFIX."invoices.biller_id = ".TB_PREFIX."biller.id and ".TB_PREFIX."invoices.id = ".TB_PREFIX."invoice_items.invoice_id GROUP BY name ORDER BY Total DESC LIMIT 1;
	";

	$result3 = mysqlQuery($sql3) or die(mysql_error());

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
