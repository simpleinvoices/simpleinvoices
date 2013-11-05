{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
	<h1 class="title">{$LANG.recurrence}
        <a class="btn btn-default" href="index.php?module=cron&amp;view=add" >
        	<span class="glyphicon glyphicon-plus"></span>
        	{$LANG.new_recurrence}
        </a>
    </h1>

{if $number_of_crons.count == 0}
	
	<div class="si_message">
		{$LANG.no_crons}
	</div>
{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/cron/manage.js.php'}

{/if}


