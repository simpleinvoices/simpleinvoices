<br />
{if $saved == true}
	<br>
	<br>
	Guardado!
{else}
	<br>
	<br>
	No guardo!
<br />
{/if}

{if $smarty.post.cancel == null }
	<meta http-equiv="refresh" content="2;URL=index.php?module=categories&view=manage" />
{else}
	<meta http-equiv="refresh" content="0;URL=index.php?module=categories&view=manage" />
{/if}
