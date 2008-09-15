<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "name" ;
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

$where = "";
if ($query) $where = " WHERE $qtype LIKE '%$query%' ";



/*Check that the sort field is OK*/
$validFields = array('id', 'name', 'email','enabled');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "name";
}

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				id, 
				name, 
				email,
				(SELECT (CASE  WHEN enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled

			FROM 
				".TB_PREFIX."biller  
			$where
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";

	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth->rowCount();

//echo sql2xml($customers, $count);
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($customers as $row) {
	$xml .= "<row id='".$row['iso']."'>";
	$xml .= "<cell><![CDATA[
	<a class='index_table' title='$LANG[view] ".utf8_encode($row['name'])."' href='index.php?module=customers&view=details&id=$row[id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	<a class='index_table' title='$LANG[edit] ".utf8_encode($row['name'])."' href='index.php?module=customers&view=details&id=$row[id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	]]></cell>";
	$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";		
	$xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
	$xml .= "<cell><![CDATA[".utf8_encode($row['email'])."]]></cell>";
	$xml .= "<cell><![CDATA[".utf8_encode($row['enabled'])."]]></cell>";				
	$xml .= "</row>";		
}

$xml .= "</rows>";
echo $xml;

?> 
