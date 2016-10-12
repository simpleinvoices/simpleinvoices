<?php

header("Content-type: text/xml");

//global $dbh;

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "name" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;


function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	
	$auth_session = new Zend_Session_Namespace('Zend_Auth');

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
	}
	/*SQL Limit - end*/	
	
	
	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
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
	$validFields = array('CID', 'name', 'customer_total','owing','enabled');
	
	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "CID";
	}
	
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
				            ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id)
				        WHERE  
				            iv.customer_id  = CID ) as customer_total,
		(    SELECT MAX(id)
			FROM
				".TB_PREFIX."invoices 
			WHERE	customer_id = CID) AS last_invoice,               (
 
	                    SELECT 
	                        coalesce(sum(ap.ac_amount), 0) AS amount 
	                    FROM
	                        ".TB_PREFIX."payment ap INNER JOIN
	                        ".TB_PREFIX."invoices iv ON (iv.id = ap.ac_inv_id)
	                    WHERE 
	                        iv.customer_id = CID) AS paid,
	                ( select customer_total - paid ) AS owing
	
				FROM 
					".TB_PREFIX."customers c 
				WHERE c.domain_id = :domain_id
					$where
				ORDER BY 
					$sort $dir 
				$limit";
	
		if (empty($query)) {
			$result = dbQuery($sql, ':domain_id', domain_id::get());
		} else {
			$result = dbQuery($sql, ':domain_id', domain_id::get(), ':query', "%$query%");
		}

		return $result;
		
}	

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();


	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($customers as $row) {
		$xml .= "<row id='".$row['CID']."'>";
		$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] $LANG[customer] ".utf8_encode($row['name'])."' href='index.php?module=customers&view=details&id=$row[CID]&action=view'><img src='images/common/view.png' class='action' /></a>
			<a class='index_table' title='$LANG[edit] $LANG[customer] ".utf8_encode($row['name'])."' href='index.php?module=customers&view=details&id=$row[CID]&action=edit'><img src='images/common/edit.png' class='action' /></a>
			<a class='index_table' title='$LANG[new_invoice] $LANG[for] $LANG[customer] ".utf8_encode($row['name'])."' href='index.php?module=invoices&view=usedefault&customer_id=$row[CID]&action=edit'><img src='images/famfam/page_add.png' class='action' /></a>
		]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['CID']."]]></cell>";		
		$xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
		$xml .= "<cell><![CDATA[<a class='index_table' title='quick view' href='index.php?module=invoices&view=quick_view&id=".utf8_encode($row['last_invoice'])."'>".utf8_encode($row['last_invoice'])."</a>]]></cell>";

		$xml .= "<cell><![CDATA[".utf8_encode(siLocal::number($row['customer_total']))."]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode(siLocal::number($row['owing']))."]]></cell>";
		$xml .= "<cell><![CDATA[".utf8_encode($row['enabled'])."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;


?> 
