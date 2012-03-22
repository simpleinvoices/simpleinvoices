<div class="align_center">
	<br />


	<!--Actions heading - start-->
	<span class="welcome">
			<a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$payment.id|htmlsafe}" href="index.php?module=export&amp;view=payment&amp;id={$payment.id|urlencode}&amp;format=print" target="_blank"><img src='{$baseUrl}images/common/printer.png' class='action' />&nbsp;{$LANG.print_preview}</a>
			 &nbsp;&nbsp; 
			 <!-- EXPORT TO PDF -->
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$payment.id|htmlsafe} {$LANG.export_pdf_tooltip}" href="index.php?module=export&amp;view=payment&amp;id={$payment.id}&amp;format=pdf"><img src='{$baseUrl}images/common/page_white_acrobat.png' class='action' />&nbsp;{$LANG.export_pdf}</a>
	</span>
</div>
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
		        <img src="{$baseUrl}images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>

