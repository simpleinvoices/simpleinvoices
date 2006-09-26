<?php
#table
include('./config/config.php'); 
include("./lang/$language.inc.php");

#get the invoice id
$master_invoice_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#master invoice id select
$print_master_invoice_id = 'SELECT * FROM si_invoices WHERE inv_id = ' . $master_invoice_id;
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
                $inv_idField = $Array_master_invoice['inv_id'];
                $inv_biller_idField = $Array_master_invoice['inv_biller_id'];
                $inv_customer_idField = $Array_master_invoice['inv_customer_id'];
                $inv_typeField = $Array_master_invoice['inv_type'];
                $inv_preferenceField = $Array_master_invoice['inv_preference'];
		$inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );
                $inv_noteField = $Array_master_invoice['inv_note'];


};


        #get all the details from the invoice_items table for this invoice
        #items invoice id select
        $print_master_invoice_items = "SELECT * FROM si_invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
        $result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


        while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_tax_idField = $Array_master_invoice_items['inv_it_tax_id'];
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];

        };




/*
		$inv_it_total_tax_amount = $inv_it_gross_totalField / $inv_it_taxField  ;
                $inv_dateField = $Array_master_invoice['inv_date'];
*/


#invoice_type query

        $sql_invoice_type = 'SELECT inv_ty_description FROM si_invoice_type WHERE inv_ty_id = ' . $inv_typeField;

        $result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

        while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
                $inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	};


###CUSTOMER

#customer
$sql_customer = "SELECT * FROM si_customers where c_enabled != 0";
$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());

#default customer name query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $inv_customer_idField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

while ($Array_customer = mysql_fetch_array($result_print_customer)) {
       $c_nameField = $Array_customer['c_name'];
}

#customer selector

if (mysql_num_rows($result_customer) == 0) {
        //no records
        $display_block_customer = "<p><em>Sorry, no biller available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_customer = "
        <select name=\"select_customer\">
        <option selected value=\"$def_customerField\" style=\"font-weight: bold\">$c_nameField</option>";

        while ($recs_customer = mysql_fetch_array($result_customer)) {
                $id_customer = $recs_customer['c_id'];
                $display_name_customer = $recs_customer['c_name'];

                $display_block_customer .= "<option value=\"$id_customer\">
                        $display_name_customer</option>";
        }
}



#######BILLER
#biller query
$sql_biller = "SELECT * FROM si_biller where b_enabled != 0";
$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

#Get the names of the defaults from their id -start
#default biller name query
$sql_biller_default = "SELECT b_name FROM si_biller where b_id = $inv_biller_idField ";
$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
}

#biller selector

if (mysql_num_rows($result_biller) == 0) {
        //no records
        $display_block_biller = "<p><em>Sorry, no biller available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_biller = "
        <select name=\"sel_id\">
        <option selected value=\"$inv_biller_idField\" style=\"font-weight: bold\">$sql_biller_defaultField</option>";

        while ($recs = mysql_fetch_array($result_biller)) {
                $id = $recs['b_id'];
                $display_name_biller = $recs['b_name'];

                $display_block_biller .= "<option value=\"$id\">$display_name_biller</option>";
        }
}


########################

##TAX
#tax query
$sql_tax = "SELECT * FROM si_tax where tax_enabled != 0";
$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

#default tax description query
$print_tax = "SELECT * FROM si_tax WHERE tax_id = $inv_it_tax_idField";
$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

while ($Array_tax = mysql_fetch_array($result_print_tax)) {
       $tax_descriptionField = $Array_tax['tax_description'];
}

#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>Sorry, no tax available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_tax = "
        <select name=\"select_tax\">
        <option selected value=\"$inv_it_tax_idField\" style=\"font-weight: bold\">$tax_descriptionField</option>";

        while ($recs_tax = mysql_fetch_array($result_tax)) {
                $id_tax = $recs_tax['tax_id'];
                $display_name_tax = $recs_tax['tax_description'];

                $display_block_tax .= "<option value=\"$id_tax\">$display_name_tax</option>";
        }
}


####

####Preference selector - start
#invoice preference query
$sql_preferences = "SELECT * FROM si_preferences where pref_enabled != 0";
$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());


#default invoice preference description query
$print_inv_preference = "SELECT * FROM si_preferences WHERE pref_id = $inv_preferenceField";
$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
       $pref_descriptionField = $Array_inv_preference['pref_description'];
}

#invoice_preference selector

if (mysql_num_rows($result_preferences) == 0) {
        //no records
        $display_block_preferences = "<p><em>Sorry, no invoice preferences available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_preferences = "
        <select name=\"select_preferences\">
        <option selected value=\"$inv_preferenceField\" style=\"font-weight: bold\">$pref_descriptionField</option>";

        while ($recs_preferences = mysql_fetch_array($result_preferences)) {
                $id_preferences = $recs_preferences['pref_id'];
                $display_name_preferences = $recs_preferences['pref_description'];

                $display_block_preferences .= "<option value=\"$id_preferences\">$display_name_preferences</option>";
        }
}


####Preference selector - end

#preferences query
$print_preferences = "SELECT * FROM si_preferences where pref_id = $inv_preferenceField ";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

while ($Array_preferences = mysql_fetch_array($result_print_preferences)) {
                $pref_idField = $Array_preferences['pref_id'];
                $pref_descriptionField = $Array_preferences['pref_description'];
                $pref_currency_signField = $Array_preferences['pref_currency_sign'];
                $pref_inv_headingField = $Array_preferences['pref_inv_heading'];
                $pref_inv_wordingField = $Array_preferences['pref_inv_wording'];
                $pref_inv_detail_headingField = $Array_preferences['pref_inv_detail_heading'];
                $pref_inv_detail_lineField = $Array_preferences['pref_inv_detail_line'];
                $pref_inv_payment_methodField = $Array_preferences['pref_inv_payment_method'];
                $pref_inv_payment_line1_nameField = $Array_preferences['pref_inv_payment_line1_name'];
                $pref_inv_payment_line1_valueField = $Array_preferences['pref_inv_payment_line1_value'];
                $pref_inv_payment_line2_nameField = $Array_preferences['pref_inv_payment_line2_name'];
                $pref_inv_payment_line2_valueField = $Array_preferences['pref_inv_payment_line2_value'];

};


#system defaults query
$print_defaults = "SELECT * FROM si_defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array_defaults = mysql_fetch_array($result_print_defaults) ) {
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
                $def_inv_templateField = $Array_defaults['def_inv_template'];
};


$display_block_top =  "


	<table align=center>
	<tr>
		<td colspan=6 align=center><b>$pref_inv_headingField</b></td>
	</tr>
        <tr>
		<td class='details_screen'>$pref_inv_wordingField No.</td><td><input type=text name=\"invoice_id\" value=$inv_idField size=15></td>
	</tr>
	<tr>
		<td class='details_screen'>$pref_inv_wordingField date</td><td colspan=2>$inv_dateField</td>
	</tr>	
	<tr>
		<td class='details_screen'>Biller</td><td>$display_block_biller</td>
	</tr>
	<tr>
		<td class='details_screen'><i>Customer</i></td><td>$display_block_customer</td>
	</tr>	

";

#PRINT DETAILS FOR THE TOTAL STYLE INVOICE

if (  $_GET['invoice_style'] === 'Total' ) {
        #invoice total layout - no quantity

	#get all the details for the total style
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM si_invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];

	};

	#products query
	$print_products = "SELECT * FROM si_products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_unit_priceField = $Array['prod_unit_price'];

	};

	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];

	};
	#all the details have bee got now print them to screen

	$display_block_details =  "
	        <tr>
        	        <td colspan=6><b>Description</b></td>
	        </tr>
	        <tr>
			<td colspan=6 ><textarea input type=text name=\"i_description\" rows=10 cols=100 WRAP=hard>$inv_it_descriptionField</textarea></td>
        	</tr>
	        <tr>
        	        <td colspan=6><br></td>
	        </tr>
	        <tr>       	         
			<td class='details_screen'>Gross Total</td><td><input type=text name='inv_it_gross_total' value='$inv_it_gross_totalField' size=10> </td>
		</tr>
		<tr>
		<tr>
			 <td class='details_screen'>Tax</td><td>$display_block_tax</td>
	        </tr>
			 <td class='details_screen'>Preference</td><td>$display_block_preferences/td>
	        </tr>
	";	
   

     }

#INVOICE ITEMEISED and CONSULTING SECTION

else if ( $_GET['invoice_style'] === 'Itemised' || $_GET['invoice_style'] === 'Consulting' ) {

	$display_block_details =  "
        <tr>
                <td colspan=6><br><br></td>
        </tr>
	";
	
	#show column heading for itemised style
        if ( $_GET['invoice_style'] === 'Itemised' ) {
		$display_block_details .=  "      
		<tr>
		<td colspan=6>
		<table>
		<tr>
        	        <td><b>Qty</b></td><td><b>Description</b></td><td><b>Unit Price</b><td><b>Gross Total</b></td><td><b>Tax</b></td><td><b>TOTAL</b></td>
	        </tr>";
	}
	#show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {
                $display_block_details .=  "
		<tr>
		<td colspan=6>
		<table>
                <tr>
                        <td><b>Qty</b></td><td><b>Item</b></td><td><b>Description</b></td><td><b>Unit Price</b><td><b>Gross Total</b></td><td><b>Tax</b></td><td><b>TOTAL</b></td>
                </tr>";
        }




	#INVOIVE_ITEMS SECTION
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM si_invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
	/*
	};
	*/

	#products query
	$print_products = "SELECT * FROM si_products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $Array['prod_id'];
                $prod_descriptionField = $Array['prod_description'];
                $prod_unit_priceField = $Array['prod_unit_price'];
	/*
	};
	*/

	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];

	#invoice total tax
	$print_invoice_total_tax ="select sum(inv_it_tax_amount) as total_tax from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_tax = mysql_query($print_invoice_total_tax, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_invoice_total_tax)) {
                $invoice_total_taxField = $Array_tax['total_tax'];


	/*
	};
	*/	


	#calculation for each line item
	$gross_total_itemised = $prod_unit_priceField * $inv_it_quantityField ;
	/*
	$tax_per_item =  $prod_unit_priceField / $inv_it_taxField;
	$total_tax_per_line = $tax_per_item  * $inv_it_quantityField ;
	$total_per_line = $gross_total_itemised + $total_tax_per_line ;
	*/

	#calculation for the Invoice Total

	#MERGE ITEMISED AND CONSULTING HERE
	#PRINT the line items
	#show the itemised invoice
	if ( $_GET['invoice_style'] === 'Itemised' ) {

		$display_block_details .=  "
	        <tr>
	                <td>$inv_it_quantityField</td><td>$prod_descriptionField</td><td>$pref_currency_signField$inv_it_unit_priceField</td><td>$pref_currency_signField$inv_it_gross_totalField</td><td>$pref_currency_signField$inv_it_tax_amountField</td><td>$pref_currency_signField$inv_it_totalField</td>
	        </tr>
		";
	}	
	#show the consulting invoice 
	else if ( $_GET['invoice_style'] === 'Consulting' ) {
		
	        #item description - only show first 20 characters and add ... to signify theres more text
	        $max_length = 20;
	        if (strlen($inv_it_descriptionField) > $max_length ) {
	                $stripped_item_description = substr($inv_it_descriptionField,0,20);
	                $stripped_item_description .= "...";
	        }
	        else if (strlen($inv_it_descriptionField) <= $max_length ) {
	                 $stripped_item_description = $inv_it_descriptionField;
	        }

	        $display_block_details .=  "
        	<tr>
	                <td>$inv_it_quantityField</td><td>$prod_descriptionField</td><td>$stripped_item_description</td><td>$pref_currency_signField$inv_it_unit_priceField</td><td>$pref_currency_signField$inv_it_gross_totalField</td><td>$pref_currency_signField$inv_it_tax_amountField</td><td>$pref_currency_signField$inv_it_totalField</td>
        	</tr>
		";
	}




	};
	};
	};
	};

	#if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' && !empty($inv_noteField)) {
                #item description - only show first 20 characters and add ... to signify theres more text
                $max_length = 20;
                if (strlen($inv_noteField) > $max_length ) {
                        $stripped_itemised_note = substr($inv_noteField,0,20);
                        $stripped_itemised_note .= "...";
                }
                else if (strlen($inv_noteField) <= $max_length ) {
                         $stripped_itemised_note = $inv_noteField;
                }


		$display_block_details .=  "
			</table>
			</td></tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td><i>Note:</i></td>
			</tr>
			<tr>
				<td>$stripped_itemised_note</td>
			</tr>
		";
	}
	
	
	#END - if itemised style show the invoice note field

	$display_block_details .=  "
	<!--
        <tr>
                <td colspan=3 align=left>Totals</td><td>$pref_currency_signField$invoice_total_taxField</td><td><u>$pref_currency_signField$invoice_total_taxField</u></td><td><u>$pref_currency_signField$invoice_total_totalField</u></td>

        </tr>
	-->
	<tr>
		<td colspan=6><br></td>
	</tr>	

        <tr>
                <td colspan=3></td><td align=left colspan=2>Total tax included</td><td colspan=2 align=right>$pref_currency_signField$invoice_total_taxField</td>
        </tr>
	<tr><td><br></td>
	</tr>
        <tr>
                <td colspan=3></td><td align=left colspan=2><b>$pref_inv_wordingField Amount</b></td><td colspan=2 align=right><u>$pref_currency_signField$invoice_total_totalField</u></td>
        </tr>

	";
}
#END INVOICE ITEMISED/CONSULTING SECTION



$display_block_bottom =  "
        
        </table>
	<!-- addition close table tag to close invoice itemised/consulting if it has a note -->
	</table>

";


?>
<html>
<head>
<?php include('./include/menu.php'); ?>
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/greybox.js"></script>
    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css" media="all"/>
    <script type="text/javascript">
      var GB_ANIMATION = true;
      $(document).ready(function(){
        $("a.greybox").click(function(){
          var t = this.title || $(this).text() || this.href;
           GB_show(t,this.href,470,600);
          return false;
        });
      });
    </script>


<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#subheader");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
mode : "textareas",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
});
</script>


<title>Simple Invoices
</title>
<?php include('./config/config.php'); ?> 
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<br>
<div id="container">
<div id="header">
You are editing <?php echo $pref_inv_wordingField; ?> <?php echo $master_invoice_id; ?>
<br>

</div id="header">

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST>

<?php echo $display_block_top; ?>
<?php echo $display_block_details; ?>
<?php echo $display_block_bottom; ?>

<div id="footer">
<p><input type=submit name="submit" value="Save"><input type=hidden name="invoice_style" value="edit_invoice_total"></p>

</div>
</div>
</form>
</body>
</html>



