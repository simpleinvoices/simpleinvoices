<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>{$title}</title>
<body>
<br>
<div id="container">
<div id="header">

</div>
{$invoiceItems[0].product.description}


	<table width="100%" align="center">
			<tr>
	   				<td colspan="5"><img src="{$logo}" border="0" hspace="0" align="left"></td><th align=right><span >{$preference.pref_inv_heading}</span></th>
			</tr>
			<tr>
					<td colspan=6><hr size="1"></td>
			</tr>
	</table>
	
	<table >
		<tr>
				<td colspan="4" ><b>{$preference.pref_inv_wording} {$LANG.summary}</b></td>
		</tr>
		<tr>
				<td >{$preference.pref_inv_wording} {$LANG.number_short}:</td><td colspan=3>{$invoice.id}</td>
		</tr>
		<tr>
				<td nowrap >{$preference.pref_inv_wording} {$LANG.date}:</td><td colspan=3>{$invoice.date}</td>
		</tr>
	<!-- Show the Invoice Custom Fields if valid -->

		{$show.custom_field1}
		{$show.custom_field2}
		{$show.custom_field3}
		{$show.custom_field4}

		<tr>
				<td >{$LANG.total}: </td><td colspan=3>{$preference.pref_currency_sign}{$invoice.total_format}</td>
		</tr>
		<tr>
				<td >{$LANG.paid}:</td><td colspan=3 >{$preference.pref_currency_sign}{$invoice.paid_format}</td>
		</tr>
		<tr>
				<td nowrap >{$LANG.owing}:</td><td colspan=3 >{$preference.pref_currency_sign}{$invoice.owing}</td>
		</tr>


	</table>
	<!-- Summary - end -->
	
	
	<table >
	

        <!-- Biller section - start -->
	<table >
        <tr>
                <td  border=1 cellpadding=2 cellspacing=1><b>{$LANG.biller}:</b></td><td border=1 cellpadding=2 cellspacing=1 colspan=3>{$biller.name}</td>
        </tr> 

        {if $biller.street_address != null}
                <tr>
                     <td >{$LANG.address}:</td><td  align=left colspan=3>{$biller.street_address}</td>
                </tr>   
        {/if}
        
        {if $biller.street_address2 != null }

                <tr >

                {if $biller.street_address == null }
                        <td >{$LANG.address}:</td><td align=left colspan=3>{$biller.street_address2}</td>
                </tr>   
                {/if}
                {if $biller.street_address != null}
                        <td ></td><td align=left colspan=3>{$biller.street_address2}</td>
                </tr>   
                {/if}
        {/if}


	
	 <tr><td ></td><td colspan=3>{$biller.city}, {$biller.zip_code}</td></tr>                </tr>
                {if $biller.country != null }
                </tr>
                <tr>
                        <td ></td><td colspan=3>{$biller.country}</td>
                </tr>
   		{/if}
                {if $biller.phone != null }
                <tr>
                        <td >{$LANG.phone_short}.:<td colspan=3>{$biller.phone}</td>
                </tr>
   		{/if}
                {if $biller.mobile_phone != null }
                <tr>
                        <td >{$LANG.mobile_short}.:<td colspan=3>{$biller.mobile_phone}</td>
                </tr>
   		{/if}
                {if $biller.email != null }
                <tr>
                        <td >{$LANG.email}:<td colspan=3>{$biller.email}</td>
                </tr>
		{/if}
        		{if $biller.custom_field1 != null }
                <tr>
                        <td >{$biller.custom_field_label1}:<td colspan=3>{$biller.custom_field1}</td>
                </tr>	
				{/if}
        		{if $biller.custom_field2 != null }
                <tr>
                        <td >{$biller.custom_field_label2}:<td  colspan=3>{$biller.custom_field2}</td>
                </tr>	
				{/if}
        		{if $biller.custom_field3 != null }
                <tr>
                        <td >{$biller.custom_field_label3}:<td  colspan=3>{$biller.custom_field3}</td>
                </tr>	
				{/if}
        		{if $biller.custom_field4 != null }
                <tr>
                        <td >{$biller.custom_field_label4}:<td  colspan=3>{$biller.custom_field4}</td>
                </tr>	
				{/if}
				<tr><td colspan=4></td></tr>

<!-- Biller section - end -->




	<br>
		<tr>
			<td colspan=3><br /><td>
		</tr>
	<!-- Customer section - start -->
	<tr>
		<td  ><b>{$LANG.customer}:</b></td><td colspan=3>{$customer.name}</td>
	</tr>

        {if $customer.attention != null}
                <tr>
                        <td >{$LANG.attention_short}:</td><td align=left  colspan=3 >{$customer.attention}</td>
                </tr>
        {/if}
               {if $customer.street_address != null }
                <tr>
                     <td >{$LANG.address}:</td><td  align=left colspan=3>{$customer.street_address}</td>
                </tr>   
        {/if}
        
        {if $customer.street_address2 != null }

                <tr >

                {if $customer.street_address == null }
                        <td >{$LANG.address}:</td><td  align=left colspan=3>{$customer.street_address2}</td>
                </tr>   
                {/if}
                {if $customer.street_address != null}
                        <td ></td><td  align=left colspan=3>{$customer.street_address2}</td>
                </tr>   
                {/if}
        {/if}


	
	 <tr><td ></td><td  colspan=3>{$customer.city}, {$customer.zip_code}</td></tr>                </tr>
                {if $customer.country != null }
                </tr>
                <tr>
                        <td ></td><td  colspan=3>{$customer.country}</td>
                </tr>
       			{/if}
                <tr>
                        <td >{$LANG.phone_short}.:<td  colspan=3>{$customer.phone}</td>
                </tr>
                <tr>
                        <td >{$LANG.mobile_short}.:<td  colspan=3>{$customer.mobile_phone}</td>
                </tr>
                <tr>
                        <td >{$LANG.email}:<td  colspan=3>{$customer.email}</td>
                </tr>
        		{if $customer.custom_field1 != null }
                <tr>
                        <td >{$customer.custom_field_label1}:<td  colspan=3>{$customer.custom_field1}</td>
                </tr>	
				{/if}
        		{if $customer.custom_field2 != null }
                <tr>
                        <td >{$customer.custom_field_label2}:<td  colspan=3>{$customer.custom_field2}</td>
                </tr>	
				{/if}
        		{if $customer.custom_field3 != null }
                <tr>
                        <td >{$customer.custom_field_label3}:<td  colspan=3>{$customer.custom_field3}</td>
                </tr>	
				{/if}
        		{if $customer.custom_field4 != null }
                <tr>
                        <td >{$customer.custom_field_label4}:<td  colspan=3>{$customer.custom_field4}</td>
                </tr>	
				{/if}
                
				<tr><td colspan=4></td></tr>


<!-- Customer -->


	</table>
		<table width="100%">
		<tr>
			<td colspan="6"><br /></td>
		</tr>
		
	{if $smarty.get.invoice_style === 'Itemised' }
		{include file="$template_path/itemised.tpl"}
	{/if}

	{if $smarty.get.invoice_style === 'Consulting' }
		{include file="$template_path/consulting.tpl"}
	{/if}
	
	{if $smarty.get.invoice_style === 'Total' }
		{include file="$template_path/total.tpl"}
	{/if}
	


	<tr >
		<td colspan="3"></td>
		<td align="right" colspan="2">{$LANG.tax_total}</td>
		<td align="right" >{$preference.pref_currency_sign}{$invoiceItems.total_tax}</td>
	</tr>
	<tr >
		<td colspan="6" ><br></td>
	</tr>
		<tr >
		<td colspan="3"></td>
		<td align=right colspan=2><b>{$preference.pref_inv_wording} {$LANG.amount}</b></td>
		<td  align=right><u>{$preference.pref_currency_sign}{$invoiceItems.total}</u></td>
	</tr>
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>
	
		<!-- invoice details section - start -->
	<tr>
		<td colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
	</tr>
	<tr>
		<td colspan=6><i>{$preference.pref_inv_detail_line}</i></td>
	</tr>
	<tr>
		<td colspan=6>{$preference.pref_inv_payment_method}</td>
	</tr>
	<tr>
		<td colspan=6>{$preference.pref_inv_payment_line1_name} {$preference.pref_inv_payment_line1_value}</td>
	</tr>
	<tr>
		<td colspan=7>{$preference.pref_inv_payment_line2_name} {$preference.pref_inv_payment_line2_value}</td>
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
