<?php

header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "pt_description" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;


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
	$action .= '<a class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">'.$LANG['actions'].'</a>';
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
