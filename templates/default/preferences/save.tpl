<br>
{if $saved == true }
	<br>
	 {$LANG.save_preference_success}
	<br>
	<br>
{else}
	<br>
	 {$LANG.save_preference_failure}
	<br>
	<br>
{/if}

{if $saved == true }
	<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>
{/if}
