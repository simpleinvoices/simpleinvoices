{*
/*
* Script: save.tpl
* 	 Customer save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $saved == true }
	<div class="si_message_ok">{$LANG.save_customer_success}</div>
{else}
	<div class="si_message_error">{$LANG.save_customer_failure}</div>
{/if}


{if $smarty.post.cancel == null }
	<meta http-equiv="refresh" content="2;url=index.php?module=customers&amp;view=manage" />
{else}
	<meta http-equiv="refresh" content="0;url=index.php?module=customers&amp;view=manage" />
{/if}
