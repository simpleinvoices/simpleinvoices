<?php

header("Content-type: text/xml");

$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "ASC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "cf_custom_field" ;
$limit = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

$xml = "";

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

//$query = $_POST['query'];
//$qtype = $_POST['qtype'];

$where = " WHERE domain_id = :domain_id";

/*Check that the sort field is OK*/
$validFields = array('cf_id', 'cf_custom_field', 'cf_custom_label','enabled');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "cf_custom_field";
}

/*
	$sql = "SELECT 
				pt_id,
				pt_description, 
				(SELECT (CASE  WHEN pt_enabled = 0 THEN '".{$LANG['disabled']}."' ELSE '".{$LANG['enabled']}."' END )) AS enabled
		FROM 
				".TB_PREFIX."payment_types
		$where
		ORDER BY 
				$sort $dir 
		LIMIT 
				$start, $limit";


	$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
	$payment_types = $sth->fetchAll(PDO::FETCH_ASSOC);
	$count = $sth->rowCount();
*/
	
	$sql = "SELECT 
				cf_id,
				cf_custom_field,
				cf_custom_label
			FROM 
				".TB_PREFIX."custom_fields
			$where
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";
			
	$sth = dbQuery($sql,':domain_id', $auth_session->domain_id);
	$count = $sth->rowCount();
	
	$cfs = null;

	$number_of_rows = 0;
	for($i=0; $cf = $sth->fetch();$i++) {
		$cfs[$i] = $cf;
		$cfs[$i]['field_name_nice'] = get_custom_field_name($cf['cf_custom_field']);
		$number_of_rows = $i;
	}
	
	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($cfs as $row) {
		$label_esc = htmlspecialchars($row['field_name_nice'] ?? $row['cf_custom_label'] ?? (string)$row['cf_id']);
		$action  = '<div class="dropdown">';
		$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
		$action .= '<div class="dropdown-menu dropdown-menu-end">';
		$action .= '<a class="dropdown-item" href="index.php?module=custom_fields&amp;view=details&amp;id='.$row['cf_id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$label_esc.'</a>';
		$action .= '<a class="dropdown-item" href="index.php?module=custom_fields&amp;view=details&amp;id='.$row['cf_id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$label_esc.'</a>';
		$action .= '</div></div>';
		$xml .= "<row id='".htmlsafe($row['cf_id'])."'>";
		$xml .= "<cell><![CDATA[".$action."]]></cell>";
		$xml .= "<cell><![CDATA[".htmlsafe($row['field_name_nice'])."]]></cell>";
		$xml .= "<cell><![CDATA[".htmlsafe($row['cf_custom_label'])."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?> 
