<?php
#table
include('../config/config.php'); 
include("../lang/$language.inc.php");

#get the invoice id
$master_invoice_id = $_GET[submit];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


#master invoice id select
$print_master_invoice_id = "SELECT * FROM si_invoices WHERE inv_id =$master_invoice_id";
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
                $inv_idField = $Array_master_invoice['inv_id'];
                $inv_biller_idField = $Array_master_invoice['inv_biller_id'];
                $inv_customer_idField = $Array_master_invoice['inv_customer_id'];
                $inv_typeField = $Array_master_invoice['inv_type'];
                $inv_preferenceField = $Array_master_invoice['inv_preference'];
                $inv_dateField = $Array_master_invoice['inv_date'];
                $inv_noteField = $Array_master_invoice['inv_note'];

};

#master invoice date select
$print_master_invoice_date = "SELECT DATE_FORMAT(inv_date, '%W %D %M %Y') FROM si_invoices WHERE inv_id =$master_invoice_id";
$result_print_master_invoice_date = mysql_query($print_master_invoice_date , $conn) or die(mysql_error());

$Array_master_invoice_date = mysql_fetch_array($result_print_master_invoice_date); 
                $inv_dateField = $Array_master_invoice_date[0];
		

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
                $c_cityField = $Array['c_city'];
                $c_stateField = $Array['c_state'];
                $c_zip_codeField = $Array['c_zip_code'];
                $c_countryField = $Array['c_country'];
		$c_phoneField = $Array['c_phone'];
		$c_faxField = $Array['c_fax'];
		$c_emailField = $Array['c_email'];
};
while ($billerArray = mysql_fetch_array($result_print_biller)) {
                $b_idField = $billerArray['b_id'];
                $b_nameField = $billerArray['b_name'];
                $b_street_addressField = $billerArray['b_street_address'];
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


#logo field support - if not logo show nothing else show logo

if (!empty($b_co_logoField)) {
        $logo_block = "
        
                <IMG src=../logo/$b_co_logoField border=0 hspace=0>
        
 ";
}
if (empty($b_co_logoField)) {
        $logo_block = "
     
                <IMG src=../logo/_default_blank_logo.png border=0 hspace=0>
        
 ";
}
#end logo section





$display_block_top =  "

<TABLE width=90% border=0 align=center>
<tr><th colspan=2 align=center style=font-size:8pt>$pref_inv_headingField</th></tr>
<tr><td width=50%>$logo_block</td><td width=50% style=font-size:10pt><h2>$b_nameField</h2>$b_street_addressField, $b_cityField, $b_stateField, $b_zip_codeField<br>Tel:$b_phoneField &nbsp; E-mail: $b_emailField</td></tr>
</TABLE>
<br>
<TABLE width=90% align=center cellspacing=0 cellpadding=0>
<tr><td width=50%><table border=1 width=90% align=left><tr><td style=font-size:12pt>Attn: $c_attentionField,<br>$c_nameField,<br>$c_street_addressField,<br>$c_cityField,<br>$c_stateField, $c_zip_codeField<br>$c_countryField</td></tr></table></td><td width=50% valign=top><b>$pref_inv_wordingField No.  </b>$inv_it_invoice_idField<br>$pref_inv_wordingField date. $inv_dateField</td></tr>
</TABLE>
<br>
<TABLE width=90% border=1 align=center cellpadding=4 cellspacing=0>
<tr><td width=84% style=font-size:10pt> Provision of:- </td><td width=16% style=font-size:10pt>&nbsp;</td></tr>
</TABLE>
";



if ($_GET[invoice_style] == "Total") {

#invoice total layout - no quantity

$blankrow="<tr><td width=70% style=font-size:10pt>&nbsp;</td><td width=15% align=right style=font-size:10pt></td><td width=15% align=right style=font-size:10pt></td>
</tr>
";

$display_block_details =  "

<TABLE width=90% border=1 align=center cellpadding=2 cellspacing=0>
<tr><td width=84%><table width=100% border=0 cellpadding=0 cellspacing=0>
$blankrow

<tr><td colspan=3 style=font-size:10pt>$inv_it_descriptionField</td>
<td width=16% align=right style=font-size:10pt><b>&nbsp;</b></td></tr>


$blankrow$blankrow$blankrow

<tr><td width=64% style=font-size:10pt>&nbsp;</td><td width=10% align=right style=font-size:10pt>Total</td><td width=10% align=right style=font-size:10pt>Tax</td>
<td width=16% align=right style=font-size:10pt><b>&nbsp;</b></td></tr>

<tr><td width=64% style=font-size:10pt>&nbsp;</td><td width=10% align=right style=font-size:10pt>$pref_currency_signField $inv_it_gross_totalField</td><td width=10% align=right style=font-size:10pt>$pref_currency_signField $inv_it_tax_amountField</td>
<td width=16% align=right style=font-size:10pt><b>&nbsp;</b></td></tr>


</table>

<td width=16% align=right style=font-size:10pt><b>&nbsp</b></td>

<tr><td width=84% style=font-size:10pt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$pref_inv_wordingField Sum</b></td>
<td width=16% align=right style=font-size:10pt><b><u>$pref_currency_signField $invoice_total_totalField</u></b></td></tr>

</TABLE>
";
}

else if ($_GET[invoice_style] === 'Itemised' || $_GET[invoice_style] === 'Consulting') {

$blankrow="<tr><td width=84% ><TABLE width=100% border=0 align=left>
    <tr><td width=8% style=font-size:10pt>&nbsp;</td>
    <td width=40% style=font-size:10pt></td>
    <td width=12% align=right style=font-size:10pt></td>
    <td width=12% align=right style=font-size:10pt></td>
    <td width=15% align=right style=font-size:10pt></td>
    <td width=15% align=right style=font-size:10pt></td></tr>
    </TABLE></td>
<td width=16% align=right style=font-size:10pt><b>&nbsp;</b></td></tr>
";

        #show column heading for itemised style
        if ( $_GET['invoice_style'] === 'Itemised' ) {

		$display_block_details =  "
		<TABLE width=90% border=1 align=center cellpadding=2 cellspacing=0>
		<tr><td width=84% ><TABLE width=100% border=0 align=left>
		    <tr><td width=8% style=font-size:10pt><b>Qty</b></td><td width=40% style=font-size:10pt><b>Description</b></td>
		    <td width=12% align=right style=font-size:10pt><b>Unit Price</b></td>
		    <td width=15% align=right style=font-size:10pt><b>Sub Total</b></td>
		    <td width=15% align=right style=font-size:10pt><b>Tax &nbsp;</b></td></tr>
		    </TABLE></td>
		<td width=16% align=center style=font-size:10pt><b>TOTAL</b></td></tr>";

        }
        #show column heading for consulting style
        else if ( $_GET['invoice_style'] === 'Consulting' ) {

                $display_block_details =  "
                <TABLE width=90% border=1 align=center cellpadding=2 cellspacing=0>
                <tr><td width=84% ><TABLE width=100% border=0 align=left>
                    <tr><td width=8% style=font-size:10pt><b>Qty</b></td><td width=40% style=font-size:10pt><b>Item</b></td>
                    <td width=12% align=right style=font-size:10pt><b>Unit Price</b></td>
                    <td width=15% align=right style=font-size:10pt><b>Sub Total</b></td>
                    <td width=15% align=right style=font-size:10pt><b>Tax &nbsp;</b></td></tr>
                    </TABLE></td>
                <td width=16% align=center style=font-size:10pt><b>TOTAL</b></td></tr>";

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
			<tr><td width=84% ><TABLE width=100% border=0 align=left>
			    <tr><td width=8% style=font-size:10pt>$inv_it_quantityField</td>
			    <td width=40% style=font-size:10pt>$prod_descriptionField</td>
			    <td width=12% align=right style=font-size:10pt>$pref_currency_signField $inv_it_unit_priceField</td>
			    <td width=15% align=right style=font-size:10pt>$pref_currency_signField $inv_it_gross_totalField</td>
			    <td width=15% align=right style=font-size:10pt>$pref_currency_signField $inv_it_tax_amountField</td></tr>
			    </TABLE></td>
			    <td width=16% align=right style=font-size:10pt><b>$pref_currency_signField $inv_it_totalField</b></td></tr>";
        }
        #show the consulting invoice
        else if ( $_GET['invoice_style'] === 'Consulting' ) {



                $display_block_details .=  "
			<tr><td width=84% ><TABLE width=100% border=0 align=left>
			    <tr><td width=8% style=font-size:10pt>$inv_it_quantityField</td>
			    <td width=40% style=font-size:10pt>$prod_descriptionField</td></tr>
				<tr><td width=8% ></td><td width=76% colspan=6 style=font-size:10pt align=left><i>Description: </i>$inv_it_descriptionField</td></tr>
			    <tr>
				<td width=8%></td>
				<td width=40%></td>
				<td width=12% align=right style=font-size:10pt>$pref_currency_signField $inv_it_unit_priceField</td>
	            		<td width=15% align=right style=font-size:10pt>$pref_currency_signField $inv_it_gross_totalField</td>
			        <td width=15% align=right style=font-size:10pt>$pref_currency_signField $inv_it_tax_amountField</td>
			    </tr>
			    </TABLE></td>
			    <td width=16% align=right valign=bottom style=font-size:10pt><b>$pref_currency_signField $inv_it_totalField</b></td></tr>";
        }
        #End merge code here



};
};
};
};

        #if itemised style show the invoice note field - START
	if ( $_GET['invoice_style'] === 'Itemised' && !empty($inv_noteField)) {

                $display_block_details .=  "
                       <tr><td width=84% ><TABLE width=100% border=0 align=left> 
			<tr>
                                <td width=84% style=font-size:10pt><i>Note:</i></td><td width=16% align=right valign=bottom style=font-size:10pt></td>
                        </tr>
                        <tr>
                                <td width=84% style=font-size:10pt>$inv_noteField</td><td width=16% align=right valign=bottom style=font-size:10pt></td>


</TABLE></td><td>&nbsp;</td>

                ";
        }
        #END - if itemised style show the invoice note field


$display_block_details .=  "



$blankrow$blankrow$blankrow$blankrow

<tr><td width=84% ><TABLE width=100% border=0 align=left>
    <tr><td width=8% style=font-size:10pt>&nbsp;</td>
    <td width=40% style=font-size:10pt><b>$pref_inv_wordingField Sum</b></td>
    <td width=12% align=right style=font-size:10pt></td>
    <td width=15% align=right style=font-size:10pt></td>
    <td width=15% align=right style=font-size:10pt><b>$pref_currency_signField $invoice_total_taxField</b></td></tr>
    </TABLE></td>
    <td width=16% align=right style=font-size:10pt><b><u>$pref_currency_signField $invoice_total_totalField</u></b></td></tr>
</TABLE>
";

}

$display_block_bottom =  "
<TABLE width=90% border=0 align=center>
<tr><td style=font-size:10pt><b>$pref_inv_detail_headingField</b></td></tr>
<tr><td style=font-size:8pt><i>$pref_inv_detail_lineField</i></td></tr>
<tr><td style=font-size:8pt>$pref_inv_payment_line1_nameField $pref_inv_payment_line1_valueField</td></tr>
<tr><td style=font-size:8pt>$pref_inv_payment_line2_nameField $pref_inv_payment_line2_valueField</td></tr>
</TABLE>
<br><br>
<div style=font-size:8pt align=center >$b_co_footerField
</div>
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

<title>Simple Invoices
</title>
<body>

<?php echo $display_block_top; ?>
<?php echo $display_block_details; ?>
<?php echo $display_block_bottom; ?>

</body>
</html>



