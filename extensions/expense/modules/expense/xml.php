<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $LANG;
	global $auth_session;
	
	//SC: Safety checking values that will be directly subbed in
	if (intval($start) != $start) {
		$start = 0;
	}
	$start = (($page-1) * $limit);
	
	if (intval($limit) != $limit) {
		$limit = 25;
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

    $req = array_merge($_GET, $_POST);
	
	$query = $_REQUEST['query'];
    $qtype = $_REQUEST['qtype'];
	
	$where = "";
	if ($query!="") $where = " AND $qtype LIKE '%$query%' ";
	
	/*Check that the sort field is OK*/
	$validFields = array('id', 'status', 'amount', 'expense_account_id','biller_id', 'customer_id', 'invoice_id','date','amount','note');
	
	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "id";
	}
	
                    #coalesce(sum(et.tax_amount),0) as ettax
                    #LEFT OUTER JOIN ".TB_PREFIX."expense_item_tax et  
                    #    ON (et.expense_id = e.id)
		$sql = "SELECT
                    e.id as EID,
                    e.status as status,
                    e.*,
                    i.id as invoice,
                    b.name as biller,
                    ea.name as expense_account,
                    c.name as customer,
                    p.description as product,
                    (select sum(tax_amount) from si_expense_item_tax where expense_id = EID) as tax,
                    (select tax + e.amount) as total,
                    (CASE WHEN status = 1 THEN '".$LANG['paid']."'
                          WHEN status = 0 THEN '".$LANG['not_paid']."'
                    END) AS status_wording

				FROM 
					".TB_PREFIX."expense e
                    LEFT OUTER JOIN ".TB_PREFIX."expense_account ea  
                        ON (e.expense_account_id = ea.id)
                    LEFT OUTER JOIN ".TB_PREFIX."biller b  
                        ON (e.biller_id = b.id)
                    LEFT OUTER JOIN ".TB_PREFIX."customers c  
                        ON (e.customer_id = c.id)
                    LEFT OUTER JOIN ".TB_PREFIX."products p  
                        ON (e.product_id = p.id)
                    LEFT OUTER JOIN ".TB_PREFIX."invoices i  
                        ON (e.invoice_id = i.id)
				WHERE
                    e.domain_id = :domain_id
					$where
				ORDER BY 
					$sort $dir 
				$limit";
	
	
	$result = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

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
