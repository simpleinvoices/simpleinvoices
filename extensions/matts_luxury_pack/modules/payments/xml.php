<?php
// /simple/extensions/payment_rows_per_page/modules/payments
header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "ap.id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
$xml = "";

$sth = paymentssql('', $dir, $sort, $rp, $page);
$sth_count_rows = paymentssql('count',$dir, $sort, $rp, $page);
$payments = $sth->fetchAll(PDO::FETCH_ASSOC);
//$count = npayment();
//$count = $count[0];
$count = $sth_count_rows->rowCount();

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($payments as $row) {
		
		$notes = si_truncate($row['ac_notes'],'13','...');
		$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[
	<a class='index_table' title='$LANG[view] ".(isset($row['name']) ? $row['name'] : '')."' href='index.php?module=payments&view=details&id=$row[id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	<a class='index_table' title='$LANG[print_preview_tooltip] ".$row['id']."' href='index.php?module=payments&view=print&id=$row[id]'><img src='images/common/printer.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
	]]></cell>";
		$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['cname']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['bname']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['ac_amount'])."]]></cell>";
		$xml .= "<cell><![CDATA[".$notes."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
	
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?>
