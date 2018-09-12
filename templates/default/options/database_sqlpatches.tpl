{*
/*
* Script: database_sqlpatches.tpl
* 	 Database sqlpatches template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* License:
*	 GPL v2 or above
*
* Website:
*	https://simpleinvoices.group
*/
*}

<div id="si_page_updates">
	<H2 class="si_title">Database Upgrade Manager</H2>

	<div class="si_message">{$page.message}</div>

	{$page.html}

{* makes rows ######################## *}
{if $page.rows|@count}

	<hr />
	<ul class="si_list">
	{foreach from=$page.rows item='row'}
		<li class="li_{$row.result}">{$row.text}</li>
	{/foreach}
	</ul>

{/if}



{* Refresh ######################## *}
{if $page.refresh}
	<meta http-equiv="refresh" content="{$page.refresh}0;url=index.php">
{/if}


</div>


{* bye bye $display_block *}
