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
                

	{if ( $invoice.inv_note != null  ) }

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
