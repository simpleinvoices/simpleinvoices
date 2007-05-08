{if $invoices == null }
	<P><em>{$LANG.no_invoices}.</em></p>
{else}

<b>{$LANG.manage_invoices}</b> ::
<a href="index.php?module=invoices&view=total">{$LANG.add_new_invoice} - {$LANG.total_style}</a> ::
<a href="index.php?module=invoices&view=itemised">{$LANG.add_new_invoice} - {$LANG.itemised_style}</a> ::
<a href="index.php?module=invoices&view=consulting">{$LANG.add_new_invoice} - {$LANG.consulting_style}</a>
<hr></hr>


<table align="center" id="ex1" class="ricoLiveGrid manage" >
<colgroup>
<col style='width:15%;' />
<col style='width:5%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<!--
<col style='width:10%;' />
-->
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
<!--
<th class="noFilter">{$LANG.paid}</th>
-->
<th class="noFilter sortable">{$LANG.owing}</th>
<th class="selectFilter index_table sortable">{$LANG.aging}</th>
<th class="noFilter sortable">{$LANG.invoice_type}</th>
<th class="noFilter sortable">{$LANG.date_created}</th>
</tr>
</thead>

{foreach from=$invoices item=invoice}


	
	<tr class="index_table">
	<td class="index_table" nowrap>
	<!-- Quick View -->
	<a class="index_table"
	 title="{$LANG.quick_view_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id}"
	 href="index.php?module=invoices&view=quick_view&submit={$invoice.invoice.inv_id}&invoice_style={$invoiceType.inv_ty_description}">
		<img src="images/common/view.png" height="16" border="-5px0" padding="-4px" valign="bottom" /><!-- print --></a>
	</a>
	
	<!-- Edit View -->
	<a class="index_table" title="{$LANG.edit_view_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id}"
	 href="index.php?module=invoices&view=details&submit={$invoice.invoice.inv_id}&action=view&invoice_style={$invoiceType.inv_ty_description}">
		<img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
	</a> 
	
	<!-- Print View -->
	<a class="index_table" title="{$LANG.print_preview_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id}"
	href="index.php?module=invoices&view=templates/template&submit={$invoice.invoice.inv_id}&action=view&location=print&invoice_style={$invoiceType.inv_ty_description}">
	<img src="images/common/printer.gif" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
 
	<!-- EXPORT TO PDF -->
	<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id} {$LANG.export_pdf_tooltip}"
	class="index_table" href="{$invoice.url_for_pdf}"><img src="images/common/pdf.jpg" height="16" padding="-4px" border="-5px" valign="bottom" /><!-- pdf --></a>

	<!--XLS -->
	<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording}{$invoice.invoice.inv_id} {$LANG.export_xls_tooltip} {$spreadsheet} {$LANG.format_tooltip}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&submit={$invoice.invoice.inv_id}&action=view&invoice_style={$invoiceType.inv_ty_description}&location=print&export={$spreadsheet}">
	 <img src="images/common/xls.gif" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>

	<!-- DOC -->
	<a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id} {$LANG.export_doc_tooltip} {$word_processor} {$LANG.format_tooltip}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&submit={$invoice.invoice.inv_id}&action=view&invoice_style={$invoiceType.inv_ty_description}&location=print&export={$word_processor}">
	 <img src="images/common/doc.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>

  <!-- Payment --><a title="{$LANG.process_payment} {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id}"
   class="index_table" href="index.php?module=payments&view=process&submit={$invoice.invoice.inv_id}&op=pay_selected_invoice">$</a>
	<!-- Email -->
	<a href="index.php?module=invoices&view=email&stage=1&submit={$invoice.invoice.inv_id}" title="{$LANG.email}  {$invoice.preference.pref_inv_wording} {$invoice.invoice.inv_id}"><img src="images/common/mail-message-new.png" height="16" border="0" padding="-4px" valign="bottom" /></a>

	</td>
	<td class="index_table">{$invoice.invoice.inv_id}</td>
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