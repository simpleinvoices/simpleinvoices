<?php

header("Content-type: text/xml");

$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : 'DESC' ;
$sort = (isset($_POST['sortname']))  ? $_POST['sortname']  : 'id'   ;
$rp   = (isset($_POST['rp']))        ? $_POST['rp']        : '25'   ;
$page = (isset($_POST['page']))      ? $_POST['page']      : '1'    ;

function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	$domain_id = domain_id::get();
	
	//SC: Safety checking values that will be directly subbed in
	if (intval($page) != $page) {
		$page = 1;
	}
	if (intval($rp) != $rp) {
		$rp = 25;
	}
	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if($type =="count")
	{
		$limit='';
	}
	/*SQL Limit - end*/	
		
	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
	}

    $req = array_merge($_GET, $_POST);
	
	$query = $_REQUEST['query'];
    $qtype = $_REQUEST['qtype'];
	
	$where = "";
	if ($query!="") $where .= " AND :qtype LIKE '%:query%' ";
	
	/*Check that the sort field is OK*/
	$validFields = array('id', 'status', 'amount', 'expense_account_id','biller_id', 'customer_id', 'invoice_id','date','amount','note');
	
	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "id";
	}
	$sql = "SELECT
                e.id as EID,
                e.status as status,
                e.*,
                i.id as invoice,
                b.name as biller,
                ea.name as expense_account,
                c.name as customer,
                p.description as product,
                (select sum(tax_amount) from ".TB_PREFIX."expense_item_tax where expense_id = EID) as tax,
                (select tax + e.amount) as total,
                (CASE WHEN status = 1 THEN '".$LANG['paid']."'
                      WHEN status = 0 THEN '".$LANG['not_paid']."'
                      END) AS status_wording
			FROM 
				".TB_PREFIX."expense e
                LEFT OUTER JOIN ".TB_PREFIX."expense_account ea  
                    ON (e.expense_account_id = ea.id)
                LEFT OUTER JOIN ".TB_PREFIX."biller b  
                    ON (e.biller_id = b.id AND e.domain_id = b.domain_id)
                LEFT OUTER JOIN ".TB_PREFIX."customers c  
                    ON (e.customer_id = c.id AND e.domain_id = c.domain_id)
                LEFT OUTER JOIN ".TB_PREFIX."products p  
                    ON (e.product_id = p.id AND e.domain_id = p.domain_id)
                LEFT OUTER JOIN ".TB_PREFIX."invoices i  
                    ON (e.invoice_id = i.id AND e.domain_id = i.domain_id)
				WHERE
                    e.domain_id = :domain_id
					$where
				ORDER BY 
					$sort $dir 
				$limit";
	
	if ($query!="") {
		$result = dbQuery($sql, ':domain_id', $domain_id, ':qtype', $_REQUEST['qtype'], ':query', $_REQUEST['query']);
	} else {
		$result = dbQuery($sql, ':domain_id', $domain_id);
	}

	return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();




//echo sql2xml($customers, $count);
$xml .= "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($customers as $row) {
    
    $status_wording = $row['status']==1?$LANG['paid']:$LANG['not_paid'];

	$xml .= "<row id='".$row['iso']."'>";
	$xml .= "<cell><![CDATA[
			<a class='index_table' title='$LANG[view] ".$row['description']."' href='index.php?module=expense&view=details&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
			<a class='index_table' title='$LANG[edit] ".$row['description']."' href='index.php?module=expense&view=details&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";		
	
	$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";		
	$xml .= "<cell><![CDATA[".siLocal::number_trim($row['amount'])."]]></cell>";
	$xml .= "<cell><![CDATA[".siLocal::number_trim($row['tax'])."]]></cell>";
	$xml .= "<cell><![CDATA[".siLocal::number_trim($row['amount'] + $row['tax'])."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['expense_account']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['biller']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['customer']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['invoice']."]]></cell>";
	$xml .= "<cell><![CDATA[".$status_wording."]]></cell>";
	$xml .= "</row>";		
}

$xml .= "</rows>";

echo $xml;

?> 
