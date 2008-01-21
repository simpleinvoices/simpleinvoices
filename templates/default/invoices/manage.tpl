{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ap.Muthu
*
* Last edited:
* 	 2008-01-03
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
<b>{$LANG.manage_invoices}</b> :: {$LANG.add_new_invoice} 
<a href="index.php?module=invoices&view=total"> {$LANG.total_style}</a> ::
<a href="index.php?module=invoices&view=itemised"> {$LANG.itemised_style}</a> ::
<a href="index.php?module=invoices&view=consulting"> {$LANG.consulting_style} </a>

</div>
<hr />


<table align="center" id="ex1" class="ricoLiveGrid manage" >
<colgroup>
	<col style='width:15%;' />
	<col style='width:5%;'  />
	<col style='width:10%;' />
	<col style='width:10%;' />
	<col style='width:10%;' />
	<col style='width:10%;' />
	<col style='width:5%;'  />
	<col style='width:5%;'  />
	<col style='width:10%;' />
</colgroup>
<thead> 
	<tr class="sortHeader">
		<th class="noFilter sortable">{$LANG.actions} </th>
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
	<a class="index_table" title="{$LANG.quick_view_tooltip} {$invoice.pref_inv_wording} {$invoice.id}"
	href="index.php?module=invoices&view=quick_view&invoice={$invoice.id}">
	<img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
	
	<!-- Edit View -->
	<a class="index_table" title="{$LANG.edit_view_tooltip} {$invoice.pref_inv_wording} {$invoice.id}"
	href="index.php?module=invoices&view=details&invoice={$invoice.id}&action=view">
	<img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
	
	<!-- Print View -->
	<a class="index_table" title="{$LANG.print_preview_tooltip} {$invoice.pref_inv_wording} {$invoice.id}"
	href="index.php?module=invoices&view=templates/template&invoice={$invoice.id}&action=view&location=print">
	<img src="images/common/printer.gif" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
 
	<!-- EXPORT TO PDF -->
	<a class="index_table" title="{$LANG.export_tooltip} {$invoice.pref_inv_wording} {$invoice.id} {$LANG.export_pdf_tooltip}"
	href="{$invoice.url_for_pdf}"><img src="images/common/pdf.jpg" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- pdf --></a>

	<!--XLS -->
	<a class="index_table" title="{$LANG.export_tooltip} {$invoice.pref_inv_wording}{$invoice.id} {$LANG.export_xls_tooltip} {$spreadsheet} {$LANG.format_tooltip}"
	href="index.php?module=invoices&view=templates/template&invoice={$invoice.id}&action=view&location=print&export={$spreadsheet}">
	<img src="images/common/xls.gif" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>

	<!-- DOC -->
	<a class="index_table" title="{$LANG.export_tooltip} {$invoice.pref_inv_wording} {$invoice.id} {$LANG.export_doc_tooltip} {$word_processor} {$LANG.format_tooltip}"
	href="index.php?module=invoices&view=templates/template&invoice={$invoice.id}&action=view&location=print&export={$word_processor}">
	<img src="images/common/doc.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>

	<!-- Payment -->
	<a class="index_table" title="{$LANG.process_payment} {$invoice.pref_inv_wording} {$invoice.id}"
	href="index.php?module=payments&view=process&invoice={$invoice.id}&op=pay_selected_invoice">$</a>

	<!-- Email -->
	<a class="index_table" title="{$LANG.email}  {$invoice.pref_inv_wording} {$invoice.id}" 
	href="index.php?module=invoices&view=email&stage=1&invoice={$invoice.id}">
	<img src="images/common/mail-message-new.png" height="16" border="0" padding="-4px" valign="bottom" /></a>

	</td>
	<td class="index_table">{$invoice.id}</td>
	<td class="index_table">{$invoice.biller}</td>
	<td class="index_table">{$invoice.customer}</td>
	<td class="index_table">{$invoice.total}</td>
	<!--
	<td class="index_table">{$invoice.paid|number_format:2}</td>
	-->
	<td class="index_table">{$invoice.owing}</td>
	<td class="index_table">{if $invoice.owing > 0}{$invoice.overdue}{/if}</td>

	<td class="index_table">{$invoice.pref_inv_wording}</td>
	<td class="index_table">{$invoice.date}</td>
	</tr>

									
	{/foreach}					

</table>
{/if}
