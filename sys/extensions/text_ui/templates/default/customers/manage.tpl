{*
/*
* Script: manage.tpl
* 	 Customer manage template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $number_of_customers.count == 0}
	<p><em>{$LANG.no_customers}</em></p>
{else}

<b>{$LANG.manage_customers} :: <a href="index.php?module=customers&amp;view=add">{$LANG.customer_add}</a></b>
<table id="manageGrid" >
    <tr>
		<td>Action</td>
		<td>Name</td>
		<td>Total</td>
		<td>Owing</td>
	</tr>
{foreach from=$xml->row item=cell}
    <tr>
		<td>{$cell->action}</td>
		<td>{$cell->name}</td>
		<td align="right">{$cell->total}</td>
		<td align="right">{$cell->owing}</td>
	</tr>
{/foreach}

</table>
	{if $number_of_customers.count > 25}
		<a href='index.php?module=customers&amp;view=manage&amp;page={$page_prev}'> << </a>
			::
		<a href='index.php?module=customers&amp;view=manage&amp;page={$page_next}'> >> </a>
	{/if}

{/if}
