<?php
header("Content-type: text/xml");

$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "ASC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "name" ;
$limit = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

$valid_search_fields = array('name', 'value');

//SC: Safety checking values that will be directly subbed in
if (intval($page) != $page) {
	$start = 0;
}
$start = (($page-1) * $limit);

if (intval($limit) != $limit) {
	$limit = 25;
}
if (!preg_match('/^(asc|desc)$/iD', $dir)) {
	$dir = 'DESC';
}

	$where = "";
	$query = $_REQUEST['query'] ?? null;
	$qtype = $_REQUEST['qtype'] ?? null;
	if ( ! (empty($qtype) || empty($query)) ) {
		if ( in_array($qtype, $valid_search_fields) ) {
			$where = " AND $qtype LIKE :query ";
		} else {
			$qtype = null;
			$query = null;
		}
	}

/*Check that the sort field is OK*/
$validFields = array('id', 'name', 'value','enabled');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "name";
}

$domain_id = domain_id::get();

	$sql = "SELECT
				v.id as id,
				a.name as name,
                v.value as value,
                v.enabled as enabled
			FROM
				".TB_PREFIX."products_attributes a LEFT JOIN
				".TB_PREFIX."products_values v ON (a.id = v.attribute_id)
			WHERE a.domain_id = :domain_id
				$where
			ORDER BY
				$sort $dir
			LIMIT
				$start, $limit";

	if (empty($query)) {
		$sth = dbQuery($sql, ':domain_id', $domain_id);
	} else {
		$sth = dbQuery($sql, ':domain_id', $domain_id, ':query', "%$query%");
	}

	$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."products_values WHERE domain_id = :domain_id";
$tth = dbQuery($sqlTotal, ':domain_id', $domain_id);
$resultCount = $tth->fetch();
$count = $resultCount[0];
//echo sql2xml($customers, $count);
$xml .= "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($customers as $row) {

	$label_esc = htmlspecialchars(($row['name'] ?? '').': '.($row['value'] ?? (string)$row['id']));
	$action  = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=product_value&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$label_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=product_value&amp;view=details&amp;id='.$row['id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$label_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";

	$xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
	$xml .= "<cell><![CDATA[".utf8_encode($row['value'])."]]></cell>";
	if ($row['enabled']=='1') {
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
