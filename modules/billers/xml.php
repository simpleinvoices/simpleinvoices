<?php

header("Content-type: text/xml");

$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "ASC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "name" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

$xml ="";

function sql($type='', $start, $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	global $auth_session;

	$valid_search_fields = array('id', 'name', 'email');

	//SC: Safety checking values that will be directly subbed in
	if (intval($start) != $start) {
		$start = 0;
	}
	if (intval($rp) != $rp) {
		$rp = 25;
	}
	
	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $rp OFFSET $start";

	if($type =="count")
	{
		unset($limit);
		$limit="";
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

$sth = sql('', $start,  $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$start, $dir, $sort, $rp, $page);

$billers = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();

//echo sql2xml($customers, $count);
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($billers as $row) {
	$name_esc = htmlspecialchars($row['name']);
	$action  = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=billers&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$name_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=billers&amp;view=details&amp;id='.$row['id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$name_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['email']."]]></cell>";
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
