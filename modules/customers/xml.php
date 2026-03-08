<?php

header("Content-type: text/xml");

//global $auth_session;
//global $dbh;

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

	$valid_search_fields = array('c.id', 'c.name');

	//SC: Safety checking values that will be directly subbed in
	if (intval($page) != $page) {
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
		$limit;
	}
	/*SQL Limit - end*/

	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
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
	$validFields = array('CID', 'name', 'department', 'customer_total', 'paid', 'owing', 'enabled');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "CID";
	}

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
					c.id as CID 
					, c.name as name 
					, c.department as department
					, (SELECT (CASE  WHEN c.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
					, SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) AS customer_total
					, COALESCE(ap.amount,0) AS paid
					, (SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) - COALESCE(ap.amount,0)) AS owing
			FROM
					".TB_PREFIX."customers c
					LEFT JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND iv.domain_id = c.domain_id)
					LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
					LEFT JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
					LEFT JOIN (SELECT iv3.customer_id, p.domain_id, SUM(COALESCE(p.ac_amount, 0)) AS amount 
							FROM ".TB_PREFIX."payment p INNER JOIN si_invoices iv3 
						ON (iv3.id = p.ac_inv_id AND iv3.domain_id = p.domain_id)
							GROUP BY iv3.customer_id, p.domain_id
						) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
			WHERE c.domain_id = :domain_id
					$where
			GROUP BY CID
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

$sth = sql('', $start, $dir, $sort, $rp, $page);
$sth_count_rows = sql('count', $start, $dir, $sort, $rp, $page);

$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";

	foreach ($customers as $row) {
		$name_esc = htmlspecialchars($row['name']);
		$action  = '<div class="dropdown">';
		$action .= '<a class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">'.$LANG['actions'].'</a>';
		$action .= '<div class="dropdown-menu dropdown-menu-end">';
		$action .= '<a class="dropdown-item" href="index.php?module=customers&amp;view=details&amp;id='.$row['CID'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$name_esc.'</a>';
		$action .= '<a class="dropdown-item" href="index.php?module=customers&amp;view=details&amp;id='.$row['CID'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$name_esc.'</a>';
		$action .= '</div></div>';
		$xml .= "<row id='".$row['CID']."'>";
		$xml .= "<cell><![CDATA[".$action."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['CID']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['customer_total'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['paid'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['owing'])."]]></cell>";
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
