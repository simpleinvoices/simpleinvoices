<?php
//Developed by -==[Mihir Shah]==- during my Project work
//for the output
header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['dir'])) ? $_POST['dir'] : "ASC" ;
$sort = (isset($_POST['sort'])) ? $_POST['sort'] : "name" ;
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
$validFields = array('CID', 'name', 'customer_total','owing','enabled');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "CID";
}

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				c.id as CID, 
				c.name as name, 
				(SELECT (CASE  WHEN c.enabled = 0 THEN 'Disabled' ELSE 'Enabled' END )) AS enabled,
				(
					SELECT
			            coalesce(sum(ii.total),  0) AS total 
			        FROM
			            ".TB_PREFIX."invoice_items ii INNER JOIN
			            ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id)
			        WHERE  
			            iv.customer_id  = CID ) as customer_total,
                (
                    SELECT 
                        coalesce(sum(ap.ac_amount), 0) AS amount 
                    FROM
                        ".TB_PREFIX."account_payments ap INNER JOIN
                        ".TB_PREFIX."invoices iv ON (iv.id = ap.ac_inv_id)
                    WHERE 
                        iv.customer_id = CID) AS owing

			FROM 
				".TB_PREFIX."customers c  
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";

	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
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

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."customers";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
echo sql2xml($customers, $count);

?> 
