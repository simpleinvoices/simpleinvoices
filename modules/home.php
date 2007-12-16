<?php


include('./include/sql_patches.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#Largest debtor query - start
if ($mysql > "4.1.0") {
	$sql = "SELECT c.id as CID, c.name as Customer,
	        (select sum(s1_ivt.total) from ".TB_PREFIX."invoice_items s1_ivt, ".TB_PREFIX."invoices s1_iv 
				where  s1_ivt.invoice_id = s1_iv.id and s1_iv.customer_id = CID) as Total,
	        (select sum(IF ( isnull(ac_amount), '0', ac_amount)) from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv 
				where ap.ac_inv_id = iv.id and iv.customer_id = CID) as Paid,
	        (select (Total - Paid)) as Owing
	FROM
	        ".TB_PREFIX."customers c 
	GROUP BY
			CID
	ORDER BY
	        Owing DESC
	LIMIT 1;
";

	$result = mysqlQuery($sql) or die(mysql_error());

	$debtor = mysql_fetch_array($result);
}
#Largest debtor query - end

#Top customer query - start

if ($mysql > "4.1.0") {
	$sql2 = "SELECT c.id as CID, c.name as Customer,
       		(select sum(s1_ivt.total) from ".TB_PREFIX."invoice_items s1_ivt,".TB_PREFIX."invoices s1_iv 
				where  s1_ivt.invoice_id = s1_iv.id and s1_iv.customer_id = CID) as Total,
	        (select sum(IF ( isnull(ac_amount), '0', ac_amount)) from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv 
				where ap.ac_inv_id = iv.id and iv.customer_id = CID) as Paid,
	        (select (Total - Paid)) as Owing
	FROM
       		".TB_PREFIX."customers c 
	GROUP BY
			CID
	ORDER BY
	        Total DESC
	LIMIT 1;
";

	$result2 = mysqlQuery($sql2) or die(mysql_error());

	$customer = mysql_fetch_array($result2);
}
#Top customer query - end

#Top biller query - start
if ($mysql > "4.1.0") {
	
	$sql3 = "SELECT b.id, b.name, sum(ivt.total) as Total 
	FROM 
		".TB_PREFIX."biller b, ".TB_PREFIX."invoice_items ivt, ".TB_PREFIX."invoices iv 
	WHERE 
		iv.biller_id = b.id and iv.id = ivt.invoice_id 
	GROUP BY 
		name 
	ORDER BY 
		Total DESC 
	LIMIT 1;
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
