
			<tr>
				<td class="tbl1 col1" ><b>{{ $LANG['quantity_short'] ?? '' }}</b></td>
				<td colspan="3" class="tbl1 col1" ><b>{{ $LANG['item'] ?? '' }}</b></td>
				<td class="tbl1 col1" ><b>{{ $LANG['unit_cost'] ?? '' }}</b></td>
				<td class="tbl1 col1" align="right"><b>{{ $LANG['price'] ?? '' }}</b></td>
			</tr>

	
	@foreach(($invoiceItems ?? []) as $invoiceItem)
			<tr>
				<td>{{ $invoiceItem['quantity'] ?? '' | siLocal_number_trim }}</td>
				<td colspan="3">{{ $ ?? '' }}</td>
				<td>{{ $preference['pref_currency_sign'] }} {{ $invoiceItem['unit_price'] ?? '' | siLocal_number }}</td>
				<td align="right">{{ $preference['pref_currency_sign'] }} {{ $invoiceItem['gross_total'] ?? '' | siLocal_number }}</td>
			</tr>
			@if($ != null)
				<tr >
					<td ></td>
					<td colspan="5">{{ $LANG['description'] ?? '' }}: {{ $invoiceItem['description'] ?? '' }}</td>
				</tr>
			@endif
			
			<tr>
                <td class="tbl1-left"></td>
				<td class="tbl1-right" colspan="5">
                    <table width="100%">
                        <tr>

					{inv_itemised_cf label=$customFieldLabels['product_cf1'] field=$}
					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf1'] field=$}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf1'] field=$}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf1'] field=$}
					{do_tr number=4 class="blank-class"}

                        </tr>
                    </table>
                </td>
			</tr>
	
	@endforeach

	@if(!empty($invoice['note']))

		<tr>
			<td class="tbl1-left tbl1-right" colspan="6"><br /></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6" align="left"><b>{{ $LANG['notes'] ?? '' }}:</b></td>
		</tr>
		<tr>
			<td class="tbl1-left tbl1-right" colspan="6">{{ $invoice['note'] ?? '' | outhtml }}</td>
		</tr>
	@endif
	
		<tr class="tbl1-left tbl1-right">
			<td class="tbl1-left tbl1-right" colspan="6" ><br /></td>
		</tr>
