
			<tr>
				<td class="tbl1 col1" ><b>{$LANG.quantity_short}</b></td>
				<td colspan="3" class="tbl1 col1" ><b>{$LANG.item}</b></td>
				<td class="tbl1 col1" ><b>{$LANG.Unit_Cost}</b></td>
				<td class="tbl1 col1" align="right"><b>{$LANG.Price}</b></td>
			</tr>

	
	{foreach from=$invoiceItems item=invoiceItem}
			<tr>
				<td>{$invoiceItem.quantity|siLocal_number_trim}</td>
				<td colspan="3">{$invoiceItem.product.description|htmlsafe}</td>
				<td>{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.unit_price|siLocal_number}</td>
				<td align="right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.gross_total|siLocal_number}</td>
			</tr>
			{if $invoiceItem.description != null}
				<tr >
					<td ></td>
					<td colspan="5">{$LANG.description}: {$invoiceItem.description|htmlsafe}</td>
				</tr>
			{/if}
			
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

	{if $invoice.note }

		<tr>
			<td class="tbl1-left tbl1-right" colspan="6"><br /></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6" align="left"><b>{$LANG.notes}:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6">{$invoice.note|outhtml}</td>
		</tr>
	{/if}
	
		<tr class="tbl1-left tbl1-right">
			<td class="tbl1-left tbl1-right" colspan="6" ><br /></td>
		</tr>
