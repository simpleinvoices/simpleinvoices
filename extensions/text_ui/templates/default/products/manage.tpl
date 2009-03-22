{*
/*
* Script: manage.tpl
* 	 Products manage template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $number_of_rows == null }
	<p><em>{$LANG.no_products}</em></p>
{else}
 
<b>{$LANG.manage_products} :: <a href="index.php?module=products&amp;view=add">{$LANG.add_new_product}</a></b>
<table id="manageGrid" >
    <tr>
		<td>Action</td>
		<td>Name</td>
		<td>{$LANG.price}</td>
	</tr>
{foreach from=$xml->row item=cell}
    <tr>
		<td>{$cell->action}</td>
		<td>{$cell->description}</td>
		<td align="right">{$cell->unit_price}</td>
	</tr>
{/foreach}

</table>
{if $number_of_rows.count > 25}
	<a href='index.php?module=products&amp;view=manage&amp;page={$page_prev}'> << </a>
		::
	<a href='index.php?module=products&amp;view=manage&amp;page={$page_next}'> >> </a>
{/if}
{/if}
