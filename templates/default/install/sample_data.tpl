{include file=$path|cat:'inc_head.tpl'}


{if $saved == true }
	<div class="si_form si_message_install">Sample data has been imported into SimpleInvoices</div>
	<meta http-equiv="refresh" content="3;URL=index.php" />
{else}
	<div class="si_message_error">Something bad happened. Sample data has NOT been imported into SimpleInvoices</div>
{/if}

{include file=$path|cat:'inc_foot.tpl'}
