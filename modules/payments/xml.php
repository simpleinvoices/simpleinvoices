<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "DESC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "ap.id" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $auth_session;

	$valid_search_fields = array('ap.id','b.name', 'c.name');

	//SC: Safety checking values that will be directly subbed in
	if (intval($start) != $start) {
		$start = 0;
	}
	if (intval($limit) != $limit) {
		$limit = 25;
	}
	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
	}

	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if($type =="count")
	{
		unset($limit);
	}
	/*SQL Limit - end*/

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
	$validFields = array('ap.id', 'ac_inv_id', 'description', 'unit_price','enabled');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "ap.id";
	}

	$sql = "SELECT 
				ap.*
				, c.name as cname
				, (SELECT CONCAT(pr.pref_inv_wording,' ',iv.index_id)) as index_name
				, b.name as bname
				, pt.pt_description AS description
				, ac_notes AS notes
				, DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
			FROM 
				".TB_PREFIX."payment ap
				INNER JOIN ".TB_PREFIX."invoices iv      ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
				INNER JOIN ".TB_PREFIX."customers c      ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
				INNER JOIN ".TB_PREFIX."biller b         ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
				INNER JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = ap.domain_id)
				INNER JOIN ".TB_PREFIX."payment_types pt ON (pt.pt_id = ap.ac_payment_type AND pt.domain_id = ap.domain_id)
			WHERE 
				ap.domain_id = :domain_id ";

	#if coming from another page where you want to filter by just one invoice
	if (!empty($_GET['id'])) {

		$id = $_GET['id'];
		
		$sql .= " 
			AND ap.ac_inv_id = :invoice_id
			$where
			ORDER BY 
				$sort $dir 
			$limit";
		
		if (empty($query)) {
			$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $id);
		} else {
			$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $id, ':query', "%$query%");
		}
	}
	#if coming from another page where you want to filter by just one customer
	elseif (!empty($_GET['c_id'])) {
		
		//$query = getCustomerPayments($_GET['c_id']);
		$id = $_GET['c_id'];
		$sql .= " 
			AND c.id = :id 
			ORDER BY 
				$sort $dir  
			$limit";

		$result = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id);
		
	}
	#if you want to show all invoices - no filters
	else {
		//$query = getPayments();
		
		$sql .= " 
				$where
			ORDER BY 
				$sort $dir 
			$limit";
					
		if (empty($query)) {
			$result =  dbQuery($sql, ':domain_id', $auth_session->domain_id);
		} else {
			$result =  dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
		}
	}
	
	return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$payments = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();
/*
$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."payment";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
//echo sql2xml($customers, $count);
*/

	$xml  = "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($payments as $row) {
		$label_esc = htmlspecialchars($row['index_name'] ?? (string)$row['id']);
		$action  = '<div class="dropdown">';
		$action .= '<a class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
		$action .= '<div class="dropdown-menu dropdown-menu-end">';
		$action .= '<a class="dropdown-item" href="index.php?module=payments&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$label_esc.'</a>';
		$action .= '<a class="dropdown-item" href="index.php?module=payments&amp;view=print&amp;id='.$row['id'].'"><i class="ti ti-printer me-2"></i>'.$LANG['print_preview_tooltip'].' '.$label_esc.'</a>';
		$action .= '</div></div>';
		$xml .= "<row id='".$row['id']."'>";
		$xml .= "<cell><![CDATA[".$action."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['cname']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['bname']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['ac_amount'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
	
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?>
