
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

                                                        </tr>
                                                </table>
                                </td>
                 </tr>

	
	{/foreach}
                

	{if $invoice.note }

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
