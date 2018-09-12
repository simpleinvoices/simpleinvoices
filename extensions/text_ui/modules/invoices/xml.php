<?php
header("Content-type: text/xml");

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "id";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_GET['page'])      ) ? $_GET['page']       : "1";
$query = (isset($_POST['query'])    ) ? $_POST['query']     : null;
$qtype = (isset($_POST['qtype'])    ) ? $_POST['qtype']     : null;

$invoices = Invoice::select_all("", $sort, $dir, $rp, $page, $qtype, $query);
$count    = Invoice::count();

$xml  = "";
$xml .= "<rows>";
$xml .= "  <page>$page</page>";
$xml .= "  <total>$count</total>";

foreach ($invoices as $row) {
    $xml .= "  <row id='$row[id]'>";
    $xml .= "    <action>
                   <![CDATA[<a href='index.php?module=invoices&view=quick_view&invoice=$row[id]'>" . utf8_encode($row['id']) . "</a>]]>
                 </action>";
    $xml .= "    <customer><![CDATA[" . utf8_encode($row['customer']) . "]]></customer>";
    $xml .= "    <date><![CDATA[" . utf8_encode($row['date']) . "]]></date>";
    $xml .= "    <invoice_total><![CDATA[" . utf8_encode(siLocal::number_trim($row['invoice_total'])) . "]]></invoice_total>";
    $xml .= "  </row>";
}
$xml .= "</rows>";
echo $xml;
