<?php
// extensions/customer_add_tabbed/modules/customers/xml.php
header("Content-type: text/xml");

//global $auth_session;
//global $dbh;

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "name" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;

$xml ="";

$sth = customersql('', $start, $dir, $sort, $rp, $page);
$customers = $sth->fetchAll(PDO::FETCH_ASSOC);

$sth_count_rows = customersql('count', $start, $dir, $sort, $rp, $page);
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
		$xml .= "<cell><![CDATA[".$row['CID']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
/**/
		$xml .= "<cell><![CDATA[".$row['street_address']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['attention']."]]></cell>";
/**/
		$xml .= "<cell><![CDATA[".siLocal::number($row['customer_total'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['paid'])."]]></cell>";
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
