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

	$valid_search_fields = array('tax_id', 'tax_description', 'tax_percentage');

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

	$where = "";
	$query = isset($_POST['query']) ? $_POST['query'] : null;
	$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
	if ( ! (empty($qtype) || empty($query)) ) {
		if ( in_array($qtype, $valid_search_fields) ) {
			$where = " AND $qtype LIKE :query ";
		} else {
			$qtype = null;
			$query = null;
		}
	}

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
			WHERE domain_id = :domain_id
				$where
			ORDER BY 
				$sort $dir 
			$limit";

	if (empty($query)) {
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
	} else {
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
	}

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
	$desc_esc = htmlspecialchars($row['tax_description']);
	$action  = '<div class="dropdown">';
	$action .= '<a class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">'.$LANG['actions'].'</a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=tax_rates&amp;view=details&amp;id='.$row['tax_id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$desc_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=tax_rates&amp;view=details&amp;id='.$row['tax_id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$desc_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['tax_id']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['tax_id']."]]></cell>";
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
