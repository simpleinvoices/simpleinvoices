<br />
<table align="center">
	<tr>
		<td class='details_screen'>{$LANG.payment_id}</td><td>{$payment.id|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.invoice_id}</td><td><a href='index.php?module=invoices&amp;view=quick_view&amp;id={$payment.ac_inv_id|escape:html}&amp;action=view'>{$payment.ac_inv_id|escape:html}</a></td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.amount}</td><td>{$payment.ac_amount|siLocal_number}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.date_upper}</td><td>{$payment.date|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.biller}</td><td>{$payment.biller|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.customer}</td><td>{$payment.customer|escape:html}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.payment_type}</td><td>{$paymentType.pt_description|escape:html}</td>
	</tr>
        <tr>
                <td class='details_screen'>{$LANG.notes}</td><td>{$payment.ac_notes}
        </tr>
</table>

<div id="container">
	<div id="header">
	</div>

	<table width="100%" align="center">
		<tr>
			<td colspan="5"><img src="{$logo}" border="0" hspace="0" align="left"></td>
			<th align="right"><span class="font1">{$preference.pref_inv_heading}</span></th>
		</tr>
		<tr>
			<td colspan="6" class="tbl1-top">&nbsp;</td>
		</tr>
	</table>
	
	<!-- Summary - start -->

	<table class="right">
		<tr>
				<td class="col1 tbl1-bottom" colspan="4" ><b>{$preference.pref_inv_wording} {$LANG.summary}</b></td>
		</tr>
		<tr>
				<td class="">{$preference.pref_inv_wording} {$LANG.number_short}:</td>
				<td class="" align="right" colspan="3">{$invoice.id}</td>
		</tr>
		<tr>
				<td nowrap class="">{$preference.pref_inv_wording} {$LANG.date}:</td>
				<td class="" align="right" colspan="3">{$invoice.date}</td>
		</tr>
	<!-- Show the Invoice Custom Fields if valid -->
		{ if $invoice.custom_field1 != null}
		<tr>
				<td nowrap class="">{$customFieldLabels.invoice_cf1}:</td>
				<td class="" align="right" colspan="3">{$invoice.custom_field1}</td>
		</tr>
		{/if}
		{ if $invoice.custom_field2 != null}
		<tr>
				<td nowrap class="">{$customFieldLabels.invoice_cf2}:</td>
				<td class="" align="right"  colspan="3">{$invoice.custom_field2}</td>
		</tr>
		{/if}
		{ if $invoice.custom_field3 != null}
		<tr>
				<td nowrap class="">{$customFieldLabels.invoice_cf3}:</td>
				<td class="" align="right" colspan="3">{$invoice.custom_field3}</td>
		</tr>
		{/if}
		{ if $invoice.custom_field4 != null}
		<tr>
				<td nowrap class="">{$customFieldLabels.invoice_cf4}:</td>
				<td class="" align="right" colspan="3">{$invoice.custom_field4}</td>
		</tr>
		{/if}

		<tr>
				<td class="" >{$LANG.total}: </td>
				<td class="" align="right" colspan="3">{$preference.pref_currency_sign}{$invoice.total|number_format:2}</td>
		</tr>
		<tr>
				<td class="">{$LANG.paid}:</td>
				<td class="" align="right" colspan="3" >{$preference.pref_currency_sign}{$invoice.paid|number_format:2}</td>
		</tr>
		<tr>
				<td nowrap class="">{$LANG.owing}:</td>
				<td class="" align="right" colspan="3" >{$preference.pref_currency_sign}{$invoice.owing|number_format:2}</td>
		</tr>

	</table>
	<!-- Summary - end -->
	
	
	<table class="left">

    <!-- Biller section - start -->
        <tr>
                <td class="tbl1-bottom col1" border=1 cellpadding=2 cellspacing=1><b>{$LANG.biller}:</b></td>
				<td class="col1 tbl1-bottom" border=1 cellpadding=2 cellspacing=1 colspan="3">{$biller.name}</td>
        </tr> 

        {if $biller.street_address != null}
		<tr>
                <td class=''>{$LANG.address}:</td>
				<td class='' align=left colspan="3">{$biller.street_address}</td>
		</tr>
        {/if}
        {if $biller.street_address2 != null }
			{if $biller.street_address == null }
		<tr>
                <td class=''>{$LANG.address}:</td>
				<td class='' align=left colspan="3">{$biller.street_address2}</td>
		</tr>   
			{/if}
			{if $biller.street_address != null }
		<tr>
                <td class=''></td>
				<td class='' align=left colspan="3">{$biller.street_address2}</td>
        </tr>   
			{/if}
        {/if}

		{merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code street1=$biller.street_address street2=$biller.street_address2 class1="" class2="" colspan="3"}

		{if $biller.country != null }
		<tr>
				<td class=''></td>
				<td class='' colspan="3">{$biller.country}</td>
		</tr>
       	{/if}

	{print_if_not_null label=$LANG.phone_short field=$biller.phone class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG.fax field=$biller.fax class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG.mobile_short field=$biller.mobile_phone class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG.email field=$biller.email class1='' class2='' colspan="3"}
	
	{print_if_not_null label=$customFieldLabels.biller_cf1 field=$biller.custom_field1 class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels.biller_cf2 field=$biller.custom_field2 class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels.biller_cf3 field=$biller.custom_field3 class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels.biller_cf4 field=$biller.custom_field4 class1='' class2='' colspan="3"}

		<tr>
				<td class="" colspan="4"> </td>
		</tr>

	<!-- Biller section - end -->

		<tr>
			<td colspan="4"><br /></td>
		</tr>

	<!-- Customer section - start -->
	<tr>
			<td class="tbl1-bottom col1" ><b>{$LANG.customer}:</b></td>
			<td class="tbl1-bottom col1" colspan="3">{$customer.name}</td>
	</tr>

        {if $customer.attention != null }
    <tr>
            <td class=''>{$LANG.attention_short}:</td>
			<td align=left class='' colspan="3" >{$customer.attention}</td>
                </tr>
       {/if}
        {if $customer.street_address != null }
    <tr >
            <td class=''>{$LANG.address}:</td>
			<td class='' align=left colspan="3">{$customer.street_address}</td>
    </tr>   
        {/if}
        {if $customer.street_address2 != null}
                {if $customer.street_address == null}
    <tr>
            <td class=''>{$LANG.address}:</td>
			<td class='' align=left colspan="3">{$customer.street_address2}</td>
    </tr>   
                {/if}
                {if $customer.street_address != null}
    <tr>
			<td class=''></td>
			<td class='' align=left colspan="3">{$customer.street_address2}</td>
    </tr>   
                {/if}
        {/if}
		
		{merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code street1=$customer.street_address street2=$customer.street_addtess2 class1="" class2="" colspan="3"}

         {if $customer.country != null}
    <tr>
            <td class=''></td>
			<td class='' colspan="3">{$customer.country}</td>
    </tr>
        {/if}

	{print_if_not_null label=$LANG.phone_short field=$customer.phone class1='' class2='t' colspan="3"}
	{print_if_not_null label=$LANG.fax field=$customer.fax class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG.mobile_short field=$customer.mobile_phone class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG.email field=$customer.email class1='' class2='' colspan="3"}
	
	{print_if_not_null label=$customFieldLabels.customer_cf1 field=$customer.custom_field1 class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels.customer_cf2 field=$customer.custom_field2 class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels.customer_cf3 field=$customer.custom_field3 class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels.customer_cf4 field=$customer.custom_field4 class1='' class2='' colspan="3"}

		<tr>
			<td class="" colspan="4"></td>
		</tr>
	</table>

	<!-- Customer section - end -->

	<table class="left" width="100%">
		<tr>
			<td colspan="6"><br /></td>
		</tr>

					<tr>
				<td class="tbl1-bottom col1"><b>{$LANG.quantity_short}</b></td>
				<td class="tbl1-bottom col1" colspan="3"><b>{$LANG.item}</b></td>
				<td class="tbl1-bottom col1" align="right"><b>{$LANG.Unit_Cost}</b></td>
				<td class="tbl1-bottom col1" align="right"><b>{$LANG.Price}</b></td>
			</tr>
			

			<tr class="" >
				<td class="">{$invoiceItem.quantity|siLocal_number_trim}</td>
				<td class="" colspan="3">{$invoiceItem.product.description}</td>
				<td class="" align="right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
				<td class="" align="right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>
			</tr>

    </table>
