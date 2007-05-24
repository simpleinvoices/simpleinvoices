<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



# Deal with op and add some basic sanity checking

$action = !empty( $_POST['action'] ) ? addslashes( $_POST['action'] ) : NULL;

$tax = getTaxRate($_POST['tax_id']);
	
#insert invoice_total - start
if ( isset( $_POST['style'] ) && $_POST['style'] === 'insert_total' ) {

	//echo $_POST['biller_id']."TT";
	if (insertInvoice(1)) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();

	$actual_tax = $tax['tax_percentage'] / 100;
	$total_invoice_total_tax = $_POST[total] * $actual_tax ;
	$total_invoice_total = $total_invoice_total_tax + $_POST[total] ;	
		

	
	$sql_items = "INSERT into
				{$tb_prefix}invoice_items
			VALUES
				(
					'',
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

	$invoice_id = $_POST[invoice_id];

	#update the {$tb_prefix}invoices table with customer etc  stuff - start
	
	$sql = "UPDATE
			{$tb_prefix}invoices
		SET
			biller_id = '$_POST[biller_id]',
			customer_id = '$_POST[customer_id]',
			preference_id = '$_POST[preference_id]',
			date = '$_POST[date]',
			custom_field1 = '$_POST[customField1]',
			custom_field2 = '$_POST[customField2]',
			custom_field3 = '$_POST[customField3]',
			custom_field4 = '$_POST[customField4]'
		WHERE
			id = $invoice_id";

	if (mysqlQuery($sql)) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'].$sql;
	}


	#update the {$tb_prefix}invoices table with customer etc  stuff - end
	
	
	#calcultate the invoice total - start
	$actual_tax = $tax['tax_percentage'] / 100;
	$total_invoice_total_tax = $_POST[gross_total] * $actual_tax ;
	$total_invoice_total = $total_invoice_total_tax + $_POST[gross_total] ;
	#calcultate the invoice total - end

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
				invoice_id = $invoice_id";

	echo $sql;

		if (mysqlQuery($sql)) {
			$display_block_items = $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Total>";
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

	$num = $_POST['max_items'];
	$items = 0;
	$num = 5;
	
	
	while ($items < $num) {
		echo "t ";
		$qty = $_POST["quantity$items"];
		$product_line_item = $_POST["select_products$items"];
	
		
		#break out of the while if no QUANTITY
		if (empty($_POST["quantity$items"])) {
			break;
		}
		
		$product = getProduct($_POST["select_products$items"]);
		print_r($product);


		$actual_tax = $tax['tax_percentage']  / 100 ;
		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["quantity$items"];
		

		$sql_items = "INSERT INTO {$tb_prefix}invoice_items VALUES ('',$invoice_id,$qty,{$product['id']},{$product['unit_price']},'$_POST[tax_id]',{$tax['tax_percentage']},$total_invoice_tax_amount,$total_invoice_item_gross,'00',$total_invoice_item_total)";
	

		echo $sql_items."<br />";
		
		if (mysqlQuery($sql_items)) {
			$display_block_items = $LANG['save_invoice_items_success'];
		} else { die(mysql_error());
		}
		
		$items++ ;
	}
	
	//exit();


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Itemised>";


}




#EDIT INVOICE ITEMISED - START

else if ( isset( $_POST['style'] ) && $_POST['style'] === 'edit_itemised' ) {

	$invoice_id = $_POST[invoice_id];

	if (updateInvoice($_POST['invoice_id'])) {
		$display_block = $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
	}


	#$display_block .= "step 2 - 2";
	$num = $_POST[max_items];
	$items = 1;
	$product_id_items = 1;	
	while ($items < $num) {

		$display_block_qty =$_POST["i_quantity$items"];
		#$display_block .= "step 2 - 3  - qty $display_block_qty!! ";
		$qty = $_POST["i_quantity$items"];
		$product_line_item = $_POST["select_products$product_id_items"];
	
		#$display_block .= "step 2 - 4 : qty $qty :: PLI=$product_line_item MAX-- $_POST[max_items];";
		#break out of the while if no QUANTITY
		
		if (empty($_POST["i_quantity$items"])) {
		       /*echo "continue"; */
		       break;
		}
		
	#$display_block .= "step 2 - 5";
		
		$product = getProduct($product_line_item);


		$actual_tax = $tax['tax_percentage']  / 100 ;
		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["i_quantity$items"];
		

		$invoice_id_item = $_POST["id$items"];
		

		$sql_items = "INSERT INTO
					{$tb_prefix}invoice_items
				VALUES
					(
						$invoice_id_item,
						$invoice_id,
						$qty,
						$product_line_item,
						{$product['unit_price']},
						'$_POST[select_tax]',
						{$tax['tax_percentage']},
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'00',
						$total_invoice_item_total
					)";


		if (mysqlQuery($sql_items)) {
			$display_block_items =  $LANG['save_invoice_items_success'];
		} else { die(mysql_error());
		}

		/* echo "$sql_items <br>";  */
		$items++ ;
		$product_id_items++;
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

	$num = $_POST['max_items'];
	$items = 0;
	
	echo "NU".$num;
	while ($items < $num) {

			
	       /* echo "<b>$items</b><br>"; */
		$qty = $_POST["quantity$items"];
		$product_line_item = $_POST["product$items"];
		$line_item_description = $_POST["description$items"];
	       /* echo "Qty: $qty<br> "; */
	       /*  echo "Prod ID: $product_line_item<br> "; */
	
		#break out of the while if no QUANTITY
		if (empty($_POST["quantity$items"])) {
			/*echo "break"; */
			break;
		}

		$product = getProduct($product_line_item);
		

		$actual_tax = $tax['tax_percentage']  / 100 ;
		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["quantity$items"];
		

		$sql_items = "INSERT into {$tb_prefix}invoice_items VALUES ('',$invoice_id,$qty,$product[id],{$product['unit_price']},'$_POST[tax_id]',{$tax['tax_percentage']},$total_invoice_tax_amount,$total_invoice_item_gross,'$line_item_description',$total_invoice_item_total)";


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

	$invoice_id = $_POST[invoice_id];

	#update the {$tb_prefix}invoices table with customer etc  stuff - start
	$sql = "UPDATE
			{$tb_prefix}invoices
		SET
			biller_id = '$_POST[biller_id]',
			customer_id = '$_POST[customer_id]',
			preference_id = '$_POST[select_preferences]',
			date = '$_POST[date]',
			note = '$_POST[note]',
			custom_field1 = '$_POST[i_custom_field1]',
			custom_field2 = '$_POST[i_custom_field2]',
			custom_field3 = '$_POST[i_custom_field3]',
			custom_field4 = '$_POST[i_custom_field4]'
		WHERE
			id = $invoice_id";

	if (mysqlQuery($sql)) {
		$display_block =  $LANG['save_invoice_success'];
	} else {
		$display_block = $LANG['save_invoice_failure'];
}

	#$display_block .= "step 2 - 2";
	$num = $_POST[max_items];
	$items = 1;
	$product_id_items = 1;
	while ($items < $num) {
	

	$consulting_item_note = $_POST["consulting_item_note$items"];
	$display_block_qty =$_POST["i_quantity$items"];


		$qty = $_POST["i_quantity$items"];
		$product_line_item = $_POST["select_products$product_id_items"];


		#$display_block .= "step 2 - 4 : qty $qty :: PLI=$product_line_item MAX-- $_POST[max_items];";
		#break out of the while if no QUANTITY
		if (empty($_POST["i_quantity$items"])) {
			/*echo "break"; */
		       /* break;*/
		}

	
		$product = getProduct($product_line_item);
		
	
		$actual_tax = $tax['tax_percentage']  / 100 ;
		$total_invoice_item_tax = $product['unit_price'] * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $product['unit_price'] ;
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $product['unit_price']  * $_POST["i_quantity$items"];


		$invoice_id_item = $_POST["id$items"];

		$sql_items = "REPLACE into
					{$tb_prefix}invoice_items
				VALUES
					(
						$invoice_id_item,
						$invoice_id,
						$qty,
						$product_line_item,
						{$product['unit_price']},
						'$_POST[select_tax]',
						{$tax['tax_percentage']},
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'$consulting_item_note',
						$total_invoice_item_total
					)";


		if (mysqlQuery($sql_items)) {
			$display_block_items =  $LANG['save_invoice_items_success'];
		} else {
			die(mysql_error());
		}

		/* echo "$sql_items <br>";  */
		$items++ ;
		$product_id_items++;
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=invoices&view=quick_view&submit=$invoice_id&style=Consulting>";

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
