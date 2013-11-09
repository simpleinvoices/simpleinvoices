<?php

header("Content-type: text/xml");

$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$rp   = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	$domain_id = domain_id::get();
	
	//SC: Safety checking values that will be directly subbed in
	if (intval($page) != $page) {
		$page = 1;
	}
	
	if (intval($rp) != $rp) {
		$rp = 25;
	}
	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if($type =="count")
	{
		$limit = '';
	}
	/*SQL Limit - end*/	
		
	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
	}
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	
	$where = "";
	if ($query) $where .= " AND $qtype LIKE '%$query%' ";
	
	
	/*Check that the sort field is OK*/
	$validFields = array('id', 'biller_id','customer_id');
	
	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "id";
	}
	
		$sql = "SELECT 
					id, 
					name
				FROM 
					".TB_PREFIX."expense_account  
				WHERE 
					domain_id = :domain_id
					$where
				ORDER BY 
					$sort $dir 
				$limit";
	
	
	$result = dbQuery($sql, ':domain_id', $domain_id);

	return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();




//echo sql2xml($customers, $count);
$xml .= "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($customers as $row) {

	$xml .= "<row id='".$row['iso']."'>";
	$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] ".$row['description']."' href='index.php?module=expense_account&view=details&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
			<a class='index_table' title='$LANG[edit] ".$row['description']."' href='index.php?module=expense_account&view=details&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";		
	
	$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";		
	$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
	$xml .= "</row>";		
}

$xml .= "</rows>";

echo $xml;

?> 
