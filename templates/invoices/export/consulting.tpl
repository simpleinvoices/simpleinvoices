		<tr class="tbl1 col1">
			<td class="tbl1"><b>{$LANG.quantity_short}</b></td>
			<td class="tbl1"><b>{$LANG.item}</b></td>
			<td class="tbl1"><b>{$LANG.unit_price}</b></td>
			<td class="tbl1"><b>{$LANG.gross_total}</b></td><td class="tbl1"><b>{$LANG.tax}</b></td>
			<td align="right" class="tbl1"><b>{$LANG.total_uppercase}</b></td>
		</tr>

	
	{foreach from=$invoiceItems item=invoiceItem}

			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left" >{$invoiceItem.quantity_formatted}</td>
				<td>{$invoiceItem.product.description}</td><td class="tbl1-right" colspan="5"></td>
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
				<td class="tbl1-right" colspan=6><i>{$LANG.description}: </i>{$invoiceItem.description}</td>
			</tr>
			<tr>
	                        <td class="tbl1-left"></td><td class="tbl1-right" colspan="5">
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


			<tr class="tbl1-left tbl1-right tbl1-bottom">
				<td class="tbl1-left tbl1-bottom" ></td>
				<td class="tbl1-bottom"></td>
				<td class="tbl1-bottom">{$preference.pref_currency_sign}{$invoiceItem.unit_price}</td>
				<td class="tbl1-bottom">{$preference.pref_currency_sign}{$invoiceItem.gross_total}</td>
				<td class="tbl1-bottom ">{$preference.pref_currency_sign}{$invoiceItem.tax_amount}</td>
				<td align=right colspan=2 class="tbl1-right tbl1-bottom">{$preference.pref_currency_sign}{$invoiceItem.total}</td>
			</tr>
	
	{/foreach}
                

	{if ( $invoice.note != null  ) }

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
