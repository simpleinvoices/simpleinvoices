{*
/*
* Script: quick_view.tpl
* 	 Quick view of invoice template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}



<br>

        {if $invoicePayments < 0}
You can delete this invoices
{$LANG.quick_view_of} {$preference.pref_inv_wording} {$invoice.id}
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels.biller_cf2}:</td><td class='details_screen' colspan=5>{$biller.custom_field2}</td>
	</tr>	
        {/if}

        {if $invoicePayments > 0}
You cant delete this invoice
	<tr class='details_screen biller'>
		<td class='details_screen'>{$customFieldLabels.biller_cf2}:</td><td class='details_screen' colspan=5>{$biller.custom_field2}</td>
	</tr>	
        {/if}


	</table>

<hr></hr>
	<form>
		<input type=button value="{$LANG.cancel}" onCLick="history.back()">
	</form>
