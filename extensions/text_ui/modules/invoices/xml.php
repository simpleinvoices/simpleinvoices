<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_GET['page'])) ? $_GET['page'] : "1" ;

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
/*SQL Limit - end*/

$query = $_POST['query'];
$qtype = $_POST['qtype'];

$where = "";
if ($query) $where = " WHERE $qtype LIKE '%$query%' ";


/*Check that the sort field is OK*/
$validFields = array('id', 'biller', 'customer', 'invoice_total','owing','date','aging','type');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "id";
}


//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";

if ($db_server == 'pgsql') {
       $sql = "
		SELECT
			 iv.id,
			 b.name AS Biller,
			 c.name AS Customer,
			 sum(ii.total) AS INV_TOTAL,
			 coalesce(SUM(ap.ac_amount), 0)  AS INV_PAID,
			 (SUM(ii.total) - coalesce(sum(ap.ac_amount), 0)) AS INV_OWING ,
			 to_char(date,'YYYY-MM-DD') AS Date ,
			 (SELECT now()::date - iv.date) AS Age,
			 (CASE WHEN now()::date - iv.date <= '14 days'::interval THEN '0-14'
			  WHEN now()::date - iv.date <= '30 days'::interval THEN '15-30'
			  WHEN now()::date - iv.date <= '60 days'::interval THEN '31-60'
			  WHEN now()::date - iv.date <= '90 days'::interval THEN '61-90'
			  ELSE '90+'
			 END) AS Aging,
			 iv.type_id As type_id,
			 p.pref_description AS Type
		FROM
			 " . TB_PREFIX . "invoices iv
			 LEFT JOIN " . TB_PREFIX . "payment ap ON ap.ac_inv_id = iv.id
			 LEFT JOIN " . TB_PREFIX . "invoice_items ii ON ii.invoice_id = iv.id
			 LEFT JOIN " . TB_PREFIX . "biller b ON b.id = iv.biller_id
			 LEFT JOIN " . TB_PREFIX . "customers c ON c.id = iv.customer_id
			 LEFT JOIN " . TB_PREFIX . "preferences p ON p.pref_id = iv.preference_id
		$where
		GROUP BY
			iv.id, b.name, c.name, date, age, aging, type
		ORDER BY
			$sort $dir
		LIMIT $limit OFFSET $start";
} else {
       $sql ="
		SELECT  iv.id,
		       b.name AS biller,
		       c.name AS customer,
		       (SELECT SUM(coalesce(ii.total,  0)) FROM " .
		TB_PREFIX . "invoice_items ii WHERE ii.invoice_id = iv.id) AS invoice_total,
		       (SELECT SUM(coalesce(ac_amount, 0)) FROM " .
		TB_PREFIX . "payment ap WHERE ap.ac_inv_id = iv.id) AS INV_PAID,
		       (SELECT (coalesce(invoice_total,0) - coalesce(INV_PAID,0))) As owing,
		       DATE_FORMAT(date,'%Y-%m-%d') AS date,
		       (SELECT IF((owing = 0), 0, DateDiff(now(), date))) AS Age,
		       (SELECT (CASE   WHEN Age = 0 THEN ''
		                                       WHEN Age <= 14 THEN '0-14'
		                                       WHEN Age <= 30 THEN '15-30'
		                                       WHEN Age <= 60 THEN '31-60'
		                                      WHEN Age <= 90 THEN '61-90'
		                                       ELSE '90+'  END)) AS aging,
		       iv.type_id As type_id,
		       pf.pref_description AS preference
		FROM   " . TB_PREFIX . "invoices iv
		               LEFT JOIN " . TB_PREFIX . "biller b ON b.id = iv.biller_id
		               LEFT JOIN " . TB_PREFIX . "customers c ON c.id = iv.customer_id
		               LEFT JOIN " . TB_PREFIX . "preferences pf ON pf.pref_id = iv.preference_id
		$where
		ORDER BY
		$sort $dir
		$limit";
}

$sth = dbQuery($sql) or die(end($dbh->errorInfo()));
$invoices = $sth->fetchAll(PDO::FETCH_ASSOC);

global $dbh;

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."invoices";
$tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
$resultCount = $tth->fetch();
$count = $resultCount[0];
//echo sql2xml($invoices, $count);

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($invoices as $row) {
		$xml .= "<row id='".$row['id']."'>";
		$xml .= "<action><![CDATA[<a href='index.php?module=invoices&view=quick_view&invoice=".$row['id']."'>".utf8_encode($row['id'])."</a>]]></action>";
		$xml .= "<customer><![CDATA[".utf8_encode($row['customer'])."]]></customer>";
		$xml .= "<date><![CDATA[".utf8_encode($row['date'])."]]></date>";
		$xml .= "<invoice_total><![CDATA[".utf8_encode(siLocal::number($row['invoice_total']))."]]></invoice_total>";
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;






?> 
