<?php
#table
#define("BROWSE","browse");
include('./include/include_print.php');
/*include("./include/functions.php");*/

#get the invoice id
$master_invoice_id = $_GET['submit'];

#Info from DB print
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


#master invoice id select
$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE id =$master_invoice_id";
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

$invoice = mysql_fetch_array($result_print_master_invoice_id);
$invoice['date'] = date( $config['date_format'], strtotime( $invoice['date'] ));


#invoice_type query

$sql_invoice_type = "SELECT inv_ty_description FROM {$tb_prefix}invoice_type WHERE inv_ty_id = $invoice[type_id] ";
$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());


#Seems to be unused...
/*$invoice_type =  mysql_fetch_array($result_invoice_type);
		while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
				$inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	};*/

#customer query
/*$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE id = $invoice[customer_id]";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

#biller query
$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE id = $invoice[biller_id]";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());

$customer = mysql_fetch_array($result_print_customer);
$biller = mysql_fetch_array($result_print_biller);*/

$customer = getCustomer($invoice['customer_id']);
$biller = getBiller($invoice['biller_id']);



#preferences query
$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $invoice[preference] ";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

$pref = mysql_fetch_array($result_print_preferences);


$defaults = getSystemDefaults();

#Accounts - for the invoice - start
#invoice total calc - start
$invoice['total'] = calc_invoice_total($invoice['id']);
$invoice['total_format'] = number_format($invoice['total'],2);
#invoice total calc - end


#amount paid calc - start
$invoice['paid'] = calc_invoice_paid($invoice['id']);
$invoice['paid_format'] = number_format($invoice['paid'],2);
#amount paid calc - end

#amount owing calc - start
$invoice['owing'] = number_format($invoice['total'] - $invoice['paid'],2);
#amount owing calc - end
#Accounts - for the invoice - end


for($i=1;$i<=4;$i++) {
	$biller["custom_field_label$i"] = get_custom_field_label("biller_cf$i");
	$customer["custom_field_label$i"] = get_custom_field_label("customer_cf$i");
	$product_cf["custom_field_label$i"] = get_custom_field_label("product_cf$i");
	$show["custom_field$i"] = show_custom_field("invoice_cf$i",$invoice["invoice_custom_field$i"],"read",'','tbl1-left','tbl1-right',3,':');
}

/*Set the template to the default*/
$template = $defaults['template'];
if (isset($_GET['export'])) {
	$template = "export";
}

#logo field support - if not logo show nothing else show logo

if(!empty($biller['logo'])) {
	$logo = "./images/logo/$biller[logo]";
}
else {
	$logo = "./images/logo/_default_blank_logo.png";
}
#end logo section





#PRINT DETAILS FOR THE TOTAL STYLE INVOICE

	#get all the details for the total style
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	#invoice_total Style
	$master_invoice = mysql_fetch_array($result_print_master_invoice_items);
	$master_invoice['inv_it_tax_amount'] = number_format($master_invoice['inv_it_tax_amount'],2);
	$master_invoice['inv_it_gross_total'] = number_format($master_invoice['inv_it_gross_total'],2);
	$master_invoice['inv_it_total'] = number_format($master_invoice['inv_it_total'],2);
	#invoice_total Style

	#all the details have bee got now print them to screen
	
	
	#invoice total tax
	$print_invoice_total_tax ="SELECT SUM(inv_it_tax_amount) AS total_tax FROM {$tb_prefix}invoice_items WHERE inv_it_invoice_id =$master_invoice_id";
	$result_print_invoice_total_tax = mysql_query($print_invoice_total_tax, $conn) or die(mysql_error());
	$tax = mysql_fetch_array($result_print_invoice_total_tax);
	$tax['total_tax'] = number_format($tax['total_tax'],2);	

				
	#invoice_total total query
	$print_invoice_total_total ="SELECT SUM(inv_it_total) AS total FROM {$tb_prefix}invoice_items WHERE inv_it_invoice_id =$master_invoice_id";
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());

	$invoice_total = mysql_fetch_array($result_print_invoice_total_total);
	$invoice_total['total'] = number_format($invoice_total['total'],2);


/* The Export code - supports any file extensions - excel/word/open office - what reads html */
if (isset($_GET['export'])) {
	$file_extension = $_GET['export'];
	header("Content-type: application/octet-stream");
	/*header("Content-type: application/x-msdownload");*/
	header("Content-Disposition: attachment; filename=$pref[pref_inv_heading]$invoice[id].$file_extension");
	header("Pragma: no-cache");
	header("Expires: 0");
}
/* End Export code */


	//include("./templates/invoices/${template}/${template}.tpl");



	#if itemised style show the invoice note field - START
	if(!( $_GET['invoice_style'] === 'Itemised' && !empty($invoice['note']) OR 'Consulting' && !empty($invoice['note']))) {
		$notes = "";
	}
	#END - if itemised style show the invoice note field
	
	
	$heading = "";
	#show column heading for itemised style
	if ( $_GET['invoice_style'] === 'Itemised' ) {
		$heading = $itemised_heading;
	}
	#show column heading for consulting style
	if ( $_GET['invoice_style'] === 'Consulting' ) {
		$heading = $consulting_heading;
	}
	#show column heading for total style
	if ( $_GET['invoice_style'] === 'Total' ) {
		$heading = $total_heading;
	}
	

	

	#INVOICE ITEMEISED SECTION
	$lines = "";
		
	
	#INVOIVE_ITEMS SECTION
	#items invoice id sgseelect
	$print_master_invoice_items = "SELECT * FROM {$tb_prefix}invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());

	$maste_invoices = null;
	for($i=0;$master_invoice = mysql_fetch_array($result_print_master_invoice_items);$i++) {
	
		$master_invoice['inv_it_quantity_formatted'] = number_format($master_invoice['inv_it_quantity'],2);
		$master_invoice['inv_it_unit_price'] = number_format($master_invoice['inv_it_unit_price'],2);
		$master_invoice['inv_it_tax_amount'] = number_format($master_invoice['inv_it_tax_amount'],2);
		$master_invoice['inv_it_gross_total'] = number_format($master_invoice['inv_it_gross_total'],2);
		$master_invoice['inv_it_total'] = number_format($master_invoice['inv_it_total'],2);
		
		#products query
		$print_products = "SELECT * FROM {$tb_prefix}products WHERE id = $master_invoice[inv_it_product_id]";
		$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());
		
		$product = mysql_fetch_array($result_print_products);

		#END INVOICE ITEMS SECTION
	
	
		#calculation for each line item
		$gross_total_itemised = $product['unit_price'] * $master_invoice['inv_it_quantity'] ;
		
		$master_invoices[$i] = $master_invoice;
	
		#MERGE ITEMISED AND CONSULTING HERE
		#PRINT the line items
		#show the itemised invoice
		//include("./templates/invoices/${template}/${template}.tpl");
		
		if ($_GET['invoice_style'] === 'Itemised' ) {
			$line = addslashes($itemised_line);
			eval('$lines .=  "'.$line.'";');

		}
		#show the consulting invoice
		if ($_GET['invoice_style'] === 'Consulting' ) {
			$line = addslashes($consulting_line);
			eval('$lines .=  "'.$line.'";');
		}
		#show the total invoice
		if ($_GET['invoice_style'] === 'Total' ) {
			$line = addslashes($total_line);
			eval('$lines .=  "'.$line.'";');
	}
}
	
#END INVOICE ITEMEISED/CONSULTING SECTION
	if (is_null($_GET['export'])) {
		
	}
	
	
	$templatePath = "./templates/invoices/${template}/template.tpl";
	$template_path = "../templates/invoices/${template}/";
	$css = "./templates/invoices/${template}/style.css";

	if(file_exists($templatePath)) {
		$smarty -> assign('invoice_total',$invoice_total);
		$smarty -> assign('biller',$biller);
		$smarty -> assign('customer',$customer);
		$smarty -> assign('invoice',$invoice);
		$smarty -> assign('pref',$pref);
		$smarty -> assign('logo',$logo);
		$smarty -> assign('template',$template);
		$smarty -> assign('product_cf',$product_cf);
		$smarty -> assign('product',$product);
		$smarty -> assign('master_invoices',$master_invoices);
		$smarty -> assign('template_path',$template_path);
		$smarty -> assign('css',$css);
		
		
		$smarty -> display(".".$templatePath);
	}
	else {
		echo "Old Template....";
		$temp = file_get_contents("./templates/invoices/${template}/${template}.html");
		$temp = addslashes($temp); $content = "";
	
		eval ('$content = "'.$temp.'";');
		echo $content;
	}	
?>
