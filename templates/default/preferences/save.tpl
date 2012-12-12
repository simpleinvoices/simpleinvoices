


{if $saved == true }
	<div class="si_message_ok">{$LANG.save_preference_success}</div>
{else}
	<div class="si_message_error">{$LANG.save_preference_failure}</div>
{/if}


{if $saved == true }
	<meta http-equiv="refresh" content="2;URL=index.php?module=preferences&amp;view=manage" />
{/if}
