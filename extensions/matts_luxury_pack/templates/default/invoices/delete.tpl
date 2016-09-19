{*
/*
* Script: ./extensions/matts_luxury_pack/templates/default/invoices/delete.tpl
* 	 delete an invoice template
*
* Authors:
*	 git0matt@gmail.com, Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2016-09-06
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $smarty.get.stage == 1 }

	<br />
	{if $invoicePaid == 0}
		<div class="si_message">{$LANG.confirm_delete} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}</div>
	<br />
	<br />
	<form name="frmpost" action="index.php?module=invoices&amp;view=delete&amp;stage=2&amp;id={$smarty.get.id|urlencode}" method="post">
		<input type="hidden" name="doDelete" value="y" />
		<div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="Save">
                <img class="button_img" src="./images/common/tick.png" alt="tick" />
                {$LANG.yes}
            </button>

            <a href="./index.php?module=invoices&amp;view=manage" class="negative">
                <img src="./images/common/cross.png" alt="cross" />
                {$LANG.cancel}
            </a>
		</div>
	</form>
	{/if}
	
	{if $invoicePaid != 0}
	<span class="welcome">
		<div class="si_message_error">{$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe} {$LANG.delete_has_payments1} {$preference.pref_currency_sign} {$invoicePaid|siLocal_number} {$LANG.delete_has_payments2}</div>
	</span>
	<br />
		{* LANG_TODO: Add help section here!! *}
	<br />
	{/if}

{/if}

{if $smarty.get.stage == 2}

	<div id="top"></b></div>
	<br /><br />
		<div class="si_message_ok">{$preference.pref_inv_wording|htmlsafe} {$id|htmlsafe} {$LANG.deleted}</div>
	<br /><br />
{	if $smarty.post.cancel == null}
	<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&view=manage" />
{	else}
	<meta http-equiv="refresh" content="0;URL=index.php?module=invoices&view=manage" />
{	/if}

{/if}
