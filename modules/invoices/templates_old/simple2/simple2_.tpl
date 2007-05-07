<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>{$title}</title>
<body>
<br>
<div id="container">
<div id="header">

</div>

<link rel="stylesheet" type="text/css" href="./modules/invoices/templates/{$template}/{$template}.css">


	<table width="100%" align="center">
			<tr>
	   				<td colspan="5"><img src="{$logo}" border="0" hspace="0" align="left"></td><th align=right><span class="font1">{$pref.pref_inv_heading}</span></th>
			</tr>
			<tr>
					<td colspan=6><hr size="1"></td>
			</tr>
	</table>
	
	<table class="right">
		<tr>
				<td class="col1 tbl1" colspan="4" ><b>{$pref.pref_inv_wording} {$LANG.summary}</b></td>
		</tr>
		<tr>
				<td class="tbl1-left">{$pref.pref_inv_wording} {$LANG.number_short}:</td><td class="tbl1-right" colspan=3>{$invoice.inv_id}</td>
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
                {if $biller.street_address != null}
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>{$biller.street_address2}</td>
                </tr>   
                {/if}
        {/if}


	
	 <tr><td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$biller.city}, {$biller.zip_code}</td></tr>                </tr>
                {if $biller.country != null }
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$biller.country}</td>
                </tr>
       			{/if}
                <tr>
                        <td class='tbl1-left'>{$LANG.phone_short}.:<td class='tbl1-right' colspan=3>{$biller.phone}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$LANG.mobile_short}.:<td class='tbl1-right' colspan=3>{$biller.mobile_phone}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$LANG.email}:<td class='tbl1-right' colspan=3>{$biller.email}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$biller.custom_field_label1}:<td class='tbl1-right' colspan=3>{$biller.custom_field1}</td>
                </tr>	<tr><td class="tbl1-top" colspan=4></td></tr>

<!-- Biller section - end -->




	<br>
		<tr>
			<td colspan=3><br /><td>
		</tr>
	<!-- Customer section - start -->
	<tr>
		<td class="tbl1-left tbl1-top tbl1-bottom col1" ><b>{$LANG.customer}:</b></td><td class="tbl1-top tbl1-bottom col1 tbl1-right" colspan=3>{$customer.c_name}</td>
	</tr>

        {if $customer.c_attention != null}
                <tr>
                        <td class='tbl1-left'>{$LANG.attention_short}:</td><td align=left class='tbl1-right' colspan=3 >{$customer.c_attention}}</td>
                </tr>
        {/if}
               {if $biller.street_address != null }
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
                {if $biller.street_address != null}
                        <td class='tbl1-left'></td><td class='tbl1-right' align=left colspan=3>{$biller.street_address2}</td>
                </tr>   
                {/if}
        {/if}


	
	 <tr><td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$customer.c_city}, {$customer.c_zip_code}</td></tr>                </tr>
                {if $customer.c_country != null }
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$customer.c_country}</td>
                </tr>
       			{/if}
                <tr>
                        <td class='tbl1-left'>{$LANG.phone_short}.:<td class='tbl1-right' colspan=3>{$customer.c_phone}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$LANG.mobile_short}.:<td class='tbl1-right' colspan=3>{$customer.c_mobile_phone}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$LANG.email}:<td class='tbl1-right' colspan=3>{$customer.c_email}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$customer.custom_field_label1}:<td class='tbl1-right' colspan=3>{$customer.c_custom_field1}</td>
                </tr>	<tr><td class="tbl1-top" colspan=4></td></tr>


<!-- Customer -->


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
	{/if}

	{if $smarty.get.invoice_style === 'Consulting' }
		<tr class="tbl1 col1">
			<td class="tbl1"><b>{$LANG.quantity_short}</b></td>
			<td class="tbl1"><b>{$LANG.item}</b></td>
			<td class="tbl1"><b>{$LANG.unit_price}</b></td>
			<td class="tbl1"><b>{$LANG.gross_total}</b></td><td class="tbl1"><b>{$LANG.tax}</b></td>
			<td align="right" class="tbl1"><b>{$LANG.total_uppercase}</b></td>
		</tr>
	{/if}
	
	{if $smarty.get.invoice_style === 'Total' }
		                <table class="left" width="100%">
		<!--
                <tr>
                        <td colspan="6"><br></td>
                </td>
		-->
                <tr class="tbl1 col1" >
                        <td class="tbl1 col1 tbl1-right" colspan="6"><b>{$LANG.description}</b></td>
                </tr>
	{/if}
	
	{foreach from=$master_invoices item=master_invoice}
		{if $smarty.get.invoice_style === 'Itemised' }
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

                                                        </tr>
                                                </table>
                                </td>
                 </tr>

		{/if}

		{if $smarty.get.invoice_style === 'Consulting' }
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left" >{$master_invoice.inv_it_quantity_formatted}</td>
				<td>{$product.description}</td><td class="tbl1-right" colspan="5"></td>
			</tr>
			
                <tr>       
                        <td class="tbl1-left"></td><td class="tbl1-right" colspan="6">
                                                <table width="100%">
                                                        <tr>
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
		{/if}
	
		{if $smarty.get.invoice_style === 'Total' }
			                <tr class="tbl1-left tbl1-right">
                        <td class="tbl1-left tbl1-right\" colspan=6>{$master_invoice.inv_it_description}</td>
                </tr>
                <tr class="tbl1-left tbl1-right">
                        <td colspan=6 class="tbl1-left tbl1-right"><br></td>
                </tr>
		{/if}
	
	{/foreach}
                

	{if ( ($smarty.get.invoice_style === 'Itemised' && $invoice.inv_note) || ($smarty.get.invoice_style === 'Consulting' && $invoice.inv_note != null ) ) }

		<tr>
			<td class="tbl1-left tbl1-right" colspan="7"><br></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7" align="left"><b>{$LANG.notes}:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="7">{$invoice.inv_note}</td>
		</tr>
	{/if}
	
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left tbl1-right" colspan="6" ><br></td>
	</tr>
	
	{if $smarty.get.invoice_style=== 'Total'}
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
