<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "tax_description" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

$xml ="";

function sql($type='', $start, $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	global $auth_session;

	//SC: Safety checking values that will be directly subbed in
	if (intval($start) != $start) {
		$start = 0;
	}
	if (intval($rp) != $rp) {
		$rp = 25;
	}
	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if($type =="count")
	{
		unset($limit);
		$limit ="";
	}
	/*SQL Limit - end*/	
	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'ASC';
	}

	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	$where = " WHERE domain_id = :domain_id";
	if ($query) $where = " WHERE domain_id = :domain_id AND $qtype LIKE '%$query%' ";


	/*Check that the sort field is OK*/
	$validFields = array('tax_id', 'tax_description','tax_percentage','enabled');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "tax_description";
	}

	$sql = "SELECT 
					tax_id, 
					tax_description,
					tax_percentage,
					type,
					(SELECT (CASE  WHEN tax_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
				FROM 
					".TB_PREFIX."tax
				$where
				ORDER BY 
					$sort $dir 
			    $limit";


	$result = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $result;

}

$sth = sql('', $dir, $start, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $start, $sort, $rp, $page);

$tax = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();
	 

$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($tax as $row) {
	$xml .= "<row id='".$row['tax_id']."'>";
	$xml .= "<cell><![CDATA[
		<a class='index_table' title='$LANG[view] $LANG[tax_rate] ".$row['tax_description']."' href='index.php?module=tax_rates&view=details&id=$row[tax_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		<a class='index_table' title='$LANG[edit] $LANG[tax_rate] ".$row['tax_description']."' href='index.php?module=tax_rates&view=details&id=$row[tax_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	]]></cell>";
	$xml .= "<cell><![CDATA[".$row['tax_description']."]]></cell>";
	$xml .= "<cell><![CDATA[".siLocal::number($row['tax_percentage'])." ".$row['type']."]]></cell>";
	if ($row['enabled']==$LANG['enabled']) {
		$xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".utf8_encode($row['enabled'])."' title='".utf8_encode($row['enabled'])."' />]]></cell>";				
	}	
	else {
		$xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".utf8_encode($row['enabled'])."' title='".utf8_encode($row['enabled'])."' />]]></cell>";				
	}
	$xml .= "</row>";		
}
$xml .= "</rows>";

echo $xml;
?>
