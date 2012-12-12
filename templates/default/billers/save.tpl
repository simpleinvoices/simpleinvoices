{*
/*
* Script: save.tpl
* 	Biller save template
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
	<div class="si_message_ok">{$LANG.save_biller_success}</div>
{else}
	<div class="si_message_error">{$LANG.save_biller_failure}</div>
{/if}


{if $smarty.post.cancel == null }
	<meta http-equiv="refresh" content="2;URL=index.php?module=billers&amp;view=manage" />
{else}
	<meta http-equiv="refresh" content="0;URL=index.php?module=billers&amp;view=manage" />
{/if}
