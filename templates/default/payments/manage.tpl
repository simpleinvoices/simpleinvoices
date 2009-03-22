{*
/*
* Script: manage.tpl
* 	 Payments manage template
*
*
* Last edited:
* 	 2008-09-01
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $payments == null}
	<p><em>{$LANG.no_payments}.</em></p>
{else}

 
	{if $smarty.get.id }
<h3>{$LANG.payments_filtered} {$smarty.get.id|escape:html}</h3> :: <a href='index.php?module=payments&amp;view=process&amp;submit={$_GET.id}&amp;op=pay_selected_invoice'>{$LANG.payments_filtered_invoice}</a>
	{elseif $smarty.get.c_id }
<h3>{$LANG.payments_filtered_customer} {$smarty.get.c_id|escape:html} :: <a href='index.php?module=payments&amp;view=process&amp;op=pay_invoice'>{$LANG.process_payment}</a></h3>
	{else}
<h3>{$LANG.manage_payments} :: <a href='index.php?module=payments&amp;view=process&amp;op=pay_invoice'>{$LANG.process_payment}</a></h3>
	{/if}
<hr />

<table id="manageGrid" style="display:none"></table>

 {include file='../modules/payments/manage.js.php' get=$smarty.get}

{/if}
<br />
<div style="text-align:center;">
<a class="cluetip" href="#"	rel="docs.php?t=help&amp;p=wheres_the_edit_button" title="{$LANG.wheres_the_edit_button}"><img src="./images/common/help-small.png" alt="" /> Wheres the Edit button?</a>
</div>
