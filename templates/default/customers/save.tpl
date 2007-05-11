{if $saved == true }
	{$LANG.save_customer_success}
{else}
	 {$LANG.save_customer_failure}
{/if}

{if $smarty.post.cancel == null }
	<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=customers&view=manage>
{else}
	<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=customers&view=manage>
{/if}