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
<h1 class="title"><a href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a> <span>/</span>  {$LANG.save}</h1>
{if $saved == true }
	<div class="si_message_ok"><span class="glyphicon glyphicon-floppy-saved"></span> {$LANG.save_customer_success}</div>
{else}
	<div class="si_message_error"><span class="glyphicon glyphicon-floppy-remove"></span> {$LANG.save_customer_failure}</div>
{/if}


{if $smarty.post.cancel == null }
	<meta http-equiv="refresh" content="2;url=index.php?module=customers&amp;view=manage" />
{else}
	<meta http-equiv="refresh" content="0;url=index.php?module=customers&amp;view=manage" />
{/if}
