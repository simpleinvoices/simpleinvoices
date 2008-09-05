{*
/*
* Script: manage.tpl
* 	 Tax Rates manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
{if $taxes == null}
<p><em>{$LANG.no_tax_rates}.</em></p>
{else}
<h3>{$LANG.manage_tax_rates} ::
<a href="./index.php?module=tax_rates&amp;view=add">{$LANG.add_new_tax_rate}</a></h3>
<hr />

<table id="manageGrid" style="display:none"></table>

 {include file='../modules/tax_rates/manage.js.php'}
 
 
{*
<table class="manage" id="live-grid" align="center">
	<colgroup>
		<col style="width:7%;" />
		<col style="width:10%;" />
		<col style="width:33%;" />
		<col style="width:10%;" />
		<col style="width:15%;" />
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
		<td class="index_table"><a title="{$LANG.view}" class="index_table" href="./index.php?module=tax_rates&amp;view=details&amp;submit={$tax.tax_id}&amp;action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle" alt="{$LANG.view}" /></a>
			<a title="{$LANG.edit}" class="index_table" href="./index.php?module=tax_rates&amp;view=details&amp;submit={$tax.tax_id}&amp;action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" alt="{$LANG.edit}" /></a></td>
		<td class="index_table">{$tax.tax_id}</td>
		<td class="index_table">{$tax.tax_description|escape:html}</td>
		<td class="index_table">{$tax.tax_percentage|escape:html}</td>
		<td class="index_table">{$tax.enabled|escape:html}</td>
	</tr>
	{/foreach}
</table>
*}
{/if}
