<?php

header("Content-type: text/xml");

$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "ASC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "email" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;


function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	global $auth_session;

	$valid_search_fields = array('email', 'ur.name');
	
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
	$validFields = array('id', 'role', 'email');
	
	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "email";
	}
	
	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT
				u.id,
				u.name,
				u.email,
				ur.name as role,
				(SELECT (CASE WHEN u.enabled = ".ENABLED." THEN '".$LANG['enabled']."' ELSE '".$LANG['disabled']."' END )) AS enabled,
				user_id
			FROM
				".TB_PREFIX."user u LEFT JOIN
				".TB_PREFIX."user_role ur ON (u.role_id = ur.id)
			WHERE u.domain_id = :domain_id
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

$user = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();

//echo sql2xml($customers, $count);
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($user as $row) {
	$display_esc = htmlspecialchars(!empty($row['name']) ? $row['name'] : $row['email']);
	$action  = '<div class="dropdown">';
	$action .= '<a class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline">'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=user&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$display_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=user&amp;view=details&amp;id='.$row['id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$display_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";
	$xml .= "<cell><![CDATA[".htmlspecialchars($row['name'] ?? '')."]]></cell>";
	$xml .= "<cell><![CDATA[".htmlspecialchars($row['email'])."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['role']."]]></cell>";
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
