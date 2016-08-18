{*
/*
* Script: save.tpl
* 	Invoice save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/
*}

{if $saved == true }
	<div class="si_message_ok">{$LANG.save_invoice_success}</div>
{else}
	<div class="si_message_error">{$LANG.save_invoice_failure}</div>
{/if}

<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=quick_view&amp;id={$id|urlencode}" />
