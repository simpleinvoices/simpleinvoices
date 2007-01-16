<?php

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block .= "<P><em>{$LANG_no_invoices}.</em></p>";
}else{
	$display_block .= <<<EOD

<div id="sorting">
	<div>Sorting tables, please hold on...</div>
</div>

<div id="top">{$page_header}</div>

<table width="85%" align="center" id="large" class="sortable filterable">
<tr class="sortHeader">
<th class="noFilter" width=25px>{$LANG_actions}</th>
<th class="index_table">{$LANG_id} &nbsp;</th>
<th class="selectFilter index_table">{$LANG_biller}</th>
<th class="selectFilter index_table">{$LANG_customer}</th>
<th class="index_table">{$LANG_total}</th>
<th class="index_table">{$LANG_paid}</th>
<th class="index_table">{$LANG_owing}</th>
<th class="selectFilter index_table">{$LANG_aging}</th>
<th class="selectFilter index_table">{$LANG_invoice_type}</th>
<th class="index_table">{$LANG_date_created}</th>
</tr>

EOD;

while ($newArray = mysql_fetch_array($result)) {
	$inv_idField = $newArray['inv_id'];
	$inv_biller_idField = $newArray['inv_biller_id'];
	$inv_customer_idField = $newArray['inv_customer_id'];
	$inv_typeField = $newArray['inv_type'];
	$inv_preferenceField = $newArray['inv_preference'];
	$inv_dateField = date( $config['date_format'], strtotime( $newArray['inv_date'] ) );
	$inv_noteField = $newArray['inv_note'];

	$sql_biller = "select b_name from si_biller where b_id = $inv_biller_idField ";
	$result_biller = mysql_query($sql_biller, $conn) or die(mysql_error());

	while ($billerArray = mysql_fetch_array($result_biller)) {
		$b_nameField = $billerArray['b_name'];


	$sql_customers = "select c_name from si_customers where c_id = $inv_customer_idField ";
	$result_customers = mysql_query($sql_customers, $conn) or die(mysql_error());

	while ($customersArray = mysql_fetch_array($result_customers)) {
		$c_nameField = $customersArray['c_name'];


	$sql_invoice_type = "select inv_ty_description from si_invoice_type where inv_ty_id = $inv_typeField ";
	$result_invoice_type = mysql_query($sql_invoice_type, $conn) or die(mysql_error());

	while ($invoice_typeArray = mysql_fetch_array($result_invoice_type)) {
		$inv_ty_descriptionField = $invoice_typeArray['inv_ty_description'];


#invoice total calc - start
	$print_invoice_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$inv_idField";
	$result_print_invoice_total = mysql_query($print_invoice_total, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_print_invoice_total)) {
                $invoice_total_Field = $Array['total'];
#invoice total calc - end

#amount paid calc - start
	$x1 = "select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) as amount from si_account_payments where ac_inv_id = $inv_idField";
	$result_x1 = mysql_query($x1, $conn) or die(mysql_error());
	while ($result_x1Array = mysql_fetch_array($result_x1)) {
		$invoice_paid_Field = number_format($result_x1Array['amount'],2);
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end

	#Overdue - number of days - start
	if ($invoice_owing_Field > 0 ) {
		echo "<!-- ", strtotime(date($config['date_format'])), ' ', strtotime($inv_dateField), " -->\n";
		$overdue_days = (strtotime(date($config['date_format'])) - strtotime($inv_dateField)) / (60 * 60 * 24);
		if ($overdue_days == 0) {
			$overdue = "";
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


        $print_invoice_preference ="select pref_inv_wording from si_preferences where pref_id =$inv_preferenceField";
        $result_print_invoice_preference = mysql_query($print_invoice_preference, $conn) or die(mysql_error());

        while ($Array = mysql_fetch_array($result_print_invoice_preference)) {
                $invoice_preference_wordingField = $Array['pref_inv_wording'];

	#system defaults query
	$print_defaults = "SELECT * FROM si_defaults WHERE def_id = 1";
	$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


	while ($Array_defaults = mysql_fetch_array($result_print_defaults) ) {
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
                $def_inv_templateField = $Array_defaults['def_inv_template'];
	
	$url_pdf = "{$_SERVER['HTTP_HOST']}{$install_path}/index.php?module=invoices&view=templates/{$def_inv_templateField}&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}";
	$url_pdf_encoded = urlencode($url_pdf);
        $url_for_pdf = "pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels={$pdf_screen_size}&media={$pdf_paper_size}&leftmargin={$pdf_left_margin}&rightmargin={$pdf_right_margin}&topmargin={$pdf_top_margin}&bottommargin={$pdf_bottom_margin}&transparency_workaround=1&imagequality_workaround=1&output=1&URL={$url_pdf_encoded}";


	$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table" nowrap>
	<!-- Quick View -->
	<a class="index_table"
	 title="{$LANG_quick_view_tooltip} {$invoice_preference_wordingField} {$inv_idField}"
	 href="index.php?module=invoices&view=quick_view&submit={$inv_idField}&invoice_style={$inv_ty_descriptionField}">V </a>
	<!-- Edit View -->
	<a class="index_table" title="{$LANG_edit_view_tooltip} {$invoice_preference_wordingField} {$inv_idField}"
	 href="index.php?module=invoices&view=details&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}">E </a> 
	<!-- Print View -->
	<a class="index_table" title="{$LANG_print_preview_tooltip} {$invoice_preference_wordingField} {$inv_idField}"
	href="index.php?module=invoices&view=templates/{$def_inv_templateField}&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}">
	<img src="themes/{$theme}/images/printer.gif" height="16" border="0" valign="bottom" /><!-- print --></a>
 
	<!-- EXPORT TO PDF -->
	<a title="{$LANG_export_tooltip} {$invoice_preference_wordingField} {$inv_idField} {$LANG_export_pdf_tooltip}"
	class="index_table" href="{$url_for_pdf}"><img src="themes/{$theme}/images/pdf.jpg" height="16" border="0" valign="bottom" /><!-- pdf --></a>

	<!--XLS -->
	<a title="{$LANG_export_tooltip} {$invoice_preference_wordingField}{$inv_idField} {$LANG_export_xls_tooltip} {$spreadsheet} {$LANG_format_tooltip}"
	 class="index_table" href="index.php?module=invoices&view=templates/simple&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}&export={$spreadsheet}">
	 <img src="themes/{$theme}/images/xls.gif" height="16" border="0" valign="bottom" /><!-- $spreadsheet --></a>
	<!-- was href="index.php?module=invoices&view=templates/{$def_inv_templateField} now using simple template-->
	<!-- DOC -->
	<a title="{$LANG_export_tooltip} {$invoice_preference_wordingField} {$inv_idField} {$LANG_export_doc_tooltip} {$word_processor} {$LANG_format_tooltip}"
	 class="index_table" href="index.php?module=invoices&view=templates/simple&submit={$inv_idField}&action=view&invoice_style={$inv_ty_descriptionField}&export={$word_processor}">
	 <img src="themes/{$theme}/images/doc.png" height="16" border="0" valign="bottom" /><!-- $word_processor --></a>
       <!-- was href="index.php?module=invoices&view=templates/{$def_inv_templateField} now using the simple template-->
  <!-- Payment --><a title="{$LANG_process_payment} {$invoice_preference_wordingField} {$inv_idField}"
   class="index_table" href="index.php?module=payments&view=process&submit={$inv_idField}&op=pay_selected_invoice">$</a>
	</td>
	<td class="index_table">{$inv_idField}</td>
	<td class="index_table">{$b_nameField}</td>
	<td class="index_table">{$c_nameField}</td>
	<td class="index_table">{$invoice_total_Field}</td>
	<td class="index_table">{$invoice_paid_Field}</td>
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
				}		
			}
		}

	$display_block .="</table>";
}

?>
