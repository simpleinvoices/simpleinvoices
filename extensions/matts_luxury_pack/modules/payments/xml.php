<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/payments/xml.php
 * 	payment details XML
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
global $LANG;

// @formatter:off
$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "ap.id" ;
$rp   = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;
// @formatter:on

$payments = paymentssql(     '', $dir, $sort, $rp, $page);
$count    = paymentssql('count', $dir, $sort, $rp, $page);

// @formatter:off
$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payments as $row) {
    $notes = si_truncate($row['notes'],'13','...');
    $xml .= "<row id='$row[id]'>";
    $xml .= 
        "<cell><![CDATA[
           <a class='index_table' title='$LANG[view] $row[index_name]'
              href='index.php?module=payments&view=details&id=$row[id]&action=view'>
             <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='$LANG[print_preview_tooltip] $row[id]'
              href='index.php?module=payments&view=print&id=$row[id]'>
             <img src='images/common/printer.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
         ]]></cell>";
    $xml .= "<cell><![CDATA[$row[id]]]></cell>";
    $xml .= "<cell><![CDATA[$row[index_name]]]></cell>";
    $xml .= "<cell><![CDATA[$row[cname]]]></cell>";
    $xml .= "<cell><![CDATA[$row[bname]]]></cell>";
    $xml .= "<cell><![CDATA[".siLocal::number($row['ac_amount'])."]]></cell>";
    $xml .= "<cell><![CDATA[$notes]]></cell>";
    $xml .= "<cell><![CDATA[$row[description]]]></cell>";
    $xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
