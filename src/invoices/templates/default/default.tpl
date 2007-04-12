<?php


$logo_block = <<<EOD
	<table width="100%" align="center">
			<tr>
	   				<td colspan="5"><img src="$logo" border="0" hspace="0" align="left"></td><th align=right><span class="font1">$pref[pref_inv_heading]</span></th>
			</tr>
			<tr>
					<td colspan=6><hr size="1"></td>
			</tr>
	</table>
EOD;



$invoice_summary = <<<EOD
	<!-- Summary - start -->

	<table class="right">
		<tr>
				<td class="col1 tbl1" colspan="4" ><b>$pref[pref_inv_wording] $LANG['summary']</b></td>
		</tr>
		<tr>
				<td class="tbl1-left">$pref[pref_inv_wording] $LANG['number_short']:</td><td class="tbl1-right" colspan=3>$invoice[inv_id]</td>
		</tr>
		<tr>
				<td nowrap class="tbl1-left">$pref[pref_inv_wording] $LANG['date']:</td><td class="tbl1-right" colspan=3>$invoice[date]</td>
		</tr>
	<!-- Show the Invoice Custom Fields if valid -->

		$show[custom_field1]
		$show[custom_field2]
		$show[custom_field3]
		$show[custom_field4]

		<tr>
				<td class="tbl1-left" >$LANG['total']: </td><td class="tbl1-right" colspan=3>$pref[pref_currency_sign]$invoice[total_format]</td>
		</tr>
		<tr>
				<td class="tbl1-left">$LANG['paid']:</td><td class="tbl1-right" colspan=3 >$pref[pref_currency_sign]$invoice[paid_format]</td>
		</tr>
		<tr>
				<td nowrap class="tbl1-left tbl1-bottom">$LANG['owing']:</td><td class="tbl1-right tbl1-bottom" colspan=3 >$pref[pref_currency_sign]$invoice[owing]</td>
		</tr>


	</table>
	<!-- Summary - end -->
EOD;





	$biller_block =<<<EOD
        <!-- Biller section - start -->
	<table class='left'>
        <tr>
                <td class="tbl1-left tbl1-bottom tbl1-top col1" border=1 cellpadding=2 cellspacing=1><b>$LANG['biller']:</b></td><td class="col1 tbl1-bottom tbl1-top tbl1-right" border=1 cellpadding=2 cellspacing=1 colspan=3>$biller[b_name]</td>
        </tr> 
EOD;

        if ($biller[b_street_address] != null) {
                $biller_block .=<<<EOD
                <tr>
                        <td class='tbl1-left'>$LANG['address']:</td><td class='tbl1-right' align=left colspan=3>$biller[b_street_address]</td>
                </tr>   
EOD;
        }
        if ($biller[b_street_address2] != null) {
                $biller_block .=<<<EOD
                <tr class='details_screen customer'>
EOD;
                if ($biller[b_street_address] == null) {
                $biller_block .=<<<EOD
                        <td class='tbl1-left'>$LANG['address']:</td><td class='tbl1-right' align=left colspan=3>$biller[b_street_address2]</td>
                </tr>   
EOD;
                }
                if ($biller[b_street_address] != null) {
                $biller_block .=<<<EOD
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>$biller[b_street_address2]</td>
                </tr>   
EOD;
                }
        }


       $biller_block .=  merge_address($biller[b_city], $biller[b_state], $biller[b_zip_code], $biller[b_street_address], $biller[b_street_address2],'tbl1-left','tbl1-right',3);

        /*country field start*/
         if ($biller[b_country] != null) {
                $biller_block .=<<<EOD
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>$biller[b_country]</td>
                </tr>
EOD;
        }
        /*country field end*/

        /*phone details start */
	$biller_block .= print_if_not_null($LANG['phone_short'], $biller[b_phone],'tbl1-left','tbl1-right',3);
	$biller_block .= print_if_not_null($LANG['fax'], $biller[b_fax],'tbl1-left','tbl1-right',3);
	$biller_block .= print_if_not_null($LANG['mobile_short'], $biller[b_mobile_phone],'tbl1-left','tbl1-right',3);


        $biller_block .= print_if_not_null($LANG['email'], $biller[b_email],'tbl1-left','tbl1-right',3);
        $biller_block .= print_if_not_null($biller[custom_field_label1], $biller[b_custom_field1],'tbl1-left','tbl1-right',3);
        $biller_block .= print_if_not_null($biller[custom_field_label2], $biller[b_custom_field2],'tbl1-left','tbl1-right',3);
        $biller_block .= print_if_not_null($biller[custom_field_label3], $biller[b_custom_field3],'tbl1-left','tbl1-right',3);
        $biller_block .= print_if_not_null($biller[custom_field_label4], $biller[b_custom_field4],'tbl1-left','tbl1-right',3);
        $biller_block .=<<<EOD
	<tr><td class="tbl1-top" colspan=4></td></tr>

<!-- Biller section - end -->
EOD;



        $customer_block =<<<EOD

	<!-- Customer section - start -->
	<tr>
		<td class="tbl1-left tbl1-top tbl1-bottom col1" ><b>$LANG['customer']:</b></td><td class="tbl1-top tbl1-bottom col1 tbl1-right" colspan=3>$customer[c_name]</td>
	</tr>
EOD;

        if ($customer[c_attention] != null) {
                $customer_block .=<<<EOD
                <tr>
                        <td class='tbl1-left'>$LANG['attention_short']:</td><td align=left class='tbl1-right' colspan=3 >$customer[c_attention]</td>
                </tr>
EOD;
        }
        if ($customer[c_street_address] != null) {
                $customer_block .=<<<EOD
                <tr >
                        <td class='tbl1-left'>$LANG['address']:</td><td class='tbl1-right' align=left colspan=3>$customer[c_street_address]</td>
                </tr>   
EOD;
        }
        if ($customer[c_street_address2] != null) {
                $customer_block .=<<<EOD
                <tr class='details_screen customer'>
EOD;
                if ($customer[c_street_address] == null) {
                $customer_block .=<<<EOD
                        <td class='tbl1-left'>$LANG['address']:</td><td class='tbl1-right' align=left colspan=3>$customer[c_street_address2]</td>
                </tr>   
EOD;
                }
                if ($customer[c_street_address] != null) {
                $customer_block .=<<<EOD
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>$customer[c_street_address2]</td>
                </tr>   
EOD;
                }
        }

        $customer_block .=  merge_address($customer[c_city], $customer[c_state], $customer[c_zip_code], $customer[c_street_address], $customer[c_street_address2],'tbl1-left','tbl1-right',3);

        /*country field start*/
         if ($customer[c_country] != null) {
                $customer_block .=<<<EOD
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>$customer[c_country]</td>
                </tr>
EOD;
        }
        /*country field end*/

        /*phone details start*/
	$customer_block .= print_if_not_null($LANG['phone_short'], $customer[c_phone],'tbl1-left','tbl1-right',3);
	$customer_block .= print_if_not_null($LANG['fax'], $customer[c_fax],'tbl1-left','tbl1-right',3);
	$customer_block .= print_if_not_null($LANG['mobile_short'], $customer[c_mobile_phone],'tbl1-left','tbl1-right',3);


        $customer_block .= print_if_not_null($LANG['email'], $customer[c_email],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label1], $customer[c_custom_field1],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label2], $customer[c_custom_field2],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label3], $customer[c_custom_field3],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label4], $customer[c_custom_field4],'tbl1-left','tbl1-right',3);
	$customer_block .=<<<EOD
		<tr><td class="tbl1-top" colspan=4></td></tr></table>
EOD;


$invoice_total_block =  <<<EOD
		<table class="left" width="100%">
		<tr>
			<td colspan=6><br></td>
		</td>
			<tr class="tbl1 col1" >
					<td class="tbl1 col1 tbl1-right" colspan=6><b>$LANG['description']</b></td>
			</tr>
			<tr class="tbl1-left tbl1-right">
					<td class="tbl1-left tbl1-right" colspan=6>$master_invoice[inv_it_description]</td>
			</tr>
			<tr class="tbl1-left tbl1-right">
					<td colspan=6 class="tbl1-left tbl1-right"><br></td>
			</tr>
			<tr class="tbl1-left tbl1-right">
					<td class="tbl1-left" width="50%"><td align=right><b>$LANG['gross_total']</b></td><td align=right><b>$LANG['tax']</b></td><td class="tbl1-right" align=right><b>$LANG['total_uppercase']</b></td>
			</tr>
			<tr class="tbl1-left tbl1-right tbl1-bottom">
					<td class="tbl1-left tbl1-bottom" width="50%"></td></td><td class="tbl1-bottom" align=right> $pref[pref_currency_sign]$master_invoice[inv_it_gross_total]</td><td class="tbl1-bottom" align=right>$pref[pref_currency_sign]$master_invoice[inv_it_tax_amount]</td><td class="tbl1-bottom tbl1-right" align=right><u>$pref[pref_currency_sign]$master_invoice[inv_it_total]</u></td>
			</tr>
			<tr>
					<td colspan=6><br><br></td>
			</tr>
			<tr class="tbl1 col1" >
					<td  class="tbl1 col1" colspan=6><b>$pref[pref_inv_detail_heading]</b></td>
			</tr>
EOD;


$consulting_heading = <<<EOD
		<tr class="tbl1 col1">
			<td class="tbl1"><b>$LANG['quantity_short']</b></td>
			<td class="tbl1"><b>$LANG['item']</b></td>
			<td class="tbl1"><b>$LANG['unit_price']</b></td>
			<td class="tbl1"><b>$LANG['gross_total']</b></td><td class="tbl1"><b>$LANG['tax']</b></td>
			<td align="right" class="tbl1"><b>$LANG['total_uppercase']</b></td>
		</tr>
EOD;


$itemised_heading = <<<EOD
			<tr>
				<td class="tbl1 col1" ><b>$LANG['quantity_short']</b></td>
				<td class="tbl1 col1" ><b>$LANG['description']</b></td>
				<td class="tbl1 col1" ><b>$LANG['unit_price']</b></td>
				<td class="tbl1 col1" ><b>$LANG['gross_total']</b></td>
				<td class="tbl1 col1" ><b>$LANG['tax']</b></td>
				<td class="tbl1 col1" align=right><b>$LANG['total_uppercase']</b></td>
			</tr>
EOD;

/*note - need to split heading from invoice details*/
$total_heading = <<<EOD
                <table class="left" width="100%">
		<!--
                <tr>
                        <td colspan="6"><br></td>
                </td>
		-->
                <tr class="tbl1 col1" >
                        <td class="tbl1 col1 tbl1-right" colspan="6"><b>$LANG['description']</b></td>
                </tr>
EOD;


$total_line = <<<EOD
                <tr class="tbl1-left tbl1-right">
                        <td class="tbl1-left tbl1-right\" colspan=6>$master_invoice[inv_it_description]</td>
                </tr>
                <tr class="tbl1-left tbl1-right">
                        <td colspan=6 class="tbl1-left tbl1-right"><br></td>
                </tr>
	<!--
                <tr class="tbl1-left tbl1-right">
                        <td class="tbl1-left" width="50"%>
			<td align="right"><b>$LANG['gross_total']</b></td>
			<td align=right><b>$LANG['tax']</b></td>
			<td class="tbl1-right" align=right><b>$LANG['total_uppercase']</b></td>
                </tr>
                <tr class="tbl1-left tbl1-right tbl1-bottom">
			<td class="tbl1-left tbl1-bottom" width="50%"></td>
			</td><td class="tbl1-bottom" align=right> $pref[pref_currency_sign]$master_invoice[inv_it_gross_total]</td>
			<td class="tbl1-bottom" align=right>$pref[pref_currency_sign]$master_invoice[inv_it_tax_amount]</td>
			<td class="tbl1-bottom tbl1-right" align=right><u>$pref[pref_currency_sign]$master_invoice[inv_it_total]</u></td>
                </tr>
-->

EOD;

$itemised_line = <<<EOD
			<tr class="tbl1" >
				<td class="tbl1">$master_invoice[inv_it_quantity_formatted]</td>
				<td class="tbl1">$product[prod_description]</td>
				<td class="tbl1">$pref[pref_currency_sign]$master_invoice[inv_it_unit_price]</td>
				<td class="tbl1">$pref[pref_currency_sign]$master_invoice[inv_it_gross_total]</td>
				<td class="tbl1">$pref[pref_currency_sign]$master_invoice[inv_it_tax_amount]</td>
				<td class="tbl1">$pref[pref_currency_sign]$master_invoice[inv_it_total]</td>
			</tr>
                <tr>
                        <td class="tbl1-left"></td><td class="tbl1-right" colspan="5">
                                                <table width="100%">
                                                        <tr>

EOD;
                /*Get the custom fields and show them nicely*/
                $itemised_line .= inv_itemised_cf($product_cf["custom_field_label1"], $product[prod_custom_field1]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $itemised_line .= inv_itemised_cf($product_cf[custom_field_label2], $product[prod_custom_field2]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $itemised_line .= inv_itemised_cf($product_cf[custom_field_label3], $product[prod_custom_field3]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $itemised_line .= inv_itemised_cf($product_cf[custom_field_label4], $product[prod_custom_field4]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $inv_it_tr = 0;
                $itemised_line .=  "
                                                        </tr>
                                                </table>
                                </td>
                 </tr>";
/*Consulting invoices - custom fields end*/



/*Consulting invoices - custom fields start*/

$consulting_line =  <<<EOD
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left" >$master_invoice[inv_it_quantity_formatted]</td>
				<td>$product[prod_description]</td><td class="tbl1-right" colspan="5"></td>
			</tr>
			
                <tr>       
                        <td class="tbl1-left"></td><td class="tbl1-right" colspan="6">
                                                <table width="100%">
                                                        <tr>
EOD;
                /*Get the custom fields and show them nicely*/
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label1], $product[prod_custom_field1]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label2], $product[prod_custom_field2]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label3], $product[prod_custom_field3]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label4], $product[prod_custom_field4]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $inv_it_tr = 0;
                $consulting_line .=  " 
                                                        </tr>
                                                </table>
                                </td>
                 </tr>";
/*Consulting invoices - custom fields end*/

$consulting_line .=  <<<EOD

	
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left"></td>
				<td class="tbl1-right" colspan=6><i>$LANG['description']: </i>$master_invoice[inv_it_description]</td>
			</tr>
			<tr class="tbl1-left tbl1-right tbl1-bottom">
				<td class="tbl1-left tbl1-bottom" ></td>
				<td class="tbl1-bottom"></td>
				<td class="tbl1-bottom">$pref[pref_currency_sign]$master_invoice[inv_it_unit_price]</td>
				<td class="tbl1-bottom">$pref[pref_currency_sign]$master_invoice[inv_it_gross_total]</td>
				<td class="tbl1-bottom ">$pref[pref_currency_sign]$master_invoice[inv_it_tax_amount]</td>
				<td align=right colspan=2 class="tbl1-right tbl1-bottom">$pref[pref_currency_sign]$master_invoice[inv_it_total]</td>
			</tr>
EOD;

if ( ($_GET['invoice_style'] === 'Itemised' && !empty($invoice[inv_note])) OR ($_GET['invoice_style'] === 'Consulting' && !empty($invoice[inv_note]) ) ) {

	$notes =  <<<EOD
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7"><br></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7" align="left"><b>$LANG['notes']:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7">$invoice[inv_note]</td>
		</tr>
EOD;
}

if ( $_GET['invoice_style'] === 'Total') {
$gross_total =  <<<EOD
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left" colspan="3"></td>
		<td align="right" colspan="2">$LANG['gross_total']</td>
		<td align="right" class="tbl1-right" >$pref[pref_currency_sign]$master_invoice[inv_it_gross_total]</td>
	</tr>
EOD;
}

$total_tax =  <<<EOD
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left" colspan="3"></td>
		<td align="right" colspan="2">$LANG['tax_total']</td>
		<td align="right" class="tbl1-right" >$pref[pref_currency_sign]$tax[total_tax]</td>
	</tr>
EOD;

$total_invoice = <<<EOD
	<tr class="tbl1-left tbl1-right tbl1-bottom">
		<td class="tbl1-left tbl1-bottom" colspan="3"></td>
		<td class="tbl1-bottom" align=right colspan=2><b>$pref[pref_inv_wording] $LANG['amount']</b></td>
		<td  class="tbl1-bottom tbl1-right" align=right><u>$pref[pref_currency_sign]$invoice_total[total]</u></td>
	</tr>
EOD;


$details = <<<EOD
	<!-- invoice details section - start -->
	<tr>
		<td class="tbl1 col1" colspan="6"><b>$pref[pref_inv_detail_heading]</b></td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan=6><i>$pref[pref_inv_detail_line]</i></td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan=6>$pref[pref_inv_payment_method]</td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan=6>$pref[pref_inv_payment_line1_name] $pref[pref_inv_payment_line1_value]</td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-bottom tbl1-right" colspan=7>$pref[pref_inv_payment_line2_name] $pref[pref_inv_payment_line2_value]</td>
	</tr>
EOD;

$footer = <<<EOD
	<tr>
		<td colspan="6"><div style="font-size:8pt;" align="center">$biller[b_co_footer]</div></td>
	</tr>
EOD;

?>
