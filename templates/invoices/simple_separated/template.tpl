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
		<td class="tbl1-left tbl1-top tbl1-bottom col1" ><b>{$LANG.customer}:</b></td><td class="tbl1-top tbl1-bottom col1 tbl1-right" colspan=3>{$customer.name}</td>
	</tr>

        {if $customer.attention != null}
                <tr>
                        <td class='tbl1-left'>{$LANG.attention_short}:</td><td align=left class='tbl1-right' colspan=3 >{$customer.attention}}</td>
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


	
	 <tr><td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$customer.city}, {$customer.zip_code}</td></tr>                </tr>
                {if $customer.country != null }
                </tr>
                <tr>
                        <td class='tbl1-left'></td><td class='tbl1-right' colspan=3>{$customer.country}</td>
                </tr>
       			{/if}
                <tr>
                        <td class='tbl1-left'>{$LANG.phone_short}.:<td class='tbl1-right' colspan=3>{$customer.phone}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$LANG.mobile_short}.:<td class='tbl1-right' colspan=3>{$customer.mobile_phone}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$LANG.email}:<td class='tbl1-right' colspan=3>{$customer.email}</td>
                </tr>
                <tr>
                        <td class='tbl1-left'>{$customer.custom_field_label1}:<td class='tbl1-right' colspan=3>{$customer.custom_field1}</td>
                </tr>	<tr><td class="tbl1-top" colspan=4></td></tr>


<!-- Customer -->


	</table>
		<table class="left" width="100%">
		<tr>
			<td colspan="6"><br /></td>
		</tr>
		
	{if $smarty.get.invoice_style === 'Itemised' }
		{include file="$template_path/itemised.tpl"}
	{/if}

	{if $smarty.get.invoice_style === 'Consulting' }
		{include file=""$template_path/consulting.tpl"}
	{/if}
	
	{if $smarty.get.invoice_style === 'Total' }
		{include file="$template_path/total.tpl"}
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
