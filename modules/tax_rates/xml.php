<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "tax_description" ;
$limit = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;



//SC: Safety checking values that will be directly subbed in
if (intval($start) != $start) {
	$start = 0;
}
if (intval($limit) != $limit) {
	$limit = 25;
}
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
	$sort = "tx_description";
}

	$sql = "SELECT 
				tax_id, 
				tax_description,
				tax_percentage,
				(SELECT (CASE  WHEN tax_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
			FROM 
				".TB_PREFIX."tax
			$where
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";


	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
	$tax = $sth->fetchAll(PDO::FETCH_ASSOC);
	$count = $sth->rowCount();
	 

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($tax as $row) {
		$xml .= "<row id='".$row['tax_id']."'>";
		$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] $LANG[tax_rate] ".utf8_encode($row['tax_description'])."' href='index.php?module=tax_rates&view=details&id=$row[tax_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
			<a class='index_table' title='$LANG[edit] $LANG[tax_rate] ".utf8_encode($row['tax_description'])."' href='index.php?module=tax_rates&view=details&id=$row[tax_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode($row['tax_id'])."]]></cell>";		
		$xml .= "<cell><![CDATA[".utf8_encode($row['tax_description'])."]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode($row['tax_percentage'])."]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode($row['enabled'])."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
