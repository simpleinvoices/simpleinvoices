<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



# Deal with op and add some basic sanity checking

$action = !empty( $_POST['action'] ) ? addslashes( $_POST['action'] ) : NULL;

if(!isset( $_POST['style'])) {
	exit("no save action");
}


#insert invoice_total - start
if ( $_POST['style'] === 'insert_total' ) {

	if (insertInvoice(1)) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	$invoice_id = mysql_insert_id();

	insertProduct($_POST['description'],$_POST['unit_price'],,0,0,,,,);
	
	$product_id = mysql_insert_id();


	if (insertInvoiceItem($invoice_id,1,$product_id,$_POST['tax_id'],$_POST['description'])) {
		$display_block_items = $LANG['save_invoice_items_success'];
	}
	else {
		die(mysql_error());
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Total>";
}


#insert invoice_itemised

if ( $_POST['style'] === 'insert_itemised' || $_POST['style'] === 'insert_consulting' ) {

	if ($_POST['style'] === 'insert_consulting' && insertInvoice(3)) {
		$display_block =  $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}
	
	
	if ($_POST['style'] === 'insert_itemised' && insertInvoice(2)) {
		$display_block = $LANG['save_invoice_success'];
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

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Itemised>";
}


if ( $_POST['style'] === 'edit_consulting' || $_POST['style'] === 'edit_itemised' || $_POST['style'] === 'edit_total' ) {

	$invoice_id = $_POST['invoice_id'];

	
	if (updateInvoice($_POST['invoice_id'])) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	if($_POST['style'] === 'edit_total') {
		$sql = "UPDATE {$tb_prefix}products SET `unit_price` = $_POST[unit_price], `description` = '$_POST[description0]' WHERE id = $_POST[products0]";
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

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Itemised>";
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
