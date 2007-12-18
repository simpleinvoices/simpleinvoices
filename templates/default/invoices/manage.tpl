{*
/*
* Script: manage.tpl
* 	 Manage invoices template
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

{if $invoices == null }
	<P><em>{$LANG.no_invoices}.</em></p>
{else}

<div style="text-align:center;">
<b>{$LANG.manage_invoices}</b> :: <b><font color="blue">{$LANG.add_new_invoice}</font></b> [
<a href="index.php?module=invoices&view=total"> <b>{$LANG.total_style}</b></a> ::
<a href="index.php?module=invoices&view=itemised"> <b>{$LANG.itemised_style}</b></a> ::
<a href="index.php?module=invoices&view=consulting"> <b>{$LANG.consulting_style}</b> </a>]
</div>
<hr />


<table align="center" id="ex1" class="ricoLiveGrid manage" >
<colgroup>
	<col style='width:15%;' />
	<col style='width:5%;' />
	<col style='width:10%;' />
	<col style='width:10%;' />
	<col style='width:10%;' />
	<col style='width:10%;' />
	<col style='width:5%;' />
	<col style='width:5%;' />
	<col style='width:10%;' />
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

	
	<tr class="index_table">
	<td class="index_table" nowrap>
	<!-- Quick View -->
	<a class="index_table"
	 title="{$LANG.quick_view_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}"
	 href="index.php?module=invoices&view=quick_view&invoice={$invoice.invoice.id}&type={$invoice.invoiceType.inv_ty_id}">
		<img src="images/common/view.png" height="16" border="-5px0" padding="-4px" valign="bottom" /><!-- print --></a>
	
	<!-- Edit View -->
	<a class="index_table" title="{$LANG.edit_view_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}"
	 href="index.php?module=invoices&view=details&invoice={$invoice.invoice.id}&action=view&type={$invoice.invoiceType.inv_ty_id}">
		<img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
	
	<!-- Print View -->
	<a class="index_table" title="{$LANG.print_preview_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}"
	href="index.php?module=invoices&view=templates/template&invoice={$invoice.invoice.id}&action=view&location=print&type={$invoice.invoiceType.inv_ty_id}">
	<img src="images/common/printer.gif" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
 
	<!-- EXPORT TO PDF -->
	<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id} {$LANG.export_pdf_tooltip}"
	class="index_table" href="{$invoice.url_for_pdf}"><img src="images/common/pdf.jpg" height="16" padding="-4px" border="-5px" valign="bottom" /><!-- pdf --></a>

	<!--XLS -->
	<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording}{$invoice.invoice.id} {$LANG.export_xls_tooltip} {$spreadsheet} {$LANG.format_tooltip}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&invoice={$invoice.invoice.id}&action=view&type={$invoice.invoiceType.inv_ty_id}&location=print&export={$spreadsheet}">
	 <img src="images/common/xls.gif" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>

	<!-- DOC -->
	<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id} {$LANG.export_doc_tooltip} {$word_processor} {$LANG.format_tooltip}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&invoice={$invoice.invoice.id}&action=view&type={$invoice.invoiceType.inv_ty_id}&location=print&export={$word_processor}">
	 <img src="images/common/doc.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>

  <!-- Payment --><a title="{$LANG.process_payment} {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}"
   class="index_table" href="index.php?module=payments&view=process&invoice={$invoice.invoice.id}&op=pay_selected_invoice">$</a>
	<!-- Email -->
	<a href="index.php?module=invoices&view=email&stage=1&invoice={$invoice.invoice.id}" title="{$LANG.email}  {$invoice.preference.pref_inv_wording} {$invoice.invoice.id}"><img src="images/common/mail-message-new.png" height="16" border="0" padding="-4px" valign="bottom" /></a>

	</td>
	<td class="index_table">{$invoice.invoice.id}</td>
	<td class="index_table">{$invoice.biller.name}</td>
	<td class="index_table">{$invoice.customer.name}</td>
	<td class="index_table">{$invoice.invoice.total}</td>
	<!--
	<td class="index_table">{$invoice.paid_format}</td>
	-->
	<td class="index_table">{$invoice.invoice.owing}</td>
	<td class="index_table">{$invoice.overdue}</td>
	<td class="index_table">{$invoice.preference.pref_inv_wording}</td>
	<td class="index_table">{$invoice.invoice.date}</td>
	</tr>

									
	{/foreach}					

</table>
{/if}
