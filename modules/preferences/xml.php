<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "pref_description" ;
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
$validFields = array('pref_id', 'pref_description','enabled');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "pref_description";
}

	$sql = "SELECT 
				pref_id, 
				pref_description,
				(SELECT (CASE  WHEN pref_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
			FROM 
				".TB_PREFIX."preferences 
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";


	$sth = dbQuery($sql) or die(htmlspecialchars(end($dbh->errorInfo())));
	$preferences = $sth->fetchAll(PDO::FETCH_ASSOC);
	$count = $sth->rowCount();
	 

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($preferences as $row) {
		$xml .= "<row id='".$row['pref_id']."'>";
		$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] $LANG[preference] ".utf8_encode($row['pref_description'])."' href='index.php?module=preferences&view=details&id=$row[pref_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
			<a class='index_table' title='$LANG[edit] $LANG[preference] ".utf8_encode($row['pref_description'])."' href='index.php?module=preferences&view=details&id=$row[pref_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode($row['pref_id'])."]]></cell>";		
		$xml .= "<cell><![CDATA[".utf8_encode($row['pref_description'])."]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode($row['enabled'])."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;




?> 
