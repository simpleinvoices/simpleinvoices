<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$having = (isset($_GET['having'])) ? $_GET['having'] : "" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
$invoice = new invoice();
$invoice->sort=$sort;
$invoice->query=$_REQUEST['query'];
$invoice->qtype=$_REQUEST['qtype'];
$invoice->sort=$sort;
$sth = $invoice->select_all('', $dir, $rp, $page, $having);
$sth_count_rows = $invoice->select_all('count',$dir, $rp, $page, $having);

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
		$xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['biller']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['customer']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number_trim($row['invoice_total'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number_trim($row['owing'])."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['aging']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['preference']."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?> 
