<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


echo <<<EOD
<title>{$title} :: {$LANG['manage_invoices']}</title>
EOD;

#insert customer

$sql = "SELECT * FROM {$tb_prefix}invoices ORDER BY inv_id desc";

$page_header = <<<EOD
<b>{$LANG['manage_invoices']}</b> ::
<a href="index.php?module=invoices&view=total">{$LANG['add_new_invoice']} - {$LANG['total_style']}</a> ::
<a href="index.php?module=invoices&view=itemised">{$LANG['add_new_invoice']} - {$LANG['itemised_style']}</a> ::
<a href="index.php?module=invoices&view=consulting">{$LANG['add_new_invoice']} - {$LANG['consulting_style']}</a>
<hr></hr>
EOD;

$result = mysql_query($sql) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG['no_invoices']}.</em></p>";
}else{
	$display_block = <<<EOD

{$page_header}

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
<th class="noFilter sortable" >{$LANG['actions']} </th>
<th class="noFilter sortable">{$LANG['id']}</th>
<th class="selectFilter index_table sortable">{$LANG['biller']}</th>
<th class="selectFilter index_table sortable">{$LANG['customer']}</th>
<th class="noFilter sortable">{$LANG['total']}</th>
<!--
<th class="noFilter">{$LANG['paid']}</th>
-->
<th class="noFilter sortable">{$LANG['owing']}</th>
<th class="selectFilter index_table sortable">{$LANG['aging']}</th>
<th class="noFilter sortable">{$LANG['invoice_type']}</th>
<th class="noFilter sortable">{$LANG['date_created']}</th>
</tr>
</thead>
EOD;

while ($invoice = mysql_fetch_array($result)) {
	$invoice['calc_date'] = date( 'Y-m-d', strtotime( $invoice['inv_date'] ) );
	$invoice['date'] = date( $config['date_format'], strtotime( $invoice['inv_date'] ) );
		
	$biller = getBiller($invoice['inv_biller_id']);
	$customer = getCustomer($invoice['inv_customer_id']);
	$invoiceType = getInvoiceType($invoice['inv_type']);

#invoice total total - start
	$invoice_total_Field = calc_invoice_total($invoice['inv_id']);
	$invoice_total_Field_format = number_format($invoice_total_Field,2);
#invoice total total - end

#amount paid calc - start
	$invoice_paid_Field = calc_invoice_paid($invoice['inv_id']);
	$invoice_paid_Field_format = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
	$invoice_owing_Field_format = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end

	#Overdue - number of days - start
	if ($invoice_owing_Field > 0 ) {
		//echo "<!-- ", strtotime(date( 'Y-m-d')), ' ', strtotime($invoice['date']), " -->\n";
		$overdue_days = (strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24);
		/*if ($overdue_days == 0) {
			$overdue = "0-14";
		}
		elseif ($overdue_days <=14 ) {
			$overdue = "0-14";
		}
		elseif ($overdue_days <= 30 ) {
			$overdue = "15-30";
		}
		elseif ($overdue_days <= 60 ) {
			$overdue = "31-60";
		}
		elseif ($overdue_days <= 90 ) {
			$overdue = "61-90";
		}
		else {
			$overdue = "90+";
		}*/
		$overdue = floor($overdue_days);
	}		
	else {
		$overdue ="";
	}

	#Overdue - number of days - end


        $print_invoice_preference ="select pref_inv_wording from {$tb_prefix}preferences where pref_id =$invoice[inv_preference]";
        $result_print_invoice_preference = mysql_query($print_invoice_preference, $conn) or die(mysql_error());

        while ($Array = mysql_fetch_array($result_print_invoice_preference)) {
                $invoice_preference_wordingField = $Array['pref_inv_wording'];

				
	$defaults = getSystemDefaults();
	
       $url_pdf = "{$_SERVER['HTTP_HOST']}{$install_path}/index.php?module=invoices&view=templates/template&submit={$invoice['inv_id']}&action=view&location=pdf&invoice_style={$invoiceType['inv_ty_description']}";
       $url_pdf_encoded = urlencode($url_pdf);
        $url_for_pdf = "./pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels={$pdf_screen_size}&media={$pdf_paper_size}&leftmargin={$pdf_left_margin}&rightmargin={$pdf_right_margin}&topmargin={$pdf_top_margin}&bottommargin={$pdf_bottom_margin}&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&URL={$url_pdf_encoded}";

	/*
    $url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$invoice[inv_id]&action=view&location=pdf&invoice_style=$invoice_type[inv_ty_description]";
    $url_pdf_encoded = urlencode($url_pdf);
    $url_for_pdf = "./pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=1&location=pdf&pdfname=$preferences[pref_inv_wording]$invoice[inv_id]&URL=$url_pdf_encoded";

*/

	$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table" nowrap>
	<!-- Quick View -->
	<a class="index_table"
	 title="{$LANG['quick_view_tooltip']} {$invoice_preference_wordingField} {$invoice['inv_id']}"
	 href="index.php?module=invoices&view=quick_view&submit={$invoice['inv_id']}&invoice_style={$invoiceType['inv_ty_description']}">
		<img src="images/common/view.png" height="16" border="-5px0" padding="-4px" valign="bottom" /><!-- print --></a>
	</a>
	<!-- Edit View -->
	<a class="index_table" title="{$LANG['edit_view_tooltip']} {$invoice_preference_wordingField} {$invoice['inv_id']}"
	 href="index.php?module=invoices&view=details&submit={$invoice['inv_id']}&action=view&invoice_style={$invoiceType['inv_ty_description']}">
		<img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
	</a> 
	
	<!-- Print View -->
	<a class="index_table" title="{$LANG['print_preview_tooltip']} {$invoice_preference_wordingField} {$invoice['inv_id']}"
	href="index.php?module=invoices&view=templates/template&submit={$invoice['inv_id']}&action=view&location=print&invoice_style={$invoiceType['inv_ty_description']}">
	<img src="images/common/printer.gif" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
 
	<!-- EXPORT TO PDF -->
	<a title="{$LANG['export_tooltip']} {$invoice_preference_wordingField} {$invoice['inv_id']} {$LANG['export_pdf_tooltip']}"
	class="index_table" href="{$url_for_pdf}"><img src="images/common/pdf.jpg" height="16" padding="-4px" border="-5px" valign="bottom" /><!-- pdf --></a>

	<!--XLS -->
	<a title="{$LANG['export_tooltip']} {$invoice_preference_wordingField}{$invoice['inv_id']} {$LANG['export_xls_tooltip']} {$spreadsheet} {$LANG['format_tooltip']}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&submit={$invoice['inv_id']}&action=view&invoice_style={$invoiceType['inv_ty_description']}&location=print&export={$spreadsheet}">
	 <img src="images/common/xls.gif" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>

	<!-- DOC -->
	<a title="{$LANG['export_tooltip']} {$invoice_preference_wordingField} {$invoice['inv_id']} {$LANG['export_doc_tooltip']} {$word_processor} {$LANG['format_tooltip']}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&submit={$invoice['inv_id']}&action=view&invoice_style={$invoiceType['inv_ty_description']}&location=print&export={$word_processor}">
	 <img src="images/common/doc.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>

  <!-- Payment --><a title="{$LANG['process_payment']} {$invoice_preference_wordingField} {$invoice['inv_id']}"
   class="index_table" href="index.php?module=payments&view=process&submit={$invoice['inv_id']}&op=pay_selected_invoice">$</a>
	<!-- Email -->
	<a href="index.php?module=invoices&view=email&stage=1&submit={$invoice['inv_id']}" title="{$LANG['email']}  {$invoice_preference_wordingField} {$invoice['inv_id']}"><img src="images/common/mail-message-new.png" height="16" border="0" padding="-4px" valign="bottom" /></a>

	</td>
	<td class="index_table">{$invoice['inv_id']}</td>
	<td class="index_table">{$biller['name']}</td>
	<td class="index_table">{$customer['name']}</td>
	<td class="index_table">{$invoice_total_Field}</td>
	<!--
	<td class="index_table">{$invoice_paid_Field_format}</td>
	-->
	<td class="index_table">{$invoice_owing_Field}</td>
	<td class="index_table">{$overdue}</td>
	<td class="index_table">{$invoice_preference_wordingField}</td>
	<td class="index_table">{$invoice['date']}</td>
	</tr>

EOD;
									
								}
								
					
				}		

	$display_block .="</table>";
}


getRicoLiveGrid("ex1","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' }");


echo $display_block;
?>
