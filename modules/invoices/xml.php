<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
function sql($type='', $dir, $sort, $rp, $page )
{
	global $config;
	global $auth_session;


	//SC: Safety checking values that will be directly subbed in
/*
	if (intval($start) != $start) {
		$start = 0;
	}
	if (intval($limit) != $limit) {
		$limit = 15;
	}
*/
	/*SQL Limit - start*/
	$start = (($page-1) * $rp);
	$limit = "LIMIT ".$start.", ".$rp;
	/*SQL Limit - end*/

	/*SQL where - start*/
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	$where = " WHERE iv.domain_id = :domain_id ";
	if ($query) $where = " WHERE iv.domain_id = :domain_id AND $qtype LIKE '%$query%' ";
	/*SQL where - end*/

	/*Check that the sort field is OK*/
	$validFields = array('iv.id', 'biller', 'customer', 'invoice_total','owing','date','aging','type');

	if (in_array($sort, $validFields)) {
		$sort = $sort;
	} else {
		$sort = "id";
	}

	if($type =="count")
	{
		//unset($limit);
        $limit="";
	}


	switch ($config->database->adapter)
	{
		case "pdo_pgsql":
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
				 p.pref_description AS Type,
				 p.pref_inv_wording AS invoice_wording
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
			break;
		case "pdo_mysql":
		default:
		   $sql ="
			SELECT  
				   iv.id,
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
			GROUP BY
				iv.id
			ORDER BY
			$sort $dir
			$limit";
			break;
	}
	
	$result =  dbQuery($sql,':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));
	return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count',$dir, $sort, $rp, $page);

$invoices = $sth->fetchAll(PDO::FETCH_ASSOC);

$xml ="";
$count = $sth_count_rows->rowCount();

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($invoices as $row) {
		$xml .= "<row id='".$row['id']."'>";
		$xml .= "<cell>
					<![CDATA[<a class='index_table' title='".$LANG['quick_view_tooltip']." ".$row['preference']." ".$row['id']."' href='index.php?module=invoices&view=quick_view&id=".$row['id']."'> <img src='images/common/view.png' class='action' /></a>
		<a class='index_table' title='".$LANG['edit_view_tooltip']." ".$row['preference']." ".$row['id']."' href='index.php?module=invoices&view=details&id=".$row['id']."&action=view'><img src='images/common/edit.png' class='action' /></a>
		<!--2 Print View -->
			<a class='index_table' title='".$LANG['print_preview_tooltip']." ".$row['preference']." ".$row['id']."' href='index.php?module=export&view=invoice&id=".$row['id']."&format=print'>
				<img src='images/common/printer.png' class='action' /><!-- print -->
			</a>
		<!--3 EXPORT DIALOG -->
			<a title='".$LANG['export_tooltip']." ".$row['preference']." ".$row['id']."' class='invoice_export_dialog' href='#' rel='".$row['id']."'>
				<img src='images/common/page_white_acrobat.png' class='action' />
			</a>

		<!--3 EXPORT DIALOG  onclick='export_invoice(".$row['id'].", \"".$config->export->spreadsheet."\", \"".$config->export->wordprocessor."\");'> -->	
		<!--3 EXPORT TO PDF <a title='".$LANG['export_tooltip']." ".$row['preference']." ".$row['id']."' class='index_table' href='pdfmaker.php?id=".$row['id']."'><img src='images/common/page_white_acrobat.png' class='action' /></a> -->
		<!--4 XLS <a title='".$LANG['export_tooltip']." ".$row['preference']." ".$row['id']." ".$LANG['export_xls_tooltip'].$config->export->spreadsheet." ".$LANG['format_tooltip']."' class='index_table' href='index.php?module=invoices&view=templates/template&invoice='".$row['id']."&action=view&location=print&export=".$config->export->spreadsheet."'><img src='images/common/page_white_excel.png' class='action' /></a> -->
		
		<!--6 Payment --><a title='".$LANG['process_payment_for']." ".$row['preference']." ".$row['id']."' class='index_table' href='index.php?module=payments&view=process&id=".$row['id']."&op=pay_selected_invoice'><img src='images/common/money_dollar.png' class='action' /></a>
		<!--7 Email --><a title='".$LANG['email']." ".$row['preference']." ".$row['id']."' class='index_table' href='index.php?module=invoices&view=email&stage=1&id=".$row['id']."'><img src='images/common/mail-message-new.png' class='action' /></a>
					]]>
				</cell>";
		$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['biller']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['customer']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::date($row['date']))."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number_trim($row['invoice_total']))."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number_trim($row['owing']))."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['aging']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['preference']."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?> 
