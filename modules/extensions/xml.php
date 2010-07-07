<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = "id" ;
$limit = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

$xml =""; 

$extension_dir = './extensions';
$extension_entries = scandir($extension_dir);
foreach ($extension_entries as $entry) {
  	if (is_dir($extension_dir."/".$entry) and ! ereg("^\..*",$entry) ) {	//Skip entries starting with a dot
		if (file_exists ($extension_dir."/".$entry."/DESCRIPTION"))
		{
			$description = file_get_contents($extension_dir."/".$entry."/DESCRIPTION") ;
		} else {
			$description = "DESCRIPTION not available (in $extension_dir/$entry/)";
		}

		$available_extensions[$entry] = array("name"=>$entry,"enabled"=>0, "registered"=>0, "description"=>$description , "id" =>"");
	}
}

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

$query = (isset($_POST['query'])) ? $_POST['query'] : "" ; 
$qtype = (isset($_POST['qtype'])) ? $_POST['qtype'] : "" ; 

$plugin[0] = " <img src='images/famfam/plugin_disabled.png' alt='".$LANG['plugin_not_registered']."' />";
$plugin[1] = " <img src='images/famfam/plugin.png' alt='".$LANG['plugin_registered']."' />";
$plugin[2] = " <img src='images/famfam/plugin_delete.png' alt='".$LANG['plugin_unregister']."' />";
$plugin[3] = " <img src='images/famfam/plugin_add.png' alt='".$LANG['plugin_register']."' />";
$light[0] = " <img src='images/famfam/lightbulb_off.png' alt='".$LANG['disabled']."' />";
$light[1] = " <img src='images/famfam/lightbulb.png' alt='".$LANG['enabled']."' />";
$light[2] = " <img src='images/common/lightswitch16x16.png' alt='".$LANG['toggle_status']."' />";


$where = " WHERE domain_id = 0 OR domain_id = :domain_id";
if ($query) $where = " WHERE (domain_id = 0 OR domain_id = :domain_id) AND $qtype LIKE '%$query%' ";


/*Check that the sort field is OK*/
$validFields = array('id', 'name','description','enabled');

	$sql = "SELECT 
				id, 
				name,
				description,
				1 AS registered,
				enabled
			FROM 
				si_extensions
			$where
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";

	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

	$registered_extensions = $sth->fetchAll(PDO::FETCH_ASSOC);

	// registered_extensions have all extensions in the database
	// available_extensions have all extensions in the distribution
	// extensions will have a complete list of the extensions, with status info (enabled, registered)

	foreach ($registered_extensions as $reg_ext) {
		$reg_name = $reg_ext['name'];
		if (isset($available_extensions[$reg_name])) { unset($available_extensions[$reg_name]); }
	}
	$extensions = array_merge($registered_extensions,$available_extensions);

	$count = count($extensions);
	 
	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($extensions as $row) {
		$xml .= "<row id='".$row['id']."'>";
		$xml .= "<cell><![CDATA[";
	if ($row['id'] == 0 && $row['registered'] ==1) { $xml .= "Always enabled "; }
	else {
		if ($row['registered'] == 1) {
		$xml .="<a class='index_table' title='$LANG[plugin_register] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=register&id=$row[id]&action=unregister'> ".$plugin[3-$row['registered']]."</a>";
			$xml .= " <a class='index_table' title='$LANG[enable] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=manage&id=$row[id]&action=toggle'>".$light[2]."</a>";
		} else {
		$xml .="<a class='index_table' title='$LANG[plugin_register] $LANG[extensions] ".$row['name']."' href='index.php?module=extensions&view=register&name=$row[name]&action=register&description=$row[description]'> ".$plugin[3-$row['registered']]."</a>";
		}
	}
	$xml .= "]]></cell>";
		$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
		$xml .= "<cell><![CDATA[".$light[$row['enabled']].$plugin[$row['registered']]."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
