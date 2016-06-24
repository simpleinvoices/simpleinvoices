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

{if $smarty.get.stage == 1 }

	<br />
    	    {if $invoicePaid == 0}
    {$LANG.confirm_delete}
    {$preference.pref_inv_wording|htmlsafe}
    {$invoice.index_id|htmlsafe}
            <br />
            <br />
    <form name="frmpost"
          action="index.php?module=invoices&amp;view=delete&amp;stage=2&amp;id={$smarty.get.id|urlencode}"
          method="post">
        <table class="center" >
            <tr>
                <td>
					<div class="si_toolbar si_toolbar_form">
						<button type="submit" class="positive" name="submit">
							<img class="button_img" src="./images/common/tick.png" alt="" /> 
							{$LANG.yes}
						</button>

						<input type="hidden" name="doDelete" value="y" />
						<a href="./index.php?module=invoices&amp;view=manage" class="negative">
							<img src="./images/common/cross.png" alt="" />
							{$LANG.cancel}
						</a>
					</div>
                </td>
            </tr>
        </table>
        </form>	
  {else}
            <span class="welcome">
        {$preference.pref_inv_wording|htmlsafe}
        {$invoice.index_id|htmlsafe}
        {$LANG.delete_has_payments1}
        {$preference.pref_currency_sign}
        {$invoicePaid|siLocal_number}
        {$LANG.delete_has_payments2}
    </span>
				<br />
				{* LANG_TODO: Add help section here!! *}
				<br />
    	    {/if}
{elseif $smarty.get.stage == 2 }
  <br />
  <br />
	{$preference.pref_inv_wording|htmlsafe} {$id|htmlsafe} {$LANG.deleted}
  <br />
  <br />
{/if}
