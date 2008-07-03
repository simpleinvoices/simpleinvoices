<?php
//Developed by -==[Mihir Shah]==- during my Project work
//for the output
header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['dir'])) ? $_POST['dir'] : "ASC" ;
$sort = (isset($_POST['sort'])) ? $_POST['sort'] : "description" ;
$limit = (isset($_POST['limit'])) ? $_POST['limit'] : "25" ;

//SC: Safety checking values that will be directly subbed in
if (intval($start) != $start) {
	$start = 0;
}
if (intval($limit) != $limit) {
	$limit = 25;
}
if (!preg_match('/^(asc|desc)$/iD', $dir)) {
	$dir = 'DESC';
}

/*Check that the sort field is OK*/
$validFields = array('ap.id', 'description', 'unit_price','enabled');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "ap.id";
}

$query = null;
#if coming from another page where you want to filter by just one invoice
if (!empty($_GET['id'])) {

	$id = $_GET['c_id']);
	//$query = getInvoicePayments($_GET['id']);
	
	$sql = "SELECT ap.*, c.name as cname, b.name as bname from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b where ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id and ap.ac_inv_id = :id ORDER BY ap.id DESC";
	
	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	
}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET['c_id'])) {
	//$query = getCustomerPayments($_GET['c_id']);
	$id = $_GET['c_id']);
	$sql = "SELECT ap.*, c.name as cname, b.name as bname from ".TB_PREFIX."account_payments ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b where ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id and c.id = :id ORDER BY ap.id DESC";

	$sth = dbQuery($sql, ':id', $id) or die(htmlspecialchars(end($dbh->errorInfo())));
	
}
#if you want to show all invoices - no filters
else {
	$query = getPayments();
	
	$sql = "SELECT 
				ap.*, 
				c.name as cname, 
				b.name as bname 
			FROM 
				".TB_PREFIX."account_payments ap, 
				".TB_PREFIX."invoices iv, 
				".TB_PREFIX."customers c, 
				".TB_PREFIX."biller b 
			WHERE 
				ap.ac_inv_id = iv.id 
				AND 
				iv.customer_id = c.id 
				AND iv.biller_id = b.id 
			ORDER BY 
				ap.id DESC
				$sort $dir 
			LIMIT 
				$start, $limit
				";
	

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				id, 
				description,
				unit_price,
				(SELECT (CASE  WHEN enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
			FROM 
				".TB_PREFIX."products  
			WHERE 
				visible = 1
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";

	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
}
	$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

/*
	$customers = null;

	for($i=0; $customer = $sth->fetch(PDO::FETCH_ASSOC); $i++) {
		if ($customer['enabled'] == 1) {
			$customer['enabled'] = $LANG['enabled'];
		} else {
			$customer['enabled'] = $LANG['disabled'];
		}
*/

global $dbh;

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."products WHERE visible =1";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
echo sql2xml($customers, $count);

?> 
