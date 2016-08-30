<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/invoices/xml.php
 * 	invoice grid XML
 *
 * Authors:
 *	yumatechnical@gmail.com
 *
 * Last edited:
 * 	2016-08-30
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
$having = (isset($_GET['having'])) ? $_GET['having'] : "" ;
$page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
$defaults = getSystemDefaults();

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
//$invoice = new invoice();
$invoice = new myinvoice;
$invoice->sort=$sort;

if ($auth_session->role_name =='customer') {
	$invoice->customer = $auth_session->user_id;
} elseif ($auth_session->role_name =='biller') {
	$invoice->biller = $auth_session->user_id;
}

$invoice->query=isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
$invoice->qtype=isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;

$large_dataset = getDefaultLargeDataset();
// @formatter:off
if ($large_dataset == $LANG['enabled']) {
    $sth            = $invoice->select_all('large', $dir, $rp, $page, $having);
    $sth_count_rows = $invoice->count();
    $invoice_count  = $sth_count_rows->fetch(PDO::FETCH_ASSOC);
    $invoice_count  = $invoice_count['count'];
} else {
    $sth            = $invoice->select_all(''     , $dir, $rp, $page, $having);
    $sth_count_rows = $invoice->select_all('count', $dir, $rp, $page, $having);
    $invoice_count  = $sth_count_rows->rowCount();
}
$invoices = $sth->fetchAll(PDO::FETCH_ASSOC);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$invoice_count</total>";
	
foreach ($invoices as $row) {
	$xml .= "<row id='" . $row['id'] . "'>";
//	$xml .= "<cell><![CDATA[" . htmlentities(print_r($row,true)) . "]]></cell>";
	$xml .=
        "<cell><![CDATA[" .
            "<a class=\"index_table\" 
				title=\"" . $LANG['quick_view_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] . "\" 
				href=\"index.php?module=invoices&view=quick_view&id=" . $row['id'] . "\"
			>	<img src=\"images/common/view.png\" class=\"action\" />
            </a>
			<a class=\"index_table\" rel=\"1 DETAILS\" 
				title=\"" . $LANG['edit_view_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] . "\" 
				href=\"index.php?module=invoices&view=details&id=" . $row['id'] . "&action=view\"
			>	<img src=\"images/common/edit.png\" class='action' />
            </a>
            <a class=\"index_table\" rel=\"2 PRINT VIEW\" 
				title=\"" . $LANG['print_preview_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] . "\" 
				href=\"index.php?module=export&view=invoice&id=" . $row['id'] . "&format=print\"
			>	<img src=\"images/common/printer.png\" class=\"action\" />
            </a>
            <a class=\"invoice_export_dialog\" rel=\"3 EXPORT DIALOG\" 
				title=\"" . $LANG['export_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] . "\" 
				href=\"#\" rel=\"" . $row['id'] . "\"
			>	<img src=\"images/common/page_white_acrobat.png\" class=\"action\" />
            </a>"/*.
            "{*3 EXPORT DIALOG  onclick='export_invoice(" . $row['id'] . ", \"" .
			$config->export->spreadsheet . "\", \"" . $config->export->wordprocessor . "\");'> *}
            {*3 EXPORT TO PDF 
			<a class=\"index_table\" rel=\"3 EXPORT TO PDF\" 
				title=\"" . $LANG['export_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] . "\" 
				href=\"pdfmaker.php?id=" . $row['id'] . "\"
			>	<img src='images/common/page_white_acrobat.png' class='action' />
            </a> *}
            {*4 XLS 
			<a class=\"index_table\" rel=\"4 XLS\" 
				title=\"" . $LANG['export_tooltip'] . " " . $row['preference'] . " " . $row['index_id'] . " " . $LANG['export_xls_tooltip'] . $config->export->spreadsheet . " " . $LANG['format_tooltip'] . "\" 
				href=\"index.php?module=invoices&view=templates/template&invoice='" . $row['id'] . "&action=view&location=print&export=" . $config->export->spreadsheet . "\"
			>	<img src=\"images/common/page_white_excel.png\" class=\"action\" />
            </a> *}
        "*/;
		
    // Alternatively: The Owing column can have the link on the amount instead of the payment icon code here
    if ($row['status'] && $row['owing'] > 0) {
        // Real Invoice Has Owing - Process payment
        $xml .= "<a title='" . $LANG['process_payment_for'] . " " . $row['preference'] . " " . $row['index_id'] . "' 
					class='index_table' rel=\"6 Payment\" 
					href='index.php?module=payments&view=process&id=" . $row['id'] . "&op=pay_selected_invoice'
				>	<img src='images/common/money_dollar.png' class='action' />
				</a>";
    } elseif ($row['status']) {
        // Real Invoice Payment Details if not Owing (get different color payment icon)
		$xml .= "<a title='" . $LANG['process_payment_for'] . " " . $row['preference'] . " " . $row['index_id'] . "' 
					class='index_table' rel=\"6 Payment\" 
					href='index.php?module=payments&view=details&id=" . $row['id'] . "&action=view'
				>	<img src='images/common/money_dollar.png' class='action' />
				</a>";
    } else {
        // Draft Invoice Just Image to occupy space till blank or greyed out icon becomes available
        $xml .= "<img src='images/common/money_dollar.png' class='action' />";
    }
	$xml .= "<a title='" . $LANG['email'] . " " . $row['preference'] . " " . $row['index_id'] ."' 
				class='index_table' rel=\"7 Email\" 
				href='index.php?module=invoices&view=email&stage=1&id=" . $row['id'] . "'
			>	<img src='images/common/mail-message-new.png' class='action' />
			</a> ]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['index_name'] 						. "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['biller'] 							. "]]></cell>";
    $xml .= "<cell><![CDATA[ <a class=\"index_table\" 
				title=\"" . $LANG['quick_view_tooltip'] . " " . $row['customer'] . "\" 
				href=\"index.php?module=customers&view=details&action=view&id=" . $row['customer_id'] . "\"
			>" . $row['customer'] . "</a> ]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::date($row['date']) 			. "]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['invoice_total']) 	. "]]></cell>";
	if ($row['status']) {
		$owing = siLocal::number($row['owing']);
/**/
		if ($owing>0.03) {
			$xml .= "<cell class='owing'><![CDATA[". $owing 			."]]></cell>";
			$xml .= "<cell class='aging'><![CDATA[". $row['aging'] 		."]]></cell>";
		} elseif ($owing<-0.03) {
			$xml .= "<cell class='minus_value owing'><![CDATA[". $owing ."]]></cell>";
			$xml .= "<cell class='aging'><![CDATA[". $row['aging'] 		."]]></cell>";
		} else {
			$xml .= "<cell class='owing'><![CDATA[0.00]]></cell>";
			$xml .= "<cell class='aging'><![CDATA[ ]]></cell>";
		}
/**/
	} else {
		$xml .= "<cell class='owing'><![CDATA[&nbsp;]]></cell>";
		$xml .= "<cell class='aging'><![CDATA[&nbsp;]]></cell>";
	}
    $xml .= "<cell><![CDATA[" . $row['preference'] 						. "]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on
echo $xml;
