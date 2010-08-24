		<tr class="tbl1 col1">
			<td class="tbl1"><b>{$LANG.quantity_short}</b></td>
			<td colspan="3" class="tbl1"><b>{$LANG.item}</b></td>
			<td class="tbl1"><b>{$LANG.Unit_Cost}</b></td>
			<td class="tbl1" align="right"><b>{$LANG.Price}</b></td>
		</tr>

	
	{foreach from=$invoiceItems item=invoiceItem}

			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left">{$invoiceItem.quantity|siLocal_number_trim}</td>
				<td>{$invoiceItem.product.description|htmlsafe}</td>
				<td class="tbl1-right" colspan="4"></td>
			</tr>
			
            <tr>       
                <td class="tbl1-left"></td>
				<td class="tbl1-right" colspan="5">
                    <table width="100%">
                        <tr>
							<td> </td>
                        </tr>
                    </table>
                </td>
            </tr>

	
			<tr class="tbl1-left tbl1-right">
				<td class="tbl1-left"></td>
				<td class="tbl1-right" colspan="5"><i>{$LANG.description}: </i>{$invoiceItem.description|htmlsafe}</td>
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


			<tr class="tbl1-left tbl1-right tbl1-bottom">
				<td class="tbl1-left tbl1-bottom" ></td>
				<td colspan="3" class="tbl1-bottom"></td>
				<td class="tbl1-bottom">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.unit_price|siLocal_number}</td>
				<td class="tbl1-right tbl1-bottom" align="right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.total|siLocal_number}</td>
			</tr>
	
	{/foreach}

	{if ( $invoice.note != null  ) }

		<tr>
			<td class="tbl1-left tbl1-right" colspan="6"><br /></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6" align="left"><b>{$LANG.notes}:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6">{$invoice.note|unescape}</td>
		</tr>
	{/if}
	
		<tr class="tbl1-left tbl1-right">
			<td class="tbl1-left tbl1-right" colspan="6" ><br /></td>
		</tr>
{php}   global $invoice; $this->assign('invoice_gross_total', $invoice[total] - $invoice[total_tax]); {/php}
		<tr>
			<td colspan="3"></td>
			<td align="right" colspan="2">{$LANG.gross_total}</td>
			<td align="right">{$preference.pref_currency_sign|htmlsafe}{$invoice_gross_total|siLocal_number}</td>
		</tr>	
