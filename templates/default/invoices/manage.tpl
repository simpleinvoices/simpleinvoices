{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
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

{if $invoices == null }
<p><em>{$LANG.no_invoices}.</em></p>
{else}
<div style="text-align:center;"><b>{$LANG.manage_invoices}</b> :: <a href="index.php?module=invoices&view=total">{$LANG.add_new_invoice} - {$LANG.total_style}</a> :: <a href="index.php?module=invoices&view=itemised">{$LANG.add_new_invoice} - {$LANG.itemised_style}</a> :: <a href="index.php?module=invoices&view=consulting">{$LANG.add_new_invoice} - {$LANG.consulting_style}</a></div><hr />
<table class="manage" id="live-grid" align="center">
	<colgroup>
		<col style="width:10%;" />
		<col style="width:5%;" />
		<col style="width:13%;" />
		<col style="width:14%;" />
		<col style="width:9%;" />
		<col style="width:9%;" />
		<col style="width:5%;" />
		<col style="width:5%;" />
		<col style="width:10%;" />
	</colgroup>
	<thead> 
		<tr class="sortHeader">
			<th class="noFilter sortable" >{$LANG.actions} </th>
			<th class="noFilter sortable">{$LANG.id}</th>
			<th class="selectFilter index_table sortable">{$LANG.biller}</th>
			<th class="selectFilter index_table sortable">{$LANG.customer}</th>
			<th class="noFilter sortable">{$LANG.total}</th>
			<th class="noFilter sortable">{$LANG.owing}</th>
			<th class="selectFilter index_table sortable">{$LANG.aging}</th>
			<th class="noFilter sortable">{$LANG.invoice_type}</th>
			<th class="noFilter sortable">{$LANG.date_upper}</th>
		</tr>
	</thead>
	{foreach from=$invoices item=invoice}
	<tbody>
		<tr class="index_table">
			<td class="index_table" nowrap>
				<!-- Quick View -->
				<a class="index_table" title="{$LANG.quick_view_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}" href="index.php?module=invoices&view=quick_view&invoice={$invoice.invoice.id}&type={$invoice.invoiceType.inv_ty_id}"><img src="images/common/view.png" height="16" border="0" align="absmiddle" /></a>
				
				<!-- Edit View -->
				<a class="index_table" title="{$LANG.edit_view_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}" href="index.php?module=invoices&view=details&invoice={$invoice.invoice.id}&action=view&type={$invoice.invoiceType.inv_ty_id}"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a>
		{if $defaults.delete == '1'}
				<!-- Delete -->
				<a title="{$LANG.delete} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}" href="index.php?module=invoices&view=delete&stage=1&invoice={$invoice.invoice.id}"><img src="images/common/delete.png" height="16" border="0" align="absmiddle" /></a>
		{/if}		
				<!-- Print View -->
				<a target="_blank" class="index_table" title="{$LANG.print_preview_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}" href="index.php?module=invoices&view=templates/template&invoice={$invoice.invoice.id}&action=view&location=print&type={$invoice.invoiceType.inv_ty_id}"><img src="images/common/printer.png" height="16" border="0" align="absmiddle" /></a>
			 	
				<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}" href="#" class="showdownloads"><img src="images/common/page_white_acrobat.png" height="16" border="0" align="absmiddle" /></a>
				<div class="downloads" style="display:none;">
					<!-- EXPORT TO PDF -->
					<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id} {$LANG.export_pdf_tooltip}" class="index_table" href="{$invoice.url_for_pdf}"><img src="images/common/page_white_acrobat.png" height="16" border="0" align="absmiddle" /></a>
				
					<!--XLS -->
					<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording}{$invoice.invoice.id} {$LANG.export_xls_tooltip} {$spreadsheet} {$LANG.format_tooltip}" class="index_table" href="index.php?module=invoices&view=templates/template&invoice={$invoice.invoice.id}&action=view&type={$invoice.invoiceType.inv_ty_id}&location=print&export={$spreadsheet}"><img src="images/common/xls.gif" height="16" border="0" align="absmiddle" /></a>
				
					<!-- DOC -->
					<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id} {$LANG.export_doc_tooltip} {$word_processor} {$LANG.format_tooltip}" class="index_table" href="index.php?module=invoices&view=templates/template&invoice={$invoice.invoice.id}&action=view&type={$invoice.invoiceType.inv_ty_id}&location=print&export={$word_processor}"><img src="images/common/doc.png" height="16" border="0" align="absmiddle" /></a>
				</div>
			 	<!-- Payment -->
				<a title="{$LANG.process_payment} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}" class="index_table" href="index.php?module=payments&view=process&invoice={$invoice.invoice.id}&op=pay_selected_invoice"><img src="images/common/money_dollar.png" height="16" border="0" align="absmiddle" /></a>
				
				<!-- Email -->
				<a href="index.php?module=invoices&view=email&stage=1&invoice={$invoice.invoice.id}" title="{$LANG.email}  {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}"><img src="images/common/email_attach.png" height="16" border="0" border="0" align="absmiddle" /></a></td>
			<td class="index_table">{$invoice.invoice.id}</td>
			<td class="index_table">{$invoice.biller.name}</td>
			<td class="index_table">{$invoice.customer.name}</td>
			<td class="index_table">{$invoice.invoice.total}</td>
			<!-- <td class="index_table">{$invoice.paid_format}</td> -->
			<td class="index_table">{$invoice.invoice.owing}</td>
			<td class="index_table">{$invoice.overdue}</td>
			<td class="index_table">{$invoice.preference.pref_inv_wording}</td>
			<td class="index_table">{$invoice.invoice.date}</td>
		</tr>									
	{/foreach}
	</tbody>				
</table>
{/if}
