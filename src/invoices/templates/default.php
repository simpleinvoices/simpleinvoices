<?php
#table
include('./include/include_print.php');
include("./include/functions.php");


#get the invoice id
$master_invoice_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


#master invoice id select
$print_master_invoice_id = "SELECT * FROM si_invoices WHERE inv_id =$master_invoice_id";
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
                $inv_idField = $Array_master_invoice['inv_id'];
                $inv_biller_idField = $Array_master_invoice['inv_biller_id'];
                $inv_customer_idField = $Array_master_invoice['inv_customer_id'];
                $inv_typeField = $Array_master_invoice['inv_type'];
                $inv_preferenceField = $Array_master_invoice['inv_preference'];
                $inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );
                $inv_noteField = $Array_master_invoice['inv_note'];
                $inv_custom_field1Field = $Array_master_invoice['invoice_custom_field1'];
                $inv_custom_field2Field = $Array_master_invoice['invoice_custom_field2'];
                $inv_custom_field3Field = $Array_master_invoice['invoice_custom_field3'];
                $inv_custom_field4Field = $Array_master_invoice['invoice_custom_field4'];

};

/* 
old date code
                $inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );

stuff to implement - talk to raymond about this

		$inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );
*/

/*
$inv_it_total_tax_amount = $inv_it_gross_totalField / $inv_it_taxField  ;
*/


#invoice_type query

        $sql_invoice_type = "select inv_ty_description from si_invoice_type where inv_ty_id = $inv_typeField ";
        $result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

        while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
                $inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];
	};

#customer query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $inv_customer_idField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

#biller query
$print_biller = "SELECT * FROM si_biller WHERE b_id = $inv_biller_idField";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_print_customer)) {
                $c_idField = $Array['c_id'];
                $c_attentionField = $Array['c_attention'];
                $c_nameField = $Array['c_name'];
                $c_street_addressField = $Array['c_street_address'];
                $c_street_address2Field = $Array['c_street_address2'];
                $c_cityField = $Array['c_city'];
                $c_stateField = $Array['c_state'];
                $c_zip_codeField = $Array['c_zip_code'];
                $c_countryField = $Array['c_country'];
		$c_phoneField = $Array['c_phone'];
                $c_mobile_phoneField = $Array['c_mobile_phone'];
		$c_faxField = $Array['c_fax'];
		$c_emailField = $Array['c_email'];
                $c_custom_field1Field = $Array['c_custom_field1'];
                $c_custom_field2Field = $Array['c_custom_field2'];
                $c_custom_field3Field = $Array['c_custom_field3'];
                $c_custom_field4Field = $Array['c_custom_field4'];

};

while ($billerArray = mysql_fetch_array($result_print_biller)) {
                $b_idField = $billerArray['b_id'];
                $b_nameField = $billerArray['b_name'];
                $b_street_addressField = $billerArray['b_street_address'];
                $b_street_address2Field = $billerArray['b_street_address2'];
                $b_cityField = $billerArray['b_city'];
                $b_stateField = $billerArray['b_state'];
                $b_zip_codeField = $billerArray['b_zip_code'];
                $b_countryField = $billerArray['b_country'];
                $b_phoneField = $billerArray['b_phone'];
                $b_mobile_phoneField = $billerArray['b_mobile_phone'];
                $b_faxField = $billerArray['b_fax'];
                $b_emailField = $billerArray['b_email'];
                $b_co_logoField = $billerArray['b_co_logo'];
                $b_co_footerField = $billerArray['b_co_footer'];
                $b_custom_field1Field = $billerArray['b_custom_field1'];
                $b_custom_field2Field = $billerArray['b_custom_field2'];
                $b_custom_field3Field = $billerArray['b_custom_field3'];
                $b_custom_field4Field = $billerArray['b_custom_field4'];
};


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

#Accounts - for the invoice - start
#invoice total calc - start
	$invoice_total_Field = calc_invoice_total($inv_idField);
	$invoice_total_Field_format = number_format($invoice_total_Field,2);
#invoice total calc - end

#amount paid calc - start
	$invoice_paid_Field = calc_invoice_paid($inv_idField);
	$invoice_paid_Field_format = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
        $invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end
#Accounts - for the invoice - end

#get custom field labels for biller
$biller_custom_field_label1 = get_custom_field_label(biller_cf1);
$biller_custom_field_label2 = get_custom_field_label(biller_cf2);
$biller_custom_field_label3 = get_custom_field_label(biller_cf3);
$biller_custom_field_label4 = get_custom_field_label(biller_cf4);
#get custom field labels for the customer
$customer_custom_field_label1 = get_custom_field_label(customer_cf1);
$customer_custom_field_label2 = get_custom_field_label(customer_cf2);
$customer_custom_field_label3 = get_custom_field_label(customer_cf3);
$customer_custom_field_label4 = get_custom_field_label(customer_cf4);
#product custom fields
$prod_custom_field_label1 = get_custom_field_label(product_cf1);
$prod_custom_field_label2 = get_custom_field_label(product_cf2);
$prod_custom_field_label3 = get_custom_field_label(product_cf3);
$prod_custom_field_label4 = get_custom_field_label(product_cf4);

#get invoice custom fields
$show_custom_field_1 = show_custom_field(invoice_cf1,$inv_custom_field1Field,read,'','tbl1-left','tbl1-right',3,':');
$show_custom_field_2 = show_custom_field(invoice_cf2,$inv_custom_field2Field,read,'','tbl1-left','tbl1-right',3,':');
$show_custom_field_3 = show_custom_field(invoice_cf3,$inv_custom_field3Field,read,'','tbl1-left','tbl1-right',3,':');
$show_custom_field_4 = show_custom_field(invoice_cf4,$inv_custom_field4Field,read,'','tbl1-left','tbl1-right',3,':');


#logo field support - if not logo show nothing else show logo

if (!empty($b_co_logoField)) {
	$logo_block = "
	<table width=100% align=center>
	        <tr>
       		        <td colspan=5><IMG src=./images/logo/$b_co_logoField border=0 hspace=0 align=left></td><th align=right><span class=\"font1\">$pref_inv_headingField</span></th>
	        </tr>
        	<tr>
                	<td colspan=6><hr size=\"1\"></td>
	        </tr>
	</table>
 ";
}
if (empty($b_co_logoField)) {
        $logo_block = "
	<table width=100% align=center>
        	<tr>
               		<td colspan=5><IMG src=./images/logo/_default_blank_logo.png border=0 hspace=0 align=left><th align=right><span class=\"font1\">$pref_inv_headingField</span></th></td>
	        </tr>
       		<tr>
               		<td colspan=6><hr size=\"1\"></hr></td>
	        </tr>
	</table>
 ";
}
#end logo section
	

#Biller and Invoice Summary section - start
$display_block_top =  "
	
	
	$logo_block
	 

	<!-- Summary - start -->

	<table class='right'>
        <tr>
                <td class=\"col1 tbl1\" colspan=4 ><b>$pref_inv_wordingField $LANG_summary</b></td> 
        </tr>
        <tr>
                <td class=\"tbl1-left\">$pref_inv_wordingField $LANG_number_short:</td><td class=\"tbl1-right\" colspan=3>$inv_idField</td>
        </tr>   
        <tr>
                <td nowrap class=\"tbl1-left\">$pref_inv_wordingField $LANG_date:</td><td class=\"tbl1-right\" colspan=3>$inv_dateField</td>
        </tr>   
	<!-- Show the Invoice Custom Fields is valid -->
	$show_custom_field_1 
	$show_custom_field_2 
	$show_custom_field_3 
	$show_custom_field_4

        <tr>
                <td class=\"tbl1-left\" >$LANG_total: </td><td class=\"tbl1-right\" colspan=3>$pref_currency_signField$invoice_total_Field_format</td>
        </tr>   
        <tr>
                <td class=\"tbl1-left\">$LANG_paid:</td><td class=\"tbl1-right\" colspan=3 >$pref_currency_signField$invoice_paid_Field_format</td>
        </tr>   
        <tr>
                <td nowrap class=\"tbl1-left tbl1-bottom\">$LANG_owing:</td><td class=\"tbl1-right tbl1-bottom\" colspan=3 >$pref_currency_signField$invoice_owing_Field</td>
        </tr>   


	</table>
	<!-- Summary - end -->
";

        $display_block_top .= "
        <!-- Biller section - start -->
	<table class='left'>
        <tr>
                <td class=\"tbl1-left tbl1-bottom tbl1-top col1\" border=\"1\" cellpadding=\"2\" cellspacing=\"1\"  ><b>$LANG_biller:</b></td><td class=\"col1 tbl1-bottom tbl1-top tbl1-right\" border=\"1\" cellpadding=\"2\" cellspacing=\"1\" colspan=3>$b_nameField</td>
        </tr> ";

        if ($b_street_addressField != null) {
                $display_block_top .=  "
                <tr >
                        <td class='tbl1-left'>$LANG_address:</td><td class='tbl1-right' align=left colspan=3>$b_street_addressField</td>
                </tr>   
                ";
        }
        if ($b_street_address2Field != null) {
                $display_block_top .=  "
                <tr class='details_screen customer'>";
                if ($b_street_addressField == null) {
                $display_block_top .=  "
                        <td class='tbl1-left'>$LANG_address:</td><td class='tbl1-right' align=left colspan=3>$b_street_address2Field</td>
                </tr>   
                ";
                }
                if ($b_street_addressField != null) {
                $display_block_top .=  "
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>$b_street_address2Field</td>
                </tr>   
                ";
                }
        }


       $display_block_top .=  merge_address($b_cityField, $b_stateField, $b_zip_codeField, $b_street_addressField, $b_street_address2Field,'tbl1-left','tbl1-right',3);

        /*country field start*/
         if ($b_countryField != null) {
                $display_block_top .=  "
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>$b_countryField</td>
                </tr>
                ";
        }
        /*country field end*/

        /*phone details start */
	$display_block_top .= print_if_not_null($LANG_phone_short, $b_phoneField,'tbl1-left','tbl1-right',3);
	$display_block_top .= print_if_not_null($LANG_fax, $b_faxField,'tbl1-left','tbl1-right',3);
	$display_block_top .= print_if_not_null($LANG_mobile_short, $b_mobile_phoneField,'tbl1-left','tbl1-right',3);
	/*
        if ($b_phoneField != null OR $b_phoneField != null OR $b_mobile_phoneField != null) {
                $display_block_top .=  "<tr class='tbl1-left tbl1-right'>";
        }

        if ($b_phoneField != null) {
                $display_block_top .=  "<td>$LANG_phone_short:</td><td>$b_phoneField";
                $tr_b++;
        }
        if ($b_faxField != null) {
                $display_block_top .=  "<td>$LANG_fax:</td><td>$b_faxField</td>";
                $tr_b++;
                $display_block_top .= do_tr($tr_b,'tbl1-left tbl1-right');

        }
        if ($b_mobile_phoneField != null) {
                $display_block_top .=  "<td>$LANG_mobile_short:</td><td>$b_mobile_phoneField</td>";
                $tr_b++;
                $display_block_top .= do_tr($tr_b,'tbl1-left tbl1-right');
        }
        phone details end*/

        $display_block_top .= print_if_not_null($LANG_email, $b_emailField,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($biller_custom_field_label1, $b_custom_field1Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($biller_custom_field_label2, $b_custom_field2Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($biller_custom_field_label3, $b_custom_field3Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($biller_custom_field_label4, $b_custom_field4Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= "<tr><td class='tbl1-top' colspan=4></td></tr>";

        $display_block_top .= "




	<tr>	
		<td colspan=3><br><td>
	</tr>
	<!-- Customer section - start -->
	<tr>
		<td class=\"tbl1-left tbl1-top tbl1-bottom col1\" ><b>$LANG_customer:</b></td><td class=\"tbl1-top tbl1-bottom col1 tbl1-right\" colspan=3>$c_nameField</td>
	</tr>
";

        if ($c_attentionField != null) {
                $display_block_top .=  "
                <tr>
                        <td class='tbl1-left'>$LANG_attention_short:</td><td align=left class='tbl1-right' colspan=3 >$c_attentionField</td>
                </tr>
                ";
        }
        if ($c_street_addressField != null) {
                $display_block_top .=  "
                <tr >
                        <td class='tbl1-left'>$LANG_address:</td><td class='tbl1-right' align=left colspan=3>$c_street_addressField</td>
                </tr>   
                ";
        }
        if ($c_street_address2Field != null) {
                $display_block_top .=  "
                <tr class='details_screen customer'>";
                if ($c_street_addressField == null) {
                $display_block_top .=  "
                        <td class='tbl1-left'>$LANG_address:</td><td class='tbl1-right' align=left colspan=3>$c_street_address2Field</td>
                </tr>   
                ";
                }
                if ($c_street_addressField != null) {
                $display_block_top .=  "
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>$c_street_address2Field</td>
                </tr>   
                ";
                }
        }

        $display_block_top .=  merge_address($c_cityField, $c_stateField, $c_zip_codeField, $c_street_addressField, $c_street_address2Field,'tbl1-left','tbl1-right',3);

        /*country field start*/
         if ($c_countryField != null) {
                $display_block_top .=  "
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>$c_countryField</td>
                </tr>
                ";
        }
        /*country field end*/

        /*phone details start*/
	$display_block_top .= print_if_not_null($LANG_phone_short, $c_phoneField,'tbl1-left','tbl1-right',3);
	$display_block_top .= print_if_not_null($LANG_fax, $c_faxField,'tbl1-left','tbl1-right',3);
	$display_block_top .= print_if_not_null($LANG_mobile_short, $c_mobile_phoneField,'tbl1-left','tbl1-right',3);
	/*
        if ($c_phoneField != null OR $c_phoneField != null OR $c_mobile_phoneField != null) {
                $display_block_top .=  "<tr class=\"tbl1-left tbl1-right\">";
        }

        if ($c_phoneField != null) {
                $display_block_top .=  "<td>$LANG_phone_short:</td><td>$c_phoneField";
                $tr_c++;
        }
        if ($c_faxField != null) {
                $display_block_top .=  "<td>$LANG_fax:</td><td>$c_faxField</td>";
                $tr_c++;
                $display_block_top .= do_tr($tr_c,'tbl1-left tbl1-right');

        }
        if ($c_mobile_phoneField != null) {
                $display_block_top .=  "<td>$LANG_mobile_short:</td><td>$c_mobile_phoneField</td>";
                $tr_c++;
                $display_block_top .= do_tr($tr_c,'tbl1-left tbl1-right');
        }
	*/
        /*phone details end*/

        $display_block_top .= print_if_not_null($LANG_email, $c_emailField,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($customer_custom_field_label1, $c_custom_field1Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($customer_custom_field_label2, $c_custom_field2Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($customer_custom_field_label3, $c_custom_field3Field,'tbl1-left','tbl1-right',3);
        $display_block_top .= print_if_not_null($customer_custom_field_label4, $c_custom_field4Field,'tbl1-left','tbl1-right',3);
	$display_block_top .= "<tr><td class='tbl1-top' colspan=4></td></tr>";

	$display_block_top .= "
	</table>
	<!-- Customer section end -->
	";

#PRINT DETAILS FOR THE TOTAL STYLE INVOICE

if ($_GET[invoice_style] === 'Total') {
        #invoice total layout - no quantity

#get all the details for the total style
	#items invoice id select
	$print_master_invoice_items = "SELECT * FROM si_invoice_items WHERE  inv_it_invoice_id =$master_invoice_id";
	$result_print_master_invoice_items = mysql_query($print_master_invoice_items, $conn) or die(mysql_error());


	while ($Array_master_invoice_items = mysql_fetch_array($result_print_master_invoice_items)) {
                $inv_it_idField = $Array_master_invoice_items['inv_it_id'];
                $inv_it_invoice_idField = $Array_master_invoice_items['inv_it_invoice_id'];
                $inv_it_quantityField = $Array_master_invoice_items['inv_it_quantity'];
                $inv_it_quantityField_formatted = number_format($Array_master_invoice_items['inv_it_quantity'],2);
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_unit_priceField_formatted = number_format($Array_master_invoice_items['inv_it_unit_price'],2);
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_tax_amountField_formatted = number_format($Array_master_invoice_items['inv_it_tax_amount'],2);
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_gross_totalField_formatted = number_format($Array_master_invoice_items['inv_it_gross_total'],2);
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
                $inv_it_totalField_formatted = number_format($Array_master_invoice_items['inv_it_total'],2);

	};

	#products query
	$print_products = "SELECT * FROM si_products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($productArray = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $productArray['prod_id'];
                $prod_descriptionField = $productArray['prod_description'];
                $prod_unit_priceField = $productArray['prod_unit_price'];
                $prod_custom_field1Field = $productArray['prod_custom_field1'];
                $prod_custom_field2Field = $productArray['prod_custom_field2'];
                $prod_custom_field3Field = $productArray['prod_custom_field3'];
                $prod_custom_field4Field = $productArray['prod_custom_field4'];
	};

	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];
                $invoice_total_totalField_formatted = number_format($Array['total'],2);

	};
	#all the details have bee got now print them to screen
	
	$display_block_details =  "
		<table class='left' width=100%>
		<tr>
			<td colspan=6><br></td>
		</td>
	        <tr class=\"tbl1 col1\" >
        	        <td class=\"tbl1 col1 tbl1-right\" colspan=6><b>$LANG_description</b></td>
	        </tr>
	        <tr class=\"tbl1-left tbl1-right\">
	                <td class=\"tbl1-left tbl1-right\" colspan=6>$inv_it_descriptionField</td>
        	</tr>
	        <tr class=\"tbl1-left tbl1-right\">
        	        <td colspan=6 class=\"tbl1-left tbl1-right\"><br></td>
	        </tr>
	        <tr class=\"tbl1-left tbl1-right\">
	                <td class=\"tbl1-left\" width=50%><td align=right><b>$LANG_gross_total</b></td><td align=right><b>$LANG_tax</b></td><td class=\"tbl1-right\" align=right><b>$LANG_total_uppercase</b></td>
        	</tr>
	        <tr class=\"tbl1-left tbl1-right tbl1-bottom\">
        	        <td class=\"tbl1-left tbl1-bottom\" width=50%></td></td><td class=\"tbl1-bottom\" align=right> $pref_currency_signField$inv_it_gross_totalField_formatted</td><td class=\"tbl1-bottom\" align=right>$pref_currency_signField$inv_it_tax_amountField_formatted</td><td class=\"tbl1-bottom tbl1-right\" align=right><u>$pref_currency_signField$inv_it_totalField_formatted</u></td>
	        </tr>
        	<tr>
                	<td colspan=6><br><br></td>
	        </tr>
        	<tr class=\"tbl1 col1\" >
                	<td  class=\"tbl1 col1\" colspan=6><b>$pref_inv_detail_headingField</b></td>
	        </tr>
	";	
   

     }

#INVOICE ITEMEISED SECTION

else if ($_GET[invoice_style] === 'Itemised' || $_GET[invoice_style] === 'Consulting' )  {

$display_block_details =  "
	<table class='left' width=100%>
        <tr>
                <td colspan=6><br></td>
        </tr>
        
        ";

        #show column heading for itemised style
        if ( $_GET['invoice_style'] === 'Itemised' ) {
                $display_block_details .=  "
                <tr>
                        <td class=\"tbl1 col1\" ><b>$LANG_quantity_short</b></td><td class=\"tbl1 col1\" ><b>$LANG_description</b></td><td class=\"tbl1 col1\" ><b>$LANG_unit_price</b><td class=\"tbl1 col1\" ><b>$LANG_gross_total</b></td><td class=\"tbl1 col1\" ><b>$LANG_tax</b></td><td class=\"tbl1 col1\" align=right><b>$LANG_total_uppercase</b></td>
                </tr>";
        }
        #show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {
                $display_block_details .=  "
                <tr class=\"tbl1 col1\">
                        <td class=\"tbl1\"><b>$LANG_quantity_short</b></td><td class=\"tbl1\"><b>$LANG_item</b></td><td class=\"tbl1\"><b>$LANG_unit_price</b><td class=\"tbl1\"><b>$LANG_gross_total</b></td><td class=\"tbl1\"><b>$LANG_tax</b></td><td align=right class=\"tbl1\"><b>$LANG_total_uppercase</b></td>
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
                $inv_it_quantityField_formatted = number_format($Array_master_invoice_items['inv_it_quantity'],2);
                $inv_it_product_idField = $Array_master_invoice_items['inv_it_product_id'];
                $inv_it_unit_priceField = $Array_master_invoice_items['inv_it_unit_price'];
                $inv_it_unit_priceField_formatted = number_format($Array_master_invoice_items['inv_it_unit_price'],2);
                $inv_it_taxField = $Array_master_invoice_items['inv_it_tax'];
                $inv_it_tax_amountField = $Array_master_invoice_items['inv_it_tax_amount'];
                $inv_it_tax_amountField_formatted = number_format($Array_master_invoice_items['inv_it_tax_amount'],2);
                $inv_it_gross_totalField = $Array_master_invoice_items['inv_it_gross_total'];
                $inv_it_gross_totalField_formatted = number_format($Array_master_invoice_items['inv_it_gross_total'],2);
                $inv_it_descriptionField = $Array_master_invoice_items['inv_it_description'];
                $inv_it_totalField = $Array_master_invoice_items['inv_it_total'];
                $inv_it_totalField_formatted = number_format($Array_master_invoice_items['inv_it_total'],2);
/*
	};
*/
	#products query
	$print_products = "SELECT * FROM si_products WHERE prod_id = $inv_it_product_idField";
	$result_print_products = mysql_query($print_products, $conn) or die(mysql_error());


	while ($productArray = mysql_fetch_array($result_print_products)) { 
                $prod_idField = $productArray['prod_id'];
                $prod_descriptionField = $productArray['prod_description'];
                $prod_unit_priceField = $productArray['prod_unit_price'];
                $prod_custom_field1Field = $productArray['prod_custom_field1'];
                $prod_custom_field2Field = $productArray['prod_custom_field2'];
                $prod_custom_field3Field = $productArray['prod_custom_field3'];
                $prod_custom_field4Field = $productArray['prod_custom_field4'];

/*
	};
*/
	#invoice_total total query
	$print_invoice_total_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_total = mysql_query($print_invoice_total_total, $conn) or die(mysql_error());


	while ($Array = mysql_fetch_array($result_print_invoice_total_total)) {
                $invoice_total_totalField = $Array['total'];
                $invoice_total_totalField_formatted = number_format($Array['total'],2);

	#invoice total tax
	$print_invoice_total_tax ="select sum(inv_it_tax_amount) as total_tax from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_tax = mysql_query($print_invoice_total_tax, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_invoice_total_tax)) {
                $invoice_total_taxField = $Array_tax['total_tax'];
                $invoice_total_taxField_formatted = number_format($Array_tax['total_tax'],2);


/*
	};
*/
	#END INVOICE ITEMS SECTION


	#calculation for each line item
	$gross_total_itemised = $prod_unit_priceField * $inv_it_quantityField ;
	/*
	$tax_per_item =  $prod_unit_priceField / $inv_it_taxField;
	$total_tax_per_line = $tax_per_item  * $inv_it_quantityField ;
	$total_per_line = $gross_total_itemised + $total_tax_per_line ;
	*/

        #MERGE ITEMISED AND CONSULTING HERE
        #PRINT the line items
        #show the itemised invoice
        if ( $_GET['invoice_style'] === 'Itemised' ) {

                $display_block_details .=  "
                <tr class=\"tbl1\" >
                        <td class=\"tbl1\">$inv_it_quantityField_formatted</td><td class=\"tbl1\">$prod_descriptionField</td><td class=\"tbl1\">$pref_currency_signField$inv_it_unit_priceField_formatted</td><td class=\"tbl1\">$pref_currency_signField$inv_it_gross_totalField_formatted</td><td class=\"tbl1\">$pref_currency_signField$inv_it_tax_amountField_formatted</td><td class=\"tbl1\">$pref_currency_signField$inv_it_totalField_formatted</td>
                </tr>
                <tr>       
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=5>
                                                <table width=100%>
                                                        <tr>
                ";
                /*Get the custom fields and show them nicely*/
                $display_block_details .= inv_itemised_cf($prod_custom_field_label1, $prod_custom_field1Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $display_block_details .= inv_itemised_cf($prod_custom_field_label2, $prod_custom_field2Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $display_block_details .= inv_itemised_cf($prod_custom_field_label3, $prod_custom_field3Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $display_block_details .= inv_itemised_cf($prod_custom_field_label4, $prod_custom_field4Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $inv_it_tr = 0;

                $display_block_details .=  " 
                                                        </tr>
                                                </table>
                                </td>
                </tr>

                ";
	}
        #show the consulting invoice
        else if ( $_GET['invoice_style'] === 'Consulting' ) {

                

                $display_block_details .=  "
                <tr class=\"tbl1-left tbl1-right\">
                        <td class=\"tbl1-left\" >$inv_it_quantityField_formatted</td><td>$prod_descriptionField</td><td class=\"tbl1-right\" colspan=5></td>
		</tr>
                <tr>       
                        <td class=\"tbl1-left\"></td><td class=\"tbl1-right\" colspan=6>
                                                <table width=100%>
                                                        <tr>
                ";
                /*Get the custom fields and show them nicely*/
                $display_block_details .= inv_itemised_cf($prod_custom_field_label1, $prod_custom_field1Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $display_block_details .= inv_itemised_cf($prod_custom_field_label2, $prod_custom_field2Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $display_block_details .= inv_itemised_cf($prod_custom_field_label3, $prod_custom_field3Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $display_block_details .= inv_itemised_cf($prod_custom_field_label4, $prod_custom_field4Field);
                $inv_it_tr++;
                $display_block_details .= do_tr($inv_it_tr,'blank-class');
                $inv_it_tr = 0;
                $display_block_details .=  " 
                                                        </tr>
                                                </table>
                                </td>
                 </tr>";

                if ($inv_it_descriptionField != null) {
                        $display_block_details .=  "
                		<tr class=\"tbl1-left tbl1-right\">
		                        <td class=\"tbl1-left\"></td><td class=\"tbl1-right\" colspan=6><i>$LANG_description: </i>$inv_it_descriptionField</td>
		                </tr>";
                }

		$display_block_details .=  "
		<tr class=\"tbl1-left tbl1-right tbl1-bottom\">
			<td class=\"tbl1-left tbl1-bottom\" ></td><td class=\"tbl1-bottom\"></td><td class=\"tbl1-bottom\">$pref_currency_signField$inv_it_unit_priceField_formatted</td><td class=\"tbl1-bottom\">$pref_currency_signField$inv_it_gross_totalField_formatted</td><td class=\"tbl1-bottom \">$pref_currency_signField$inv_it_tax_amountField_formatted</td><td align=right colspan=2 class=\"tbl1-right tbl1-bottom\" >$pref_currency_signField$inv_it_totalField_formatted</td>
                </tr>
                ";
        }
	#End merge code here


	};
	};
	};
	};


        #if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' && !empty($inv_noteField) OR 'Consulting' && !empty($inv_noteField)) {

                $display_block_details .=  "
                        <tr>
                                <td class=\"tbl1-left tbl1-right\" colspan=7><br></td>
                        </tr>
                        <tr>
                                <td class=\"tbl1-left tbl1-right\" colspan=7 align=left><b>$LANG_notes:</b></td>
                        </tr>
                        <tr>
                                <td class=\"tbl1-left tbl1-right\" colspan=7>$inv_noteField</td>
                        </tr>
                ";
        }
        #END - if itemised style show the invoice note field



	$display_block_details .=  "
	<tr class=\"tbl1-left tbl1-right\">
		<td class=\"tbl1-left tbl1-right\" colspan=6 ><br></td>
	</tr>	
        <tr class=\"tbl1-left tbl1-right\">
                <td class=\"tbl1-left\" colspan=3 ></td><td align=left colspan=2>$LANG_tax_total</td><td align=right class=\"tbl1-right\" >$pref_currency_signField$invoice_total_taxField_formatted</td>
        </tr>
	<tr class=\"tbl1-left tbl1-right\" >
		<td class=\"tbl1-left tbl1-right\" colspan=6 >
			<br>
		</td>
	</tr>
        <tr class=\"tbl1-left tbl1-right tbl1-bottom\">
                <td class=\"tbl1-left tbl1-bottom\" colspan=3></td><td class=\"tbl1-bottom\" align=left colspan=2><b>$pref_inv_wordingField $LANG_amount</b></td><td  class=\"tbl1-bottom tbl1-right\" align=right><u>$pref_currency_signField$invoice_total_totalField_formatted</u></td>
        </tr>


	<tr>
		<td colspan=6><br><br></td>
	</tr>	
	<tr>
		<td class=\"tbl1 col1\" colspan=6><b>$pref_inv_detail_headingField</b></td>
	</tr>
";
}

#END INVOICE ITEMEISED/CONSULTING SECTION

$display_block_bottom =  "
	<!-- invoice details section - start -->
        <tr>
                <td class=\"tbl1-left tbl1-right\" colspan=6><i>$pref_inv_detail_lineField</i></td>
        </tr>
	<tr>
		<td class=\"tbl1-left tbl1-right\" colspan=6>$pref_inv_payment_methodField</td>
        <tr>
                <td class=\"tbl1-left tbl1-right\" colspan=6>$pref_inv_payment_line1_nameField $pref_inv_payment_line1_valueField</td>
        </tr>
        <tr>
                <td class=\"tbl1-left tbl1-bottom tbl1-right\" colspan=7>$pref_inv_payment_line2_nameField $pref_inv_payment_line2_valueField</td>
        </tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td colspan=6><div style=font-size:8pt align=center >$b_co_footerField</div></td>
	</tr>
        </table>

";

/* The Export code - supports any file extensions - excel/word/open office - what reads html */
if (isset($_GET['export'])) {
$file_extension = $_GET['export'];
header("Content-type: application/octet-stream");
/*header("Content-type: application/x-msdownload");*/
header("Content-Disposition: attachment; filename=$pref_inv_headingField$inv_idField.$file_extension");
header("Pragma: no-cache");
header("Expires: 0");
}
/* End Export code */


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

	<title><?php echo $title; ?></title>
<?php include('./config/config.php'); ?> 
<body>
<br>
<div id="container">
<div id="header">


</div>
<style type="text/css">

body { background: white; color: black; font-style: normal; font-size: 12pt; font-family: Veranda, San-Serif, Arial; }

table {
        border-spacing: 0;
        border-collapse: collapse;
}

td {margin: 0px;      padding: 0px;}
tr {margin: 0px;      padding: 0px;}

.col1 { background-color: #EFEFEF; }

.font1 { font-family: Arial; font-size: 14pt; }

.font2 { color: #990000; font-size: 12pt; font-weight: bold; }

.font3 { font-family: Arial; font-size: 8pt; }

.tbl1 { border: #33628D 1px solid; border-collapse:collapse; }
.tbl1-left { border-left: #33628D 1px solid; border-collapse:collapse; }
.tbl1-right { border-right: #33628D 1px solid; border-collapse:collapse; }
.tbl1-bottom { border-bottom: #33628D 1px solid; border-collapse:collapse; }
.tbl1-top { border-top: #33628D 1px solid; border-collapse:collapse; }

.left {
 clear: left;
}

.right {
 float: right;
}
</style>
<!-- <link rel="stylesheet" type="text/css" href="../themes/<?php echo $theme; ?>/print.css"> -->
<?php echo $display_block_top; ?>
<?php echo $display_block_details; ?>
<?php echo $display_block_bottom; ?>
<div id="footer"></div></div>

</body>
</html>



