<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "ap.id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $auth_session;

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

	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if($type =="count")
	{
		unset($limit);
	}
	/*SQL Limit - end*/

	$where = "";
	if ($query) $where = " AND $qtype LIKE '%$query%' ";


	/*Check that the sort field is OK*/
	$validFields = array('ap.id', 'ac_inv_id', 'description', 'unit_price','enabled');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "ap.id";
	}

	$query = null;
	#if coming from another page where you want to filter by just one invoice
	if (!empty($_GET['id'])) {

		$id = $_GET['id'];
		//$query = getInvoicePayments($_GET['id']);
		
		//$sql = "SELECT ap.*, c.name as cname, b.name as bname from ".TB_PREFIX."payment ap, ".TB_PREFIX."invoices iv, ".TB_PREFIX."customers c, ".TB_PREFIX."biller b where ap.ac_inv_id = iv.id and iv.customer_id = c.id and iv.biller_id = b.id and ap.ac_inv_id = :id ORDER BY ap.id DESC";
		$sql = "SELECT 
					ap.*, 
					c.name as cname, 
					(SELECT CONCAT(p.pref_inv_wording,' ',iv.index_id)) as index_name,
					b.name as bname,
					pt.pt_description AS description,
					ac_notes AS notes,
					DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
			from 
				".TB_PREFIX."payment ap,
				".TB_PREFIX."invoices iv,
				".TB_PREFIX."customers c,
				".TB_PREFIX."preferences p,
				".TB_PREFIX."biller b ,
				".TB_PREFIX."payment_types pt 
			where 
				ap.ac_inv_id = iv.id 
				and 
				iv.customer_id = c.id 
				and 
				iv.biller_id = b.id 
				and
				ap.ac_payment_type = pt.pt_id 
				and 
				ap.ac_inv_id = :invoice_id
				and 
				ap.domain_id = :domain_id
				and 
				iv.preference_id = p.pref_id
				$where
			ORDER BY 
				$sort $dir 
				$limit";
		
		
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $_GET['id']) or die(htmlsafe(end($dbh->errorInfo())));
		
	}
	#if coming from another page where you want to filter by just one customer
	elseif (!empty($_GET['c_id'])) {
		
		//$query = getCustomerPayments($_GET['c_id']);
		$id = $_GET['c_id'];
		$sql = "SELECT 
					ap.*, 
					c.name as cname, 
					(SELECT CONCAT(p.pref_inv_wording,' ',iv.index_id)) as index_name,
					b.name as bname,
					pt.pt_description AS description,
					ac_notes AS notes,
					DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
				from 
					".TB_PREFIX."payment ap, 
					".TB_PREFIX."invoices iv, 
					".TB_PREFIX."customers c, 
					".TB_PREFIX."preferences p,
					".TB_PREFIX."biller b  ,
					".TB_PREFIX."payment_types pt 
				where 
					ap.ac_inv_id = iv.id 
					and 
					iv.customer_id = c.id 
					and 
					iv.biller_id = b.id 
					and
					ap.ac_payment_type = pt.pt_id 
					and 
					c.id = :id 
					and 
					iv.preference_id = p.pref_id
				ORDER BY 
				$sort $dir  
				$limit";

		$result = dbQuery($sql, ':id', $id) or die(htmlsafe(end($dbh->errorInfo())));
		
	}
	#if you want to show all invoices - no filters
	else {
		//$query = getPayments();
		
		$sql = "SELECT 
					ap.*, 
					c.name as cname, 
					b.name as bname,
					pt.pt_description AS description,
					ac_notes AS notes,
					(SELECT CONCAT(p.pref_inv_wording,' ',iv.index_id)) as index_name,
					DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
				FROM 
					".TB_PREFIX."payment ap, 
					".TB_PREFIX."invoices iv, 
					".TB_PREFIX."customers c, 
					".TB_PREFIX."biller b ,
					".TB_PREFIX."preferences p,
					".TB_PREFIX."payment_types pt 
				WHERE 
					ap.ac_inv_id = iv.id 
					AND 
						iv.customer_id = c.id 
					AND 
						iv.biller_id = b.id 
					AND
						ap.ac_payment_type = pt.pt_id 
					AND
						ap.domain_id = :domain_id
					and 
					iv.preference_id = p.pref_id
					$where
				ORDER BY 
					$sort $dir 
				$limit
					";
					
		$result =  dbQuery($sql,':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));
	}
	
	return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$payments = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();
/*
$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."payment";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
//echo sql2xml($customers, $count);
*/

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($payments as $row) {
		
		$notes = si_truncate($row['ac_notes'],'13','...');
		$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[
	<a class='index_table' title='$LANG[view] ".$row['name']."' href='index.php?module=payments&view=details&id=$row[id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	<a class='index_table' title='$LANG[print_preview_tooltip] ".$row['id']."' href='index.php?module=payments&view=print&id=$row[id]'><img src='images/common/printer.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	]]></cell>";
		$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['cname']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['bname']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['ac_amount'])."]]></cell>";
		$xml .= "<cell><![CDATA[".$notes."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
	
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?>
