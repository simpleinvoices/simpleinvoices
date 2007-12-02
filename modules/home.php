<?php


include('./include/sql_patches.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

global $db_server;
global $dbh;
#Largest debtor query - start
if ($db_server == 'pgsql' or $mysql > "4.1.0") {
	$sql = "SELECT	
	        	c.id as \"CID\",
	        	c.name as \"Customer\",
	        	sum(ii.total) as \"Total\",
	        	(select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Paid\",
	        	sum(ii.total) - (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Owing\"
	FROM
	        ".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	GROUP BY
		\"CID\", iv.customer_id, c.id, c.name
	ORDER BY
	        \"Owing\" DESC
	LIMIT 1;
	";

	$sth = dbQuery($sql) or die(end($dbh->errorInfo()));

	$debtor = $sth->fetch();
}
#Largest debtor query - end

#Top customer query - start

if ($db_server == 'pgsql' or $mysql > "4.1.0") {
	$sql2 = "SELECT
			c.id as \"CID\",
	        	c.name as \"Customer\",
       			sum(ii.total) as \"Total\",
	        	(select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Paid\",
	        	sum(ii.total) - (select coalesce(sum(ap.ac_amount), 0) from ".TB_PREFIX."account_payments ap INNER JOIN ".TB_PREFIX."invoices iv2 ON (ap.ac_inv_id = iv2.id) where iv2.customer_id = c.id) as \"Owing\"

	FROM
       		".TB_PREFIX."customers c INNER JOIN
		".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	GROUP BY
	        \"CID\", iv.customer_id, \"Customer\"
	ORDER BY 
		\"Total\" DESC
	LIMIT 1;
";

	$tth = dbQuery($sql2) or die(end($dbh->errorInfo()));

	$customer = $tth->fetch();
}
#Top customer query - end

#Top biller query - start
if ($db_server == 'pgsql' or $mysql > "4.1.0") {
	
	$sql3 = "SELECT
		b.name,  
		sum(ii.total) as Total 
	FROM 
		".TB_PREFIX."biller b INNER JOIN
		".TB_PREFIX."invoices iv ON (b.id = iv.biller_id) INNER JOIN
		".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id)
	GROUP BY b.name
	ORDER BY Total DESC
	LIMIT 1;
	";

	$uth = dbQuery($sql3) or die(end($dbh->errorInfo()));

	$biller = $uth->fetch();
}
#Top biller query - start



$smarty -> assign("mysql",$mysql);
$smarty -> assign("db_server",$db_server);
/*
$smarty -> assign("patch",count($patch));
$smarty -> assign("max_patches_applied", $max_patches_applied);
*/
$smarty -> assign("biller", $biller);
$smarty -> assign("customer", $customer);
$smarty -> assign("debtor", $debtor);
$smarty -> assign("title", $title);
?>
