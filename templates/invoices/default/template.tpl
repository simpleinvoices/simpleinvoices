<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="{$css}">
<title>{$preference.pref_inv_wording} {$LANG.number_short}: {$invoice.id}</title>
</head>
<body>
<br>
<div id="container">
	<div id="header">
	</div>

	<table width="100%" align="center">
		<tr>
			<td colspan="5"><img src="{$logo}" border="0" hspace="0" align="left"></td>
			<th align="right"><span class="font1">{$preference.pref_inv_heading}</span></th>
		</tr>
		<tr>
			<td colspan="6"><hr size="1"></td>
		</tr>
	</table>
	
	<!-- Summary - start -->

	<table class="right">
		<tr>
				<td class="col1 tbl1" colspan="4" ><b>{$preference.pref_inv_wording} {$LANG.summary}</b></td>
		</tr>
		<tr>
				<td class="tbl1-left">{$preference.pref_inv_wording} {$LANG.number_short}:</td>
				<td class="tbl1-right" colspan="3">{$invoice.id}</td>
		</tr>
		<tr>
				<td nowrap class="tbl1-left">{$preference.pref_inv_wording} {$LANG.date}:</td>
				<td class="tbl1-right" colspan="3">{$invoice.date}</td>
		</tr>
	<!-- Show the Invoice Custom Fields if valid -->
		{ if $invoice.custom_field1 != null}
		<tr>
				<td nowrap class="tbl1-left">{$customFieldLabels.invoice_cf1}:</td>
				<td class="tbl1-right" colspan="3">{$invoice.custom_field1}</td>
		</tr>
		{/if}
		{ if $invoice.custom_field2 != null}
		<tr>
				<td nowrap class="tbl1-left">{$customFieldLabels.invoice_cf2}:</td>
				<td class="tbl1-right" colspan="3">{$invoice.custom_field2}</td>
		</tr>
		{/if}
		{ if $invoice.custom_field3 != null}
		<tr>
				<td nowrap class="tbl1-left">{$customFieldLabels.invoice_cf3}:</td>
				<td class="tbl1-right" colspan="3">{$invoice.custom_field3}</td>
		</tr>
		{/if}
		{ if $invoice.custom_field4 != null}
		<tr>
				<td nowrap class="tbl1-left">{$customFieldLabels.invoice_cf4}:</td>
				<td class="tbl1-right" colspan="3">{$invoice.custom_field4}</td>
		</tr>
		{/if}

		<tr>
				<td class="tbl1-left" >{$LANG.total}: </td>
				<td class="tbl1-right" colspan="3">{$preference.pref_currency_sign}{$invoice.total|number_format:2}</td>
		</tr>
		<tr>
				<td class="tbl1-left">{$LANG.paid}:</td>
				<td class="tbl1-right" colspan="3" >{$preference.pref_currency_sign}{$invoice.paid_format}</td>
		</tr>
		<tr>
				<td nowrap class="tbl1-left tbl1-bottom">{$LANG.owing}:</td>
				<td class="tbl1-right tbl1-bottom" colspan="3" >{$preference.pref_currency_sign}{$invoice.owing}</td>
		</tr>

	</table>
	<!-- Summary - end -->
	
	
	<table class="left">

    <!-- Biller section - start -->
        <tr>
                <td class="tbl1-left tbl1-bottom tbl1-top col1" border=1 cellpadding=2 cellspacing=1><b>{$LANG.biller}:</b></td>
				<td class="col1 tbl1-bottom tbl1-top tbl1-right" border=1 cellpadding=2 cellspacing=1 colspan="3">{$biller.name}</td>
        </tr> 

        {if $biller.street_address != null}
		<tr>
                <td class='tbl1-left'>{$LANG.address}:</td>
				<td class='tbl1-right' align=left colspan="3">{$biller.street_address}</td>
		</tr>
        {/if}
        {if $biller.street_address2 != null }
			{if $biller.street_address == null }
		<tr>
                <td class='tbl1-left'>{$LANG.address}:</td>
				<td class='tbl1-right' align=left colspan="3">{$biller.street_address2}</td>
		</tr>   
			{/if}
			{if $biller.street_address != null }
		<tr>
                <td class='tbl1-left'></td>
				<td class='tbl1-right' align=left colspan="3">{$biller.street_address2}</td>
        </tr>   
			{/if}
        {/if}

		{merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code street1=$biller.street_address street2=$biller.street_address2 class1="tbl1-left" class2="tbl1-right" colspan="3"}

		{if $biller.country != null }
		<tr>
				<td class='tbl1-left'></td>
				<td class='tbl1-right' colspan="3">{$biller.country}</td>
		</tr>
       	{/if}

	{print_if_not_null label=$LANG.phone_short field=$biller.phone class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$LANG.fax field=$biller.fax class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$LANG.mobile_short field=$biller.mobile_phone class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$LANG.email field=$biller.email class1='tbl1-left' class2='tbl1-right' colspan="3"}
	
	{print_if_not_null label=$customFieldLabels.biller_cf1 field=$biller.custom_field1 class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$customFieldLabels.biller_cf2 field=$biller.custom_field2 class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$customFieldLabels.biller_cf3 field=$biller.custom_field3 class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$customFieldLabels.biller_cf4 field=$biller.custom_field4 class1='tbl1-left' class2='tbl1-right' colspan="3"}

		<tr>
				<td class="tbl1-top" colspan="4"> </td>
		</tr>

	<!-- Biller section - end -->

		<tr>
			<td colspan="4"><br /></td>
		</tr>

	<!-- Customer section - start -->
	<tr>
			<td class="tbl1-left tbl1-top tbl1-bottom col1" ><b>{$LANG.customer}:</b></td>
			<td class="tbl1-top tbl1-bottom col1 tbl1-right" colspan="3">{$customer.name}</td>
	</tr>

        {if $customer.attention != null }
    <tr>
            <td class='tbl1-left'>{$LANG.attention_short}:</td>
			<td align=left class='tbl1-right' colspan="3" >{$customer.attention}</td>
                </tr>
       {/if}
        {if $customer.street_address != null }
    <tr >
            <td class='tbl1-left'>{$LANG.address}:</td>
			<td class='tbl1-right' align=left colspan="3">{$customer.street_address}</td>
    </tr>   
        {/if}
        {if $customer.street_address2 != null}
                {if $customer.street_address == null}
    <tr>
            <td class='tbl1-left'>{$LANG.address}:</td>
			<td class='tbl1-right' align=left colspan="3">{$customer.street_address2}</td>
    </tr>   
                {/if}
                {if $customer.street_address != null}
    <tr>
			<td class='tbl1-left'></td>
			<td class='tbl1-right' align=left colspan="3">{$customer.street_address2}</td>
    </tr>   
                {/if}
        {/if}
		
		{merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code street1=$customer.street_address street2=$customer.street_addtess2 class1="tbl1-left" class2="tbl1-right" colspan="3"}

         {if $customer.country != null}
    <tr>
            <td class='tbl1-left'></td>
			<td class='tbl1-right' colspan="3">{$customer.country}</td>
    </tr>
        {/if}

	{print_if_not_null label=$LANG.phone_short field=$customer.phone class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$LANG.fax field=$customer.fax class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$LANG.mobile_short field=$customer.mobile_phone class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$LANG.email field=$customer.email class1='tbl1-left' class2='tbl1-right' colspan="3"}
	
	{print_if_not_null label=$customFieldLabels.customer_cf1 field=$customer.custom_field1 class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$customFieldLabels.customer_cf2 field=$customer.custom_field2 class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$customFieldLabels.customer_cf3 field=$customer.custom_field3 class1='tbl1-left' class2='tbl1-right' colspan="3"}
	{print_if_not_null label=$customFieldLabels.customer_cf4 field=$customer.custom_field4 class1='tbl1-left' class2='tbl1-right' colspan="3"}

		<tr>
			<td class="tbl1-top" colspan="4"></td>
		</tr>
	</table>

	<!-- Customer section - end -->

	<table class="left" width="100%">
		<tr>
			<td colspan="6"><br /></td>
		</tr>

	{if $invoice.type_id == 2 }
					<tr>
				<td class="tbl1 col1"><b>{$LANG.quantity_short}</b></td>
				<td class="tbl1 col1"><b>{$LANG.description}</b></td>
				<td class="tbl1 col1"><b>{$LANG.unit_price}</b></td>
				<td class="tbl1 col1"><b>{$LANG.gross_total}</b></td>
				<td class="tbl1 col1"><b>{$LANG.tax}</b></td>
				<td class="tbl1 col1" align="right"><b>{$LANG.total_uppercase}</b></td>
			</tr>
			
				{foreach from=$invoiceItems item=invoiceItem}

			<tr class="tbl1" >
				<td class="tbl1">{$invoiceItem.quantity_formatted}</td>
				<td class="tbl1">{$invoiceItem.product.description}</td>
				<td class="tbl1">{$preference.pref_currency_sign}{$invoiceItem.unit_price}</td>
				<td class="tbl1">{$preference.pref_currency_sign}{$invoiceItem.gross_total}</td>
				<td class="tbl1">{$preference.pref_currency_sign}{$invoiceItem.tax_amount}</td>
				<td class="tbl1" align="right">{$preference.pref_currency_sign}{$invoiceItem.total}</td>
			</tr>
            <tr>
                <td class="tbl1-left"></td>
				<td class="tbl1-right" colspan="5">
					<table width="100%">
						<tr>

					{inv_itemised_cf label=$customFieldLabels.product_cf1 field=$invoiceItem.product.custom_field1}
					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf2 field=$invoiceItem.product.custom_field2}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf3 field=$invoiceItem.product.custom_field3}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf4 field=$invoiceItem.product.custom_field4}
					{do_tr number=4 class="blank-class"}
 
						</tr>
					</table>
                </td>
            </tr>
             	{/foreach}
	{/if}

	{if $invoice.type_id == 3 }
			<tr class="tbl1 col1">
				<td class="tbl1"><b>{$LANG.quantity_short}</b></td>
				<td class="tbl1"><b>{$LANG.item}</b></td>
				<td class="tbl1"><b>{$LANG.unit_price}</b></td>
				<td class="tbl1"><b>{$LANG.gross_total}</b></td>
				<td class="tbl1"><b>{$LANG.tax}</b></td>
				<td align="right" class="tbl1"><b>{$LANG.total_uppercase}</b></td>
			</tr>
		
			{foreach from=$invoiceItems item=invoiceItem}
	
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left" >{$invoiceItem.quantity_formatted}</td>
				<td>{$invoiceItem.product.description}</td>
				<td class="tbl1-right" colspan="4"></td>
			</tr>
			
            <tr>       
                <td class="tbl1-left"></td>
				<td class="tbl1-right" colspan="5">
                    <table width="100%">
                        <tr>

					{inv_itemised_cf label=$customFieldLabels.product_cf1 field=$invoiceItem.product.custom_field1}
					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf2 field=$invoiceItem.product.custom_field2}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf3 field=$invoiceItem.product.custom_field3}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf4 field=$invoiceItem.product.custom_field4}
					{do_tr number=4 class="blank-class"}

                        </tr>
                    </table>
                </td>
            </tr>
	
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left"></td>
				<td class="tbl1-right" colspan="5"><i>{$LANG.description}: </i>{$invoiceItem.description}</td>
			</tr>
			<tr class="tbl1-left tbl1-right tbl1-bottom">
				<td class="tbl1-left tbl1-bottom" ></td>
				<td class="tbl1-bottom"></td>
				<td class="tbl1-bottom">{$preference.pref_currency_sign}{$invoiceItem.unit_price}</td>
				<td class="tbl1-bottom">{$preference.pref_currency_sign}{$invoiceItem.gross_total}</td>
				<td class="tbl1-bottom ">{$preference.pref_currency_sign}{$invoiceItem.tax_amount}</td>
				<td align="right" class="tbl1-right tbl1-bottom">{$preference.pref_currency_sign}{$invoiceItem.total}</td>
			</tr>
			{/foreach}
	{/if}
	
	{if $invoice.type_id == 1 }
		    <table class="left" width="100%">

                <tr class="tbl1 col1" >
                    <td class="tbl1 col1 tbl1-right" colspan="6"><b>{$LANG.description}</b></td>
                </tr>
                
          {foreach from=$invoiceItems item= invoiceItem}

			    <tr class="tbl1-left tbl1-right">
                    <td class="tbl1-left tbl1-right" colspan="6">{$invoiceItem.description}</td>
                </tr>
                <tr class="tbl1-left tbl1-right">
                    <td colspan="6" class="tbl1-left tbl1-right"><br></td>
                </tr>

		{/foreach}
	{/if}

{if ($invoice.type_id == 2 && $invoice.note != "") || ($invoice.type_id == 3 && $invoice.note != "" )  }

		<tr>
			<td class="tbl1-left tbl1-right" colspan="6"><br></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6" align="left"><b>{$LANG.notes}:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6">{$invoice.note}</td>
		</tr>

{/if}

	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left tbl1-right" colspan="6" ><br></td>
	</tr>
	
	{if $invoice.type_id == 3}
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left" colspan="2"></td>
		<td align="right" colspan="3">{$LANG.gross_total}</td>
		<td align="right" class="tbl1-right">{$preference.pref_currency_sign}{$invoiceItem.gross_total}</td>
	</tr>
	{/if}
	
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left" colspan="2"></td>
		<td align="right" colspan="3">{$LANG.tax_total}</td>
		<td align="right" class="tbl1-right" >{$preference.pref_currency_sign}{$invoice.total_tax}</td>
	</tr>
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left tbl1-right" colspan="6" ><br></td>
	</tr>
	<tr class="tbl1-left tbl1-right tbl1-bottom">
		<td class="tbl1-left tbl1-bottom" colspan="2"></td>
		<td class="tbl1-bottom" align="right" colspan="3"><b>{$preference.pref_inv_wording} {$LANG.amount}</b></td>
		<td  class="tbl1-bottom tbl1-right" align="right"><u>{$preference.pref_currency_sign}{$invoice.total}</u></td>
	</tr>
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>
	
	<!-- invoice details section - start -->

	<tr>
		<td class="tbl1 col1" colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan="6"><i>{$preference.pref_inv_detail_line}</i></td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan="6">{$preference.pref_inv_payment_method}</td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-right" colspan="6">{$preference.pref_inv_payment_line1_name} {$preference.pref_inv_payment_line1_value}</td>
	</tr>
	<tr>
		<td class="tbl1-left tbl1-bottom tbl1-right" colspan="6">{$preference.pref_inv_payment_line2_name} {$preference.pref_inv_payment_line2_value}</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td colspan="6"><div style="font-size:8pt;" align="center">{$biller.footer}</div></td>
	</tr>

	<!-- invoice details section - end -->

</table>

<div id="footer"></div>

</div>

</body>
</html>
