<?php

header("Content-type: text/xml");

$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "ASC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "pt_description" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;


function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $auth_session;
	global $LANG;

	$valid_search_fields = array('pt_id', 'pt_description');

	//SC: Safety checking values that will be directly subbed in
	if (intval($start) != $start) {
		$start = 0;
	}
	if (intval($limit) != $rp) {
		$rp = 25;
	}

	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if($type =="count")
	{
		unset($limit);
	}
	/*SQL Limit - end*/	

	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'ASC';
	}

	$where = "";
	$query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
	$qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;
	if ( ! (empty($qtype) || empty($query)) ) {
		if ( in_array($qtype, $valid_search_fields) ) {
			$where = " AND $qtype LIKE :query ";
		} else {
			$qtype = null;
			$query = null;
		}
	}

	/*Check that the sort field is OK*/
	$validFields = array('pt_id', 'pt_description','enabled');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "pt_description";
	}

		$sql = "SELECT 
					pt_id,
					pt_description, 
					(SELECT (CASE  WHEN pt_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
			FROM 
					".TB_PREFIX."payment_types
			WHERE domain_id = :domain_id
				$where
			ORDER BY 
					$sort $dir 
			$limit";

	if (empty($query)) {
		$result = dbQuery($sql,':domain_id', $auth_session->domain_id);
	} else {
		$result = dbQuery($sql,':domain_id', $auth_session->domain_id, ':query', "%$query%");
	}

	return $result;
}

 
$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$payment_types = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();

$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payment_types as $row) {
	$desc_esc = htmlspecialchars($row['pt_description']);
	$action  = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=payment_types&amp;view=details&amp;id='.$row['pt_id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$desc_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=payment_types&amp;view=details&amp;id='.$row['pt_id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$desc_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['pt_id']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['pt_description']."]]></cell>";
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
