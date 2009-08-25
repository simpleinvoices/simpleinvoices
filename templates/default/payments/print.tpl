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
