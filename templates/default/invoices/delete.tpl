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

Delete Invoice
<hr></hr>

<br>

        {if $invoicePaid == 0}
			Are you sure you want to delete {$preference.pref_inv_wording} {$invoice.id}
<br>
<br>
	<hr></hr>
	<form name="frmpost" ACTION="index.php?module=invoices&view=email&stage=2&invoice={$smarty.get.invoice}" METHOD="post">
		<input type="submit" name="submit" value="I'm sure"> <input type=button value="Cancel" onCLick="history.back()">
		<input type="hidden" name="op" value="delete_invoice">
	</form>	
        {/if}

        {if $invoicePaid != 0}
			{$preference.pref_inv_wording} {$invoice.id} can not be deleted as it has payments of {$preference.pref_currency_sign}{$invoicePaid} recorded against it
			<br>
			Add help section here!!
			<br>
<hr></hr>
		<form>
				<input type=button value="Back" onCLick="history.back()">
	</form>	
        {/if}


	</table>


