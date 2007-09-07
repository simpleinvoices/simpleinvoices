<?php

/*
* Script: save.php
* 	Invoice save file
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* License:
*	 GPL v2 or above
*	 
* Website:
* 	http://www.simpleinvoices.or
*/


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "invoices";

$smarty->assign('pageActive', $pageActive);

# Deal with op and add some basic sanity checking


if(!isset( $_POST['type']) && !isset($_POST['action'])) {
	exit("no save action");
}


#insert invoice_total - start
if ($_POST['type'] == 1 && $_POST['action'] == "insert" ) {
	
	//Get type id - so do add into redirector header
	$typeId = $_POST['type'];

	if (insertInvoice(1)) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	$invoice_id = mysql_insert_id();

	insertProduct($_POST['description'],$_POST['unit_price'],0,0);
	
	$product_id = mysql_insert_id();


	if (insertInvoiceItem($invoice_id,1,$product_id,$_POST['tax_id'],$_POST['description'])) {
		$display_block_items = $LANG['save_invoice_items_success'];
	}
	else {
		die(mysql_error());
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&invoice=$invoice_id&type=$typeId>";
}


#insert invoice_itemised

if ( $_POST['action'] == "insert" && ($_POST['type'] == 2 || $_POST['type'] == 3 )) {

	if (($_POST['type'] == 3 && insertInvoice(3)) || ($_POST['type'] == 2 && insertInvoice(2))) {
	//Get type id - so do add into redirector header

	$typeId = $_POST['type'];
		$display_block =  $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	//get the invoice id from the insert
	$invoice_id = mysql_insert_id();
	
	for($i=0;!empty($_POST["quantity$i"]) && $i < $_POST['max_items']; $i++) {

		if (insertInvoiceItem($invoice_id,$_POST["quantity$i"],$_POST["products$i"],$_POST['tax_id'],$_POST["description$i"]) ) {
			$display_block_items = $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&invoice=$invoice_id&type=$typeId>";
}


if ( $_POST['action'] == "edit" && ($_POST['type'] == 1 || $_POST['type'] == 2 || $_POST['type'] == 3 )) {

	//Get type id - so do add into redirector header
	$typeId = $_POST['type'];

	$invoice_id = $_POST['invoice_id'];
	
	if (updateInvoice($_POST['invoice_id'])) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	if($_POST['type'] == 1) {
		$sql = "UPDATE ".TB_PREFIX."products SET `unit_price` = $_POST[unit_price], `description` = '$_POST[description0]' WHERE id = $_POST[products0]";
		mysqlQuery($sql);
	}

	for($i=0;(!empty($_POST["quantity$i"]) && $i < $_POST['max_items']);$i++) {
		
		if (updateInvoiceItem($_POST["id$i"],$_POST["quantity$i"],$_POST["products$i"],$_POST['tax_id'],$_POST["description$i"])) {
			$display_block_items =  $LANG['save_invoice_items_success'];
		}
		else {
			die(mysql_error());
		}
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&invoice=$invoice_id&type=$typeId>";
}



$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';

echo <<<EOD
{$refresh_total}
<title>{$title}</title>
</head>

<body>

<br>
{$display_block}
<br><br>
{$display_block_items}
EOD;
?>
