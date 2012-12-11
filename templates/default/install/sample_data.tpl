{include file=$path|cat:'inc_head.tpl'}


{if $saved == true }
	<div class="si_message_ok">Sample data has been imported into Simple Invoices</div>
	<meta http-equiv="refresh" content="3;URL=index.php" />
{else}
	<div class="si_message_error">Something bad happened. Sample data has NOT been imported into Simple Invoices</div>
{/if}

{include file=$path|cat:'inc_foot.tpl'}
