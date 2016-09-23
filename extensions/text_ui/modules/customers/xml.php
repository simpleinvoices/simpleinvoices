<?php

header("Content-type: text/xml");

$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "name" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_GET['page'])) ? $_GET['page'] : "1" ;

$domain_id = domain_id::get();
$valid_search_fields = array('c.id', 'c.name');

//SC: Safety checking values that will be directly subbed in
if (intval($page) != $page) {
	$start = 0;
}
if (intval($rp) != $rp) {
	$rp = 25;
}
if (!preg_match('/^(asc|desc)$/iD', $dir)) {
	$dir = 'DESC';
}

/*SQL Limit - start*/
$start = (($page-1) * $rp);
$limit = "LIMIT $start, $rp";
/*SQL Limit - end*/

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
$validFields = array('CID', 'name', 'customer_total','owing','enabled');

if (!in_array($sort, $validFields))
	$sort = "CID";

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				c.id as CID, 
				c.name as name, 
				(SELECT (CASE  WHEN c.enabled = 0 THEN 'Disabled' ELSE 'Enabled' END )) AS enabled,
				(
					SELECT
			            coalesce(sum(ii.total),  0) AS total 
			        FROM
			            ".TB_PREFIX."invoice_items ii INNER JOIN
			            ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
			        WHERE  
			            iv.customer_id  = CID ) as customer_total,
                (
                    SELECT 
                        coalesce(sum(ap.ac_amount), 0) AS amount 
                    FROM
                        ".TB_PREFIX."payment ap INNER JOIN
                        ".TB_PREFIX."invoices iv ON (iv.id = ap.ac_inv_id AND iv.domain_id = ap.domain_id)
                    WHERE 
                        iv.customer_id = CID) AS paid,
                (select customer_total - paid ) as owing

			FROM 
				".TB_PREFIX."customers c  
			WHERE 
				c.domain_id = :domain_id
				$where
			ORDER BY 
				$sort $dir 
			$limit";

	if (empty($query)) {
		$sth = dbQuery($sql, ':domain_id', $domain_id);
	} else {
		$sth = dbQuery($sql, ':domain_id', $domain_id, ':query', "%$query%");
	}

	$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
$tth = dbQuery($sqlTotal, ':domain_id', $domain_id);
$resultCount = $tth->fetch();
$count = $resultCount[0];

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($customers as $row) {
		$xml .= "<row id='".$row['CID']."'>";
		$xml .= "<action><![CDATA[<a title='".$LANG['quick_view_tooltip']." ".$row['CID']."' href='index.php?module=customers&view=details&action=view&id=".$row['CID']."'>".$row['CID']."</a>]]></action>";
		$xml .= "<name><![CDATA[".utf8_encode($row['name'])."]]></name>";
		$xml .= "<total><![CDATA[".utf8_encode(siLocal::number($row['customer_total']))."]]></total>";
		$xml .= "<owing><![CDATA[".utf8_encode(siLocal::number($row['owing']))."]]></owing>";
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;


?> 
