<br />
<table align="center">
	<tr>
		<td class='details_screen'>{$LANG.payment_id}</td><td>{$payment.id|htmlsafe}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.invoice_id}</td><td><a href='index.php?module=invoices&amp;view=quick_view&amp;id={$payment.ac_inv_id|htmlsafe}&amp;action=view'>{$payment.ac_inv_id|htmlsafe}</a></td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.amount}</td><td>{$payment.ac_amount|siLocal_number}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.date_upper}</td><td>{$payment.date|htmlsafe}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.biller}</td><td>{$payment.biller|htmlsafe}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.customer}</td><td>{$payment.customer|htmlsafe}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.payment_type}</td><td>{$paymentType.pt_description|htmlsafe}</td>
	</tr>
	<tr>
		<td class='details_screen'>{$LANG.online_payment_id}</td><td>{$payment.online_payment_id|htmlsafe}</td>
	</tr>
        <tr>
                <td class='details_screen'>{$LANG.notes}</td><td>{$payment.ac_notes|outhtml}
        </tr>
</table>

<br />
<table class="buttons" align="center">
	<tr>
		<td>
		
			<a href="./index.php?module=payments&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>

