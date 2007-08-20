{*
/*
* Script: manage_sqlpatches.tpl
* 	 Manage sql patches template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<h3>Database patches applied to Simple Invoices</h3>
<hr />


	<table align="center" class="ricoLiveGrid manage" id="rico_sqlpatches">
	<colgroup>
		<col style='width:20%;' />
		<col style='width:60%;' />
		<col style='width:20%;' />
	</colgroup>
	<thead>
	<tr>
		<th class="sortable">Patch ID</th>
		<th class="sortable">Description</th>
		<th class="sortable">Release</th>
	</tr>
	</thead>

{foreach from=$patches item=patch} 
	<tr>
		<td class='index_table'>{$patch.sql_patch_ref}</td>
		<td class='index_table'>{$patch.sql_patch}</td>
		<td class='index_table'>{$patch.sql_release}</td>
	</tr>

{/foreach}
		

</table>
