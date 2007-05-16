{if $saved == true }
	<br>
	 {$LANG.save_biller_success}
	<br>
	<br>
{else}
	<br>
	 {$LANG.save_biller_failure}
	<br>
	<br>
{/if}

{if $smarty.post.cancel == null }
	<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=billers&view=manage>
{else}
	<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=billers&view=manage>
{/if}
