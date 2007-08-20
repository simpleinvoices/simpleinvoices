{if $taxes == null}
	<p><em>{$LANG.no_tax_rates}.</em></p>
{else}

	<h3>{$LANG.manage_tax_rates} ::
	<a href="./index.php?module=tax_rates&view=add">{$LANG.add_new_tax_rate}</a></h3>
 <hr />


<table align="center" class="ricoLiveGrid" id="rico_tax_rates">
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:30%;' />
<col style='width:10%;' />
<col style='width:15%;' />
</colgroup>
<thead>
<tr class="sortHeader">
	<th class="noFilter sortable">{$LANG.actions}</th>
	<th class="index_table sortable">{$LANG.tax_id}</th>
	<th class="index_table sortable">{$LANG.tax_description}</th>
	<th class="index_table sortable">{$LANG.tax_percentage}</th>
	<th class="noFilter index_table sortable">{$LANG.enabled}</th>
</tr>
</thead>

	{foreach from=$taxes item=tax}
		<tr class="index_table">
		<td class="index_table">
		<a class="index_table"
		href="./index.php?module=tax_rates&view=details&submit={$tax.tax_id}&action=view">{$LANG.view}</a> ::
		<a class="index_table"
		 href="./index.php?module=tax_rates&view=details&submit={$tax.tax_id}&action=edit">{$LANG.edit}</a></td>
		<td class="index_table">{$tax.tax_id}</td>
		<td class="index_table">{$tax.tax_description}</td>
		<td class="index_table">{$tax.tax_percentage}</td>
		<td class="index_table">{$tax.enabled}</td>
		</tr>

	{/foreach}
	</table>
{/if}