{if $billers == null}
<P><em>{$LANG.no_billers}.</em></p>
{else}
<h3>{$LANG.manage_billers} :: <a href='index.php?module=billers&view=add'>{$LANG.add_new_biller}</a></h3>
<hr />
<table class="ricoLiveGrid manage" id="rico_biller" align="center">
	<colgroup>
		<col style='width:15%;' />
		<col style='width:10%;' />
		<col style='width:40%;' />
		<!--
<col style='width:10%;' />
<col style='width:10%;' />
-->
		<col style='width:25%;' />
		<col style='width:10%;' />
	</colgroup>
	<thead>
		<tr class="sortHeader">
			<th class="noFilter sortable">{$LANG.actions}</th>
			<th class=" index_table sortable">{$LANG.biller_id}</th>
			<th class="index_table sortable">{$LANG.biller_name}</th>
			<th class="index_table sortable">{$LANG.email}</th>
			<th class="noFilter index_table sortable">{$LANG.enabled}</th>
		</tr>
	</thead>
	{foreach from=$billers item=biller}
	<tr class='index_table'>
		<td class='index_table'><a class='index_table'
			href='index.php?module=billers&view=details&submit={$biller.id}&action=view'>
		{$LANG.view} </a> :: <a class='index_table'
			href='index.php?module=billers&view=details&submit={$biller.id}&action=edit'>
		{$LANG.edit} </a></td>
		<td class='index_table'>{$biller.id}</td>
		<td class='index_table'>{$biller.name}</td>
		<!--
	<td class='index_table'>{$biller.phone}</td>
	<td class='index_table'>{$biller.mobile_phone}</td>
	-->
		<td class='index_table'>{$biller.email}</td>
		<td class='index_table'>{$biller.enabled}</td>
	</tr>
	{/foreach}
</table>
{/if}
