{*
/*
* Script: save.tpl
* 	User save template
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
	<div class="si_message_ok">{$LANG.save_user_success}</div>
{else}
	<div class="si_message_error">{$LANG.save_user_failure}</div>
{/if}


{if $smarty.post.cancel == null }
	<meta http-equiv="refresh" content="2;URL=index.php?module=user&view=manage" />
{else}
	<meta http-equiv="refresh" content="0;URL=index.php?module=user&view=manage" />
{/if}
