<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/product_attribute/xml.php
 * 	product attribute data XML
 *
 * Authors:
 *	yumatechnical@gmail.com
 *
 * Last edited:
 * 	2016-08-29
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$limit = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

$valid_search_fields = array('id', 'name');

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
$validFields = array('id', 'name','enabled','visible');

if (in_array($sort, $validFields)) {
	$sort = $sort;
} else {
	$sort = "id";
}

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				id, 
				name,
                enabled,
                visible
			FROM 
				".TB_PREFIX."products_attributes
			WHERE 1
				$where
			ORDER BY 
				$sort $dir 
			LIMIT 
				$start, $limit";

	if (empty($query)) {
		$sth = dbQuery($sql);
	} else {
		$sth = dbQuery($sql, ':query', "%$query%");
	}

	$customers = $sth->fetchAll(PDO::FETCH_ASSOC);
/*
	$customers = null;

	for($i=0; $customer = $sth->fetch(PDO::FETCH_ASSOC); $i++) {
		if ($customer['enabled'] == 1) {
			$customer['enabled'] = $LANG['enabled'];
		} else {
			$customer['enabled'] = $LANG['disabled'];
		}
*/
global $dbh;

$sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."products_attributes";
$tth = dbQuery($sqlTotal);
$resultCount = $tth->fetch();
$count = $resultCount[0];
//echo sql2xml($customers, $count);
$xml = "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($customers as $row) {

//	$xml .= "<row id='".print_r($row, true)."'>";
	$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[<a class='index_table' title='$LANG[view] ".$row['name']."' href='index.php?module=product_attribute&view=details&action=view&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a> <a class='index_table' title='$LANG[edit] ".$row['name']."' href='index.php?module=product_attribute&view=details&action=edit&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>]]></cell>";//Matt
	$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";		
	$xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";//Matt
	if ($row['enabled']=='1') {
		$xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";				
	}	
	else {
		$xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";				
	}
	if ($row['visible']=='1') {
		$xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['visible']."' title='".$row['visible']."' />]]></cell>";				
	}	
	else {
		$xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['visible']."' title='".$row['visible']."' />]]></cell>";				
	}
	$xml .= "</row>";		
}

$xml .= "</rows>";

echo $xml;

?> 
