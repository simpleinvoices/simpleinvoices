<?php
header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$limit = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

//SC: Safety checking values that will be directly subbed in
if (intval($page) != $page) {
	$start = 0;
}
$start = (($page-1) * $limit);

if (intval($limit) != $limit) {
	$limit = 25;
}
if (!preg_match('/^(asc|desc)$/iD', $dir)) {
	$dir = 'DESC';
}

/*Check that the sort field is OK*/
$validFields = array('id', 'biller_id','customer_id');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "id";
}

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				id, 
				biller_id,
				customer_id
			FROM 
				".TB_PREFIX."invoices 
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

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."invoices";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
//echo sql2xml($customers, $count);
$xml .= "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($customers as $row) {

	$xml .= "<row id='".$row['iso']."'>";

	$xml .= "<cell><![CDATA['View :: Edit']]></cell>";
			
	$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";		

	$xml .= "<cell><![CDATA[".utf8_encode($row['biller_id'])."]]></cell>";

	//$xml .= "<cell><![CDATA[".print_r($_POST,true)."]]></cell>";				

	$xml .= "<cell><![CDATA[".utf8_encode($row['customer_id'])."]]></cell>";		



	$xml .= "</row>";		

}



$xml .= "</rows>";

echo $xml;

?> 
