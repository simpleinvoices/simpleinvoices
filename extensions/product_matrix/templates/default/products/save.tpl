{*
/*
* Script: save.tpl
* 	Biller save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/
*}

{if $saved == true }
	<br />
	 {$LANG.save_product_success}
	<br />
	<br />
{else}
	<br />
	 {$LANG.save_product_failure}
	<br />
	<br />
{/if}

{if $smarty.post.cancel == null }
	<meta http-equiv="refresh" content="2;url=index.php?module=products&amp;view=manage" />
{else}
	<meta http-equiv="refresh" content="0;url=index.php?module=products&amp;view=manage" />
{/if}
