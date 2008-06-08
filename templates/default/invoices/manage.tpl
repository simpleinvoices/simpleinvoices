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

 {include file='../modules/invoices/manage.js.php'}

<div style="text-align:center;">
<b>{$LANG.manage_invoices}</b> :: {$LANG.add_new_invoice} &ndash
<a href="index.php?module=invoices&view=total"> {$LANG.total_style}</a> :: 
<a href="index.php?module=invoices&view=itemised"> {$LANG.itemised_style}</a> :: 
<a href="index.php?module=invoices&view=consulting"> {$LANG.consulting_style}</a>
</div>
<hr />
{if $invoices == null }
<p><em>{$LANG.no_invoices}.</em></p>
{else}

<div id="manageInvoicesGrid"></div>

{/if}
