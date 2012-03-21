<br />
{if $saved == true }
	<br />
	 {$LANG.save_preference_success}
	<br />
	<br />
{else}
	<br />
	 {$LANG.save_preference_failure}
	<br />
	<br />
{/if}

{if $saved == true }
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
{/if}
