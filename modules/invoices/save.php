<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



# Deal with op and add some basic sanity checking

$action = !empty( $_POST['action'] ) ? addslashes( $_POST['action'] ) : NULL;

$tax = getTaxRate($_POST['tax_id']);

	#calcultate the invoice total - start
	$actual_tax = $tax['tax_percentage'] / 100;
	$total_invoice_total_tax = $_POST['gross_total'] * $actual_tax ;
	$total_invoice_total = $total_invoice_total_tax + $_POST['gross_total'] ;
	#calcultate the invoice total - end
	
#insert invoice_total - start
if ( isset( $_POST['style'] ) && $_POST['style'] === 'insert_total' ) {

	if (insertInvoice(1)) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();
	

	
	$sql_items = "INSERT into
				{$tb_prefix}invoice_items
			VALUES
				(
					'NULL',
					$invoice_id,
					'1',
					'00',
					'00',
					'$_POST[tax_id]',
					{$tax['tax_percentage']},
					$total_invoice_total_tax,		
					'$_POST[total]',
					'$_POST[description]',
					$total_invoice_total
				)
			";


	if (mysqlQuery($sql_items)) {
		$display_block_items = $LANG['save_invoice_items_success'];
	}
	else {
		die(mysql_error());
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Total>";

}
#insert invoice_total - end

#EDIT invoice_total
else if ( isset( $_POST['style'] ) && $_POST['style'] === 'edit_total' ) {


	if (updateInvoice($_POST['invoice_id']) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'].$sql;
	}
	
	


	#update the {$tb_prefix}invoice_items table - which tax,description etc.. - start
	$sql = "UPDATE
				{$tb_prefix}invoice_items
			SET
				tax_id = '$_POST[tax_id]',
				tax = '{$tax['tax_percentage']}',
				tax_amount = $total_invoice_total_tax,
				gross_total = '$_POST[total]',
				description = '$_POST[description]',
				total = $total_invoice_total
			WHERE
				invoice_id = $_POST[invoice_id]";


		if (mysqlQuery($sql)) {
			$display_block_items = $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$_POST[invoice_id]&style=Total>";
}

#EDIT invoce total - end


#insert invoice_itemised

else if ( isset( $_POST['style'] ) && $_POST['style'] === 'insert_itemised' ) {


	if (insertInvoice(2)) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();

	$items = 0;	
	
	while ($items < $_POST['max_items']) {

		$product_line_item = $_POST["products$items"];
	
		
		#break out of the while if no QUANTITY
		if (empty($_POST["quantity$items"])) {
			break;
		}
		
		$product = getProduct($_POST["products$items"]);


		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["quantity$items"];
		

		$sql_items = "INSERT INTO {$tb_prefix}invoice_items VALUES ('NULL',$invoice_id,".$_POST["quantity$items"].",{$product['id']},{$product['unit_price']},'$_POST[tax_id]',{$tax['tax_percentage']},$total_invoice_tax_amount,$total_invoice_item_gross,'00',$total_invoice_item_total)";
	

		//echo $sql_items."<br />";
		
		if (mysqlQuery($sql_items)) {
			$display_block_items = $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}
		
		$items++ ;
	}
	


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Itemised>";


}




#EDIT INVOICE ITEMISED - START

else if ( isset( $_POST['style'] ) && $_POST['style'] === 'edit_itemised' ) {

	$invoice_id = $_POST['invoice_id'];

	if (updateInvoice($_POST['invoice_id'])) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}



	$items = 1;
	while ($items < $_POST['max_items']) {


		$product_line_item = $_POST["products$items"];

		if (empty($_POST["quantity$items"])) {
		       break;
		}
		
		
		$product = getProduct($product_line_item);

		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;
		$total_invoice_item_total = $total_invoice_item * $_POST["quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["quantity$items"];
		

		$invoice_id_item = $_POST["id$items"];
		

		$sql_items = "INSERT INTO
					{$tb_prefix}invoice_items
				VALUES
					(
						$invoice_id_item,
						$invoice_id,
						".$_POST["quantity$items"].",
						$product_line_item,
						{$product['unit_price']},
						'$_POST[tax_id]',
						{$tax['tax_percentage']},
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'00',
						$total_invoice_item_total
					)";


		if (mysqlQuery($sql_items)) {
			$display_block_items =  $LANG['save_invoice_items_success'];
		}
		else {
			die(mysql_error());
		}

		$items++ ;
}


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Itemised>";

}


#EDIT Invoice Itemised - End


#Insert - INVOICE CONSULTING


else if ( isset( $_POST['style'] ) && $_POST['style'] === 'insert_consulting' ) {

	if (insertInvoice(3)) {
		$display_block =  $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();

	$items = 0;
	
	
	while ($items < $_POST['max_items']) {
	
		#break out of the while if no QUANTITY
		if (empty($_POST["quantity$items"])) {
			/*echo "break"; */
			break;
		}

		$product = getProduct($_POST["product$items"]);

		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["quantity$items"];
		

		$sql_items = "INSERT INTO {$tb_prefix}invoice_items VALUES ('NULL',$invoice_id,".$_POST["quantity$items"].",$product[id],{$product['unit_price']},'$_POST[tax_id]',{$tax['tax_percentage']},$total_invoice_tax_amount,$total_invoice_item_gross,'".$_POST["description$items"]."',$total_invoice_item_total)";


		echo $sql_items."<br />";
		if (mysqlQuery($sql_items)) {
			$display_block_items = $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}
		
		/* echo "$sql_items <br>";  */
		$items++ ;
}

	//exit();
	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Consulting>";


}


#EDIT INVOICE CONSULTING - START

else if ( isset( $_POST['style'] ) && $_POST['style'] === 'edit_consulting' ) {

	if (updateInvoice($_POST['invoice_id'])) {
		$display_block =  $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
}

	#$display_block .= "step 2 - 2";
	$items = 1;
	while ($items < $_POST['max_items']) {
	

		$consulting_item_note = $_POST["note$items"];


		$product_line_item = $_POST["products$items"];
	
		$product = getProduct($product_line_item);
	
		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;
		$total_invoice_item_total = $total_invoice_item * $_POST["quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["quantity$items"];


		$invoice_id_item = $_POST["id$items"];

		$sql_items = "REPLACE into
					{$tb_prefix}invoice_items
				VALUES
					(
						$invoice_id_item,
						$_POST[invoice_id],
						".$_POST["quantity$items"].",
						$product_line_item,
						{$product['unit_price']},
						'$_POST[tax_id]',
						{$tax['tax_percentage']},
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'".$_POST["note$items"]."',
						$total_invoice_item_total
					)";


		if (mysqlQuery($sql_items)) {
			$display_block_items =  $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}

		/* echo "$sql_items <br>";  */
		$items++ ;
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$_POST[invoice_id]&style=Consulting>";

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
