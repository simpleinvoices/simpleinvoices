{*
/*
* Script: manage_cronlogs.tpl
* 	 Manage Cron Logs template
*
* Authors:
*	 Ap.Muthu
*
* Last edited:
* 	 2013-10-20
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<h3>Cron Log - Recurrent Invoices Inserted</h3>
<hr />


	<table class="manage" id="live-grid" class="center">
	<colgroup>
		<col style='width:20%;' />
		<col style='width:30%;' />
		<col style='width:20%;' />
<!--		<col style='width:30%;' /> -->
	</colgroup>
	<thead>
	<tr>
		<th class="sortable">ID</th>
		<th class="sortable">Date</th>
		<th class="sortable">Cron ID</th>
<!--		<th class="sortable">Invoice No</th> -->
	</tr>
	</thead>

{foreach from=$cronlogs item=cronlog} 
	<tr>
		<td class='index_table'>{$cronlog.id|htmlsafe}</td>
		<td class='index_table'>{$cronlog.run_date|htmlsafe}</td>
		<td class='index_table'><a href="index.php?module=cron&view=view&id={$cronlog.cron_id|htmlsafe}">{$cronlog.cron_id|htmlsafe}</a></td>
	</tr>

{/foreach}
		

</table>
