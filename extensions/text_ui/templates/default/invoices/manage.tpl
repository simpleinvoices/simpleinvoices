{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $number_of_invoices.count == 0}
	<p><em>{$LANG.no_invoices}</em></p>
{else}

<b>{$LANG.manage_invoices}</b>
<table id="manageGrid" style="display:none"></table>

 {include file='../extensions/text_ui/modules/invoices/manage.js.php'}
{/if}
