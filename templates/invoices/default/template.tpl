		{include file='../templates/invoices/default/functions.tpl'}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>{$title}</title>
<body>
<br>
<div id="container">
<div id="header">


</div>

<link rel="stylesheet" type="text/css" href="{$css}">

	<table width="100%" align="center">
			<tr>
	   				<td colspan="5"><img src="$logo" border="0" hspace="0" align="left"></td><th align=right><span class="font1">{$pref.pref_inv_heading}</span></th>
			</tr>
			<tr>
					<td colspan=6><hr size="1"></td>
			</tr>
	</table>
	
	<!-- Summary - start -->

	<table class="right">
		<tr>
				<td class="col1 tbl1" colspan="4" ><b>{$pref.pref_inv_wording} {$LANG.summary}</b></td>
		</tr>
		<tr>
				<td class="tbl1-left">{$pref.pref_inv_wording} {$LANG.number_short}:</td><td class="tbl1-right" colspan=3>{$invoice.id}</td>
		</tr>
		<tr>
				<td nowrap class="tbl1-left">{$pref.pref_inv_wording} {$LANG.date}:</td><td class="tbl1-right" colspan=3>{$invoice.date}</td>
		</tr>
	<!-- Show the Invoice Custom Fields if valid -->

		{$show.custom_field1}
		{$show.custom_field2}
		{$show.custom_field3}
		{$show.custom_field4}

		<tr>
				<td class="tbl1-left" >{$LANG.total}: </td><td class="tbl1-right" colspan=3>{$pref.pref_currency_sign}{$invoice.total_format}</td>
		</tr>
		<tr>
				<td class="tbl1-left">{$LANG.paid}:</td><td class="tbl1-right" colspan=3 >{$pref.pref_currency_sign}{$invoice.paid_format}</td>
		</tr>
		<tr>
				<td nowrap class="tbl1-left tbl1-bottom">{$LANG.owing}:</td><td class="tbl1-right tbl1-bottom" colspan=3 >{$pref.pref_currency_sign}{$invoice.owing}</td>
		</tr>


	</table>
	<!-- Summary - end -->
	
	
	<table class="left">

        <!-- Biller section - start -->
	<table class='left'>
        <tr>
                <td class="tbl1-left tbl1-bottom tbl1-top col1" border=1 cellpadding=2 cellspacing=1><b>{$LANG.biller}:</b></td><td class="col1 tbl1-bottom tbl1-top tbl1-right" border=1 cellpadding=2 cellspacing=1 colspan=3>{$biller.name}</td>
        </tr> 

        {if $biller.street_address != null}
                <tr>
                        <td class='tbl1-left'>{$LANG.address}:</td><td class='tbl1-right' align=left colspan=3>{$biller.street_address}</td>
                </tr>   
        {/if}
        {if $biller.street_address2 != null }
                <tr class='details_screen customer'>

                {if $biller.street_address == null }
                        <td class='tbl1-left'>{$LANG.address}:</td><td class='tbl1-right' align=left colspan=3>{$biller.street_address2}</td>
                </tr>   
                {/if}
                {if $biller.street_address != null }
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>{$biller.street_address2}</td>
                </tr>   
                {/if}
        {/if}

		{php}
		       echo  merge_address($biller.city, $biller.state, $biller.zip_code, $biller.street_address, $biller.street_address2,'tbl1-left','tbl1-right',3);
		{/php}


         {if $biller.country != null }
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$biller.country}</td>
                </tr>
        {/if}

	{php}
        
	echo print_if_not_null($LANG.phone_short, $biller.phone,'tbl1-left','tbl1-right',3);
	echo print_if_not_null($LANG.fax, $biller.fax,'tbl1-left','tbl1-right',3);
	echo print_if_not_null($LANG.mobile_short, $biller.mobile_phone,'tbl1-left','tbl1-right',3);


        echo print_if_not_null($LANG['email'], $biller[email],'tbl1-left','tbl1-right',3);
        echo print_if_not_null($biller[custom_field_label1], $biller[custom_field1],'tbl1-left','tbl1-right',3);
        echo print_if_not_null($biller[custom_field_label2], $biller[custom_field2],'tbl1-left','tbl1-right',3);
        echo print_if_not_null($biller[custom_field_label3], $biller[custom_field3],'tbl1-left','tbl1-right',3);
        echo print_if_not_null($biller[custom_field_label4], $biller[custom_field4],'tbl1-left','tbl1-right',3);
	{/php}

	<tr><td class="tbl1-top" colspan=4></td></tr>

<!-- Biller section - end -->






		<tr>
			<td colspan=3><br /><td>
		</tr>

	<!-- Customer section - start -->
	<tr>
		<td class="tbl1-left tbl1-top tbl1-bottom col1" ><b>{$LANG.customer}:</b></td><td class="tbl1-top tbl1-bottom col1 tbl1-right" colspan=3>{$customer.name}</td>
	</tr>

        {if $customer.attention != null }
                <tr>
                        <td class='tbl1-left'>{$LANG.attention_short}:</td><td align=left class='tbl1-right' colspan=3 >{$customer.attention}</td>
                </tr>
       {/if}
        {if $customer.street_address != null }
                <tr >
                        <td class='tbl1-left'>{$LANG.address}:</td><td class='tbl1-right' align=left colspan=3>{$customer.street_address}</td>
                </tr>   
        {/if}
        {if $customer.street_address2 != null}
                <tr class='details_screen customer'>
                {if $customer.street_address == null}
                        <td class='tbl1-left'>{$LANG.address}:</td><td class='tbl1-right' align=left colspan=3>{$customer.street_address2}</td>
                </tr>   
                {/if}
                {if $customer.street_address != null}
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>{$customer.street_address2}</td>
                </tr>   
                {/if}
        {/if}

		{php}
        echo  merge_address($customer[city], $customer[state], $customer[zip_code], $customer[street_address], $customer[street_address2],'tbl1-left','tbl1-right',3);
        {/php}

         {if $customer.country != null}
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$customer.country}</td>
                </tr>
        {/if}

	{php}
		$customer_block .= print_if_not_null($LANG['phone_short'], $customer[phone],'tbl1-left','tbl1-right',3);
		$customer_block .= print_if_not_null($LANG['fax'], $customer[fax],'tbl1-left','tbl1-right',3);
		$customer_block .= print_if_not_null($LANG['mobile_short'], $customer[mobile_phone],'tbl1-left','tbl1-right',3);


        $customer_block .= print_if_not_null($LANG['email'], $customer[email],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label1], $customer[custom_field1],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label2], $customer[custom_field2],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label3], $customer[custom_field3],'tbl1-left','tbl1-right',3);
        $customer_block .= print_if_not_null($customer[custom_field_label4], $customer[custom_field4],'tbl1-left','tbl1-right',3);
        echo $customer_block;
	{/php}

		<tr><td class="tbl1-top" colspan=4></td></tr></table>


	</table>
		<table class="left" width="100%">
		<tr>
			<td colspan="6"><br /></td>
		</tr>

	{if $smarty.get.invoice_style === 'Itemised' }
					<tr>
				<td class="tbl1 col1" ><b>{$LANG.quantity_short}</b></td>
				<td class="tbl1 col1" ><b>{$LANG.description}</b></td>
				<td class="tbl1 col1" ><b>{$LANG.unit_price}</b></td>
				<td class="tbl1 col1" ><b>{$LANG.gross_total}</b></td>
				<td class="tbl1 col1" ><b>{$LANG.tax}</b></td>
				<td class="tbl1 col1" align=right><b>{$LANG.total_uppercase}</b></td>
			</tr>
			
				{foreach from=$master_invoices item=master_invoice}

			<tr class="tbl1" >
				<td class="tbl1">{$master_invoice.inv_it_quantity_formatted}</td>
				<td class="tbl1">{$product.description}</td>
				<td class="tbl1">{$pref.pref_currency_sign}{$master_invoice.inv_it_unit_price}</td>
				<td class="tbl1">{$pref.pref_currency_sign}{$master_invoice.inv_it_gross_total}</td>
				<td class="tbl1">{$pref.pref_currency_sign}{$master_invoice.inv_it_tax_amount}</td>
				<td class="tbl1">{$pref.pref_currency_sign}{$master_invoice.inv_it_total}</td>
			</tr>
                <tr>
                        <td class="tbl1-left"></td><td class="tbl1-right" colspan="5">
                                                <table width="100%">
                                                        <tr>
			{php}
                $itemised_line .= inv_itemised_cf($product_cf["custom_field_label1"], $product[custom_field1]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $itemised_line .= inv_itemised_cf($product_cf[custom_field_label2], $product[custom_field2]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $itemised_line .= inv_itemised_cf($product_cf[custom_field_label3], $product[custom_field3]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $itemised_line .= inv_itemised_cf($product_cf[custom_field_label4], $product[custom_field4]);
                $inv_it_tr++;
                $itemised_line .= do_tr($inv_it_tr,'blank-class');
                $inv_it_tr = 0;
                echo $itemised_line;
               {/php}

                                                        </tr>
                                                </table>
                                </td>
                 </tr>
             	{/foreach}
             	
	{/if}

	{if $smarty.get.invoice_style === 'Consulting' }
				<tr class="tbl1 col1">
			<td class="tbl1"><b>{$LANG.quantity_short}</b></td>
			<td class="tbl1"><b>{$LANG.item}</b></td>
			<td class="tbl1"><b>{$LANG.unit_price}</b></td>
			<td class="tbl1"><b>{$LANG.gross_total}</b></td><td class="tbl1"><b>{$LANG.tax}</b></td>
			<td align="right" class="tbl1"><b>{$LANG.total_uppercase}</b></td>
		</tr>
		
			{foreach from=$master_invoices item=master_invoice}
	
	
				<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left" >{$master_invoice.inv_it_quantity_formatted}</td>
				<td>{$product.description}</td><td class="tbl1-right" colspan="5"></td>
			</tr>
			
                <tr>       
                        <td class="tbl1-left"></td><td class="tbl1-right" colspan="6">
                                                <table width="100%">
                                                        <tr>
				{php}

                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label1], $product[custom_field1]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label2], $product[custom_field2]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label3], $product[custom_field3]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $consulting_line .= inv_itemised_cf($product_cf[custom_field_label4], $product[custom_field4]);
                $inv_it_tr++;
                $consulting_line .= do_tr($inv_it_tr,'blank-class');
                $inv_it_tr = 0;
                echo $consulting_line;
                {/php}
                                                        </tr>
                                                </table>
                                </td>
                 </tr>
	
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left"></td>
				<td class="tbl1-right" colspan=6><i>{$LANG.description}: </i>{$master_invoice.inv_it_description}</td>
			</tr>
			<tr class="tbl1-left tbl1-right tbl1-bottom">
				<td class="tbl1-left tbl1-bottom" ></td>
				<td class="tbl1-bottom"></td>
				<td class="tbl1-bottom">{$pref.pref_currency_sign}{$master_invoice.inv_it_unit_price}</td>
				<td class="tbl1-bottom">{$pref.pref_currency_sign}{$master_invoice.inv_it_gross_total}</td>
				<td class="tbl1-bottom ">{$pref.pref_currency_sign}{$master_invoice.inv_it_tax_amount}</td>
				<td align=right colspan=2 class="tbl1-right tbl1-bottom">{$pref.pref_currency_sign}{$master_invoice.inv_it_total}</td>
			</tr>
			{/foreach}
			
			
	{/if}
	
	{if $smarty.get.invoice_style === 'Total' }
		                <table class="left" width="100%">

                <tr class="tbl1 col1" >
                        <td class="tbl1 col1 tbl1-right" colspan="6"><b>{$LANG.description}</b></td>
                </tr>
                
          {foreach from=$master_invoices item=master_invoice}

			                <tr class="tbl1-left tbl1-right">
                        <td class="tbl1-left tbl1-right\" colspan=6>{$master_invoice.inv_it_description}</td>
                </tr>
                <tr class="tbl1-left tbl1-right">
                        <td colspan=6 class="tbl1-left tbl1-right"><br></td>
                </tr>

		{/foreach}
	{/if}
	






{if ($smarty.get.invoice_style === 'Itemised' && $invoice.note != "") || ($smarty.get.invoice_style === 'Consulting' && $invoice.note != "" )  }

		<tr>
			<td class="tbl1-left tbl1-right" colspan="7"><br></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7" align="left"><b>{$LANG.notes}:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7">{$invoice.note}</td>
		</tr>

{/if}


	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left tbl1-right" colspan="6" ><br></td>
	</tr>
	
	{if $smarty.get.invoice_style === 'Total' }
		<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left" colspan="3"></td>
		<td align="right" colspan="2">{$LANG.gross_total}</td>
		<td align="right" class="tbl1-right" >{$pref.pref_currency_sign}{$master_invoice.inv_it_gross_total}</td>
	</tr>
	{/if}
	
	
		<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left" colspan="3"></td>
		<td align="right" colspan="2">{$LANG.tax_total}</td>
		<td align="right" class="tbl1-right" >{$pref.pref_currency_sign}{$tax.total_tax}</td>
	</tr>
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left tbl1-right" colspan="6" ><br></td>
	</tr>
		<tr class="tbl1-left tbl1-right tbl1-bottom">
		<td class="tbl1-left tbl1-bottom" colspan="3"></td>
		<td class="tbl1-bottom" align=right colspan=2><b>{$pref.pref_inv_wording} {$LANG.amount}</b></td>
		<td  class="tbl1-bottom tbl1-right" align=right><u>{$pref.pref_currency_sign}{$invoice_total.total}</u></td>
	</tr>
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>
	
		<!-- invoice details section - start -->
	<tr>
		<td class="tbl1 col1" colspan="6"><b>{$pref.pref_inv_detail_heading}</b></td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan=6><i>{$pref.pref_inv_detail_line}</i></td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan=6>{$pref.pref_inv_payment_method}</td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan=6>{$pref.pref_inv_payment_line1_name} {$pref.pref_inv_payment_line1_value}</td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-bottom tbl1-right" colspan=7>{$pref.pref_inv_payment_line2_name} {$pref.pref_inv_payment_line2_value}</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
		<tr>
		<td colspan="6"><div style="font-size:8pt;" align="center">{$biller.footer}</div></td>
	</tr>
</table>
<div id="footer"></div></div>

</body>
</html>
