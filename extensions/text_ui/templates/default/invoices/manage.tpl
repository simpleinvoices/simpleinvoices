{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $number_of_invoices.count == 0}
	<p><em>{$LANG.no_invoices}</em></p>
{else}

<b>{$LANG.manage_invoices}</b>
<table id="manageGrid" >
    <tr>
		<td>Action</td>
		<td>Cust.</td>
		<td>Date</td>
		<td>Total</td>
	</tr>
{foreach from=$xml->row item=cell}
    <tr>
		<td>{$cell->action}</td>
		<td>{$cell->customer}</td>
		<td>{$cell->date}</td>
		<td align="right">{$cell->invoice_total}</td>
	</tr>
{/foreach}

</table>
{if $number_of_invoices.count > 25}
	<a href='index.php?module=invoices&view=manage&page={$page_prev}'> << </a>
		::
	<a href='index.php?module=invoices&view=manage&page={$page_next}'> >> </a>
{/if}
{*
 {include file='../extensions/text_ui/modules/invoices/manage.js.php'}
*}
{/if}
