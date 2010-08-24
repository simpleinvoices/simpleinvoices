<?php

header("Content-type: text/xml");

//global $auth_session;
//global $dbh;

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "name" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

$xml ="";

function sql($type='', $start, $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	global $auth_session;

		
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
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	
	$where = "  WHERE c.domain_id = :domain_id";
	if ($query) $where = " WHERE c.domain_id = :domain_id AND $qtype LIKE '%$query%' ";
	
	
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
					(SELECT (CASE  WHEN c.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled,
					(
						SELECT
				            coalesce(sum(ii.total),  0) AS total 
				        FROM
				            ".TB_PREFIX."invoice_items ii INNER JOIN
				            ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id)
				        WHERE  
				            iv.customer_id  = CID ) as customer_total,
	                (
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
				$where
				ORDER BY 
					$sort $dir 
				$limit";
	
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
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
		$xml .= "<row id='".$row['CID']."'>";
		$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] $LANG[customer] ".$row['name']."' href='index.php?module=customers&view=details&id=$row[CID]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
			<a class='index_table' title='$LANG[edit] $LANG[customer] ".$row['name']."' href='index.php?module=customers&view=details&id=$row[CID]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['customer_total'])."]]></cell>";
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