<?php
global $LANG;

header("Content-type: text/xml");

$start     = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
$dir       = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort      = (isset($_POST['sortname']))  ? $_POST['sortname']  : "name" ;
$rp        = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
$page      = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
$pdo_error = (isset($_POST['pdo_error'])) ? $_POST['pdo_error'] : "";

$billers = Biller::sql('', $start,  $dir, $sort, $rp, $page);
$count = Biller::sql('count',$start, $dir, $sort, $rp, $page);

$xml  = "";
$xml .= $pdo_error;
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($billers as $row) {
    $xml .= "<row id='".$row['id']."'>";
    $xml .= "<cell><![CDATA[" .
	        "<a class='index_table' title='$LANG[view] " . $row['name'] . "'" .
	        "   href='index.php?module=billers&view=details&id=$row[id]&action=view'>" .
            "  <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />" .
            "</a>" .
	        "<a class='index_table' title='$LANG[edit] " . $row['name'] . "'" .
	        "   href='index.php?module=billers&view=details&id=$row[id]&action=edit'>" .
	        "  <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />" .
	        "</a>" .
	        "]]></cell>";
    $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['email']."]]></cell>";
    if ($row['enabled']==$LANG['enabled']) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='" . $row['enabled'] . "' title='" .
                         $row['enabled'] . "' />]]></cell>";
    } else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='" . $row['enabled'] . "' title='" .
                         $row['enabled'] . "' />]]></cell>";
    }
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
