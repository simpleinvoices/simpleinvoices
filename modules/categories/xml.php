<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "referencia" ;
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

	$where = "";
	if ($query) $where = " WHERE domain_id = :domain_id AND $qtype LIKE '%$query%' ";


	/*Check that the sort field is OK*/
	$validFields = array('category_id', 'name','referencia','enabled');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "a.category_id";
	}

	$sql = "SELECT 
					a.category_id, 
					a.name,
					a.slug,
					a.referencia,
					(SELECT (CASE  WHEN a.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled,
					b.parent,
					(select referencia from si_categories where category_id = b.parent) as referencia_parent
				FROM 
					".TB_PREFIX."categories as a INNER JOIN ".TB_PREFIX."categories_taxonomy as b
				ON a.category_id = b.category_id
				$where
				ORDER BY 
					$sort $dir 
			    $limit";


	$result = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	return $result;

}

$sth = sql('', $dir, $start, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $start, $sort, $rp, $page);

$categories = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();
	 

$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($categories as $row) {
	$xml .= "<row id='".$row['category_id']."'>";
	$xml .= "<cell><![CDATA[
		<a class='index_table' title='$LANG[view] $LANG[category] ".$row['tax_description']."' href='index.php?module=categories&view=details&id=$row[category_id]&action=view'>
		<img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		<a class='index_table' title='$LANG[edit] $LANG[category] ".$row['tax_description']."' href='index.php?module=categories&view=details&id=$row[category_id]&action=edit'>
		<img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	]]></cell>";
	$xml .= "<cell><![CDATA[".$row['category_id']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['slug']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['referencia_parent'].$row['referencia']." ".$row['type']."]]></cell>";
	if ($row['enabled']==$LANG['enabled']) {
		$xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";				
	}	
	else {
		$xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";				
	}
	$xml .= "</row>";			
}
$xml .= "</rows>";

echo $xml;
?>