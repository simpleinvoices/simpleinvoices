<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block .= "<P><em>{$LANG['no_invoices']}.</em></p>";
}else{
	$display_block .= <<<EOD

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

while ($newArray = mysql_fetch_array($result)) {
	$inv_idField = $newArray['inv_id'];
	$inv_biller_idField = $newArray['inv_biller_id'];
	$inv_customer_idField = $newArray['inv_customer_id'];
	$inv_typeField = $newArray['inv_type'];
	$inv_preferenceField = $newArray['inv_preference'];
	$calc_dateField = date( 'Y-m-d', strtotime( $newArray['inv_date'] ) );
	$inv_dateField = date( $config['date_format'], strtotime( $newArray['inv_date'] ) );
	$inv_noteField = $newArray['inv_note'];

	$sql_biller = "select name from {$tb_prefix}biller where id = $inv_biller_idField ";
	$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

	while ($billerArray = mysql_fetch_array($result_biller)) {
		$b_nameField = $billerArray['name'];


	$sql_customers = "select name from {$tb_prefix}customers where id = $inv_customer_idField ";
	$result_customers = mysql_query($sql_customers, $conn) or die(mysql_error());

	while ($customersArray = mysql_fetch_array($result_customers)) {
		$c_nameField = $customersArray['name'];


	$sql_invoice_type = "select inv_ty_description from {$tb_prefix}invoice_type where inv_ty_id = $inv_typeField ";
	$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

	while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
		$inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];

#invoice total total - start
	$invoice_total_Field = calc_invoice_total($inv_idField);
	$invoice_total_Field_format = number_format($invoice_total_Field,2);
#invoice total total - end

#amount paid calc - start
	$invoice_paid_Field = calc_invoice_paid($inv_idField);
	$invoice_paid_Field_format = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
	$invoice_owing_Field_format = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end

	#Overdue - number of days - start
	if ($invoice_owing_Field > 0 ) {
		echo "<!-- ", strtotime(date( 'Y-m-d')), ' ', strtotime($inv_dateField), " -->\n";
		$overdue_days = (strtotime(date('Y-m-d')) - strtotime($calc_dateField)) / (60 * 60 * 24);
		if ($overdue_days == 0) {
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
		}
	}		
	else {
		$overdue ="";
	}

	#Overdue - number of days - end


        $print_invoice_preference ="select pref_inv_wording from {$tb_prefix}preferences where pref_id =$inv_preferenceField";
        $result_print_invoice_preference = mysql_query($print_invoice_preference, $conn) or die(mysql_error());

        while ($Array = mysql_fetch_array($result_print_invoice_preference)) {
                $invoice_preference_wordingField = $Array['pref_inv_wording'];

				
	$defaults = getSystemDefaults();
	
       $url_pdf = "{$_SERVER['HTTP_HOST']}{$install_path}/index.php?module=invoices&view=templates/template&submit={$inv_idField}&action=view&location=pdf&invoice_style={$inv_ty_descriptionField}";
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
	 title="{$LANG['quick_view_tooltip']} {$invoice_preference_wordingField} {$inv_idField}"
	 href="index.php?module=invoices&view=quick_view&submit={$inv_idField}&invoice_style={$inv_ty_descriptionField}">
		<img src="images/common/view.png" height="16" border="-5px0" padding="-4px" valign="bottom" /><!-- print --></a>
	</a>
	<!-- Edit View -->
	<a class="index_table" title="{$LANG['edit_view_tooltip']} {$invoice_preference_wordingField} {$inv_idField}"
	 href="index.php?module=invoices&view=details&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}">
		<img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
	</a> 
	<!-- Print View -->
	<a class="index_table" title="{$LANG['print_preview_tooltip']} {$invoice_preference_wordingField} {$inv_idField}"
	href="index.php?module=invoices&view=templates/template&submit={$inv_idField}&action=view&location=print&invoice_style={$inv_ty_descriptionField}">
	<img src="images/common/printer.gif" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>
 
	<!-- EXPORT TO PDF -->
	<a title="{$LANG['export_tooltip']} {$invoice_preference_wordingField} {$inv_idField} {$LANG['export_pdf_tooltip']}"
	class="index_table" href="{$url_for_pdf}"><img src="images/common/pdf.jpg" height="16" padding="-4px" border="-5px" valign="bottom" /><!-- pdf --></a>

	<!--XLS -->
	<a title="{$LANG['export_tooltip']} {$invoice_preference_wordingField}{$inv_idField} {$LANG['export_xls_tooltip']} {$spreadsheet} {$LANG['format_tooltip']}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}&location=print&export={$spreadsheet}">
	 <img src="images/common/xls.gif" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>
	<!-- was href="index.php?module=invoices&view=templates/{$default['invoice']} now using simple template-->
	<!-- DOC -->
	<a title="{$LANG['export_tooltip']} {$invoice_preference_wordingField} {$inv_idField} {$LANG['export_doc_tooltip']} {$word_processor} {$LANG['format_tooltip']}"
	 class="index_table" href="index.php?module=invoices&view=templates/template&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}&location=print&export={$word_processor}">
	 <img src="images/common/doc.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>
       <!-- was href="index.php?module=invoices&view=templates/{$default['invoice']} now using the simple template-->
  <!-- Payment --><a title="{$LANG['process_payment']} {$invoice_preference_wordingField} {$inv_idField}"
   class="index_table" href="index.php?module=payments&view=process&submit={$inv_idField}&op=pay_selected_invoice">$</a>
	<!-- Email -->
	<a href="index.php?module=invoices&view=email&stage=1&submit={$inv_idField}" title="{$LANG['email']}  {$invoice_preference_wordingField} {$inv_idField}"><img src="images/common/mail-message-new.png" height="16" border="0" padding="-4px" valign="bottom" /></a>

	</td>
	<td class="index_table">{$inv_idField}</td>
	<td class="index_table">{$b_nameField}</td>
	<td class="index_table">{$c_nameField}</td>
	<td class="index_table">{$invoice_total_Field}</td>
	<!--
	<td class="index_table">{$invoice_paid_Field_format}</td>
	-->
	<td class="index_table">{$invoice_owing_Field}</td>
	<td class="index_table">{$overdue}</td>
	<td class="index_table">{$invoice_preference_wordingField}</td>
	<td class="index_table">{$inv_dateField}</td>
	</tr>

EOD;
									
								}
							}
						}
					}
				}		

	$display_block .="</table>";
}

?>
