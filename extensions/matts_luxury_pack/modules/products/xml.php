<?php
// extensions/product_add_LxWxH_weight/modules/products/xml.php
header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "description" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
if (intval($page) != $page)
	$start = 0;
else
	$start = (($page-1) * $rp);

$defaults = getSystemDefaults();
$smarty -> assign("defaults", $defaults);

function sql($type='', $start, $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	global $auth_session;

	$valid_search_fields = array('id', 'description', 'unit_price');
		
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
        $validFields = array('id', 'description', 'unit_price', 'custom_field1', 'enabled');
	
	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "id";
	}
	
		//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT 
				id,
				description,
				custom_field1,
				unit_price,
				(SELECT COALESCE(SUM(quantity),0) FROM ".TB_PREFIX."invoice_items, ".TB_PREFIX."invoices, ".TB_PREFIX."preferences WHERE product_id = ".TB_PREFIX."products.id AND ".TB_PREFIX."invoice_items.domain_id = :domain_id AND ".TB_PREFIX."invoice_items.invoice_id = ".TB_PREFIX."invoices.id AND ".TB_PREFIX."invoices.preference_id = ".TB_PREFIX."preferences.pref_id AND ".TB_PREFIX."preferences.status = 1 ) AS qty_out ,
				(SELECT COALESCE(SUM(quantity),0) FROM ".TB_PREFIX."inventory WHERE product_id = ".TB_PREFIX."products.id AND domain_id = :domain_id) AS qty_in ,
				(SELECT COALESCE(reorder_level,0)) AS reorder_level ,
				(SELECT qty_in - qty_out ) AS quantity,
				(SELECT (CASE  WHEN enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
			FROM 
				".TB_PREFIX."products  
			WHERE 
				visible = 1
			AND domain_id = :domain_id
				$where
			ORDER BY 
				$sort $dir ";
	$sql.= isset($limit) ? $limit : '';

	if (empty($query)) {
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
	} else {
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
	}

	return $result;
}	

$sth = sql('', $start, $dir, $sort, $rp, $page);
$sth_count_rows = sql('count', $start, $dir, $sort, $rp, $page);

$products = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();
/*
* USES CODE FROM include/class/product.php , SO DISABLED; MY OWN IS ABOVE *
$products = new product();
$sth = $products->select_all('', $dir, $sort, $rp, $page);
$sth_count_rows = $products->select_all('count', $dir, $sort, $rp, $page);

$products_all = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();
*/
//echo sql2xml($customers, $count);
$xml = "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($products as $row) {

	$xml .= "<row id='".$row['id']."'>";//$row['iso']."'>";
	$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] ".$row['description']."' href='index.php?module=products&view=details&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
			<a class='index_table' title='$LANG[edit] ".$row['description']."' href='index.php?module=products&view=details&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";		
	
	$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
/**/
//	$xml .= "<!--cell><![CDATA[".$row['custom_field1']."]]></cell-->";
	$xml .= "<cell><![CDATA[".$row['custom_field1']."]]></cell>";
/**/
	$xml .= "<cell><![CDATA[".siLocal::number($row['unit_price'])."]]></cell>";
    if($defaults['inventory'] == '1')
    {
      	$xml .= "<cell><![CDATA[".siLocal::number_trim($row['quantity'])."]]></cell>";
    }

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
