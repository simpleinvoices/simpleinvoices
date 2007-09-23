{*
/*
* Script: manage.tpl
* 	 Products manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $products == null }
	<p><em>{$LANG.no_products}</em></p>
{else}
<h3>{$LANG.manage_products} :: <a href="index.php?module=products&view=add">{$LANG.add_new_product}</a></h3>
<hr />
<table align="center" class="ricoLiveGrid" id="rico_product">
	<colgroup>
		<col style='width:7%;' />
		<col style='width:10%;' />
		<col style='width:53%;' />
		<col style='width:20%;' />
		<col style='width:10%;' />
	</colgroup>
	<thead>
		<tr class="sortHeader">
			<th class="noFilter sortable">{$LANG.actions}</th>
			<th class="index_table sortable">{$LANG.product_id}</th>
			<th class="index_table sortable">{$LANG.product_description}</th>
			<th class="index_table sortable">{$LANG.product_unit_price}</th>
			<th class="noFilter index_table sortable">{$LANG.enabled}</th>
		</tr>
	</thead>
{foreach from=$products item=product}
	<tr class="index_table">
		<td class="index_table"><a title="{$LANG.view}" class="index_table" href="index.php?module=products&view=details&id={$product.id}&action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle"/></a>
			<a title="{$LANG.edit}" class="index_table" href="index.php?module=products&view=details&id={$product.id}&action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a> </td>
		<td class="index_table">{$product.id}</td>
		<td class="index_table">{$product.description}</td>
		<td class="index_table">{$product.unit_price}</td>
		<td class="index_table">{$product.enabled}</td>
	</tr>
{/foreach}
</table>
{/if}
