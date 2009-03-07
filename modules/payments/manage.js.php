<script type="text/javascript">
{literal}

/*
var quick_view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_view_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
var print_preview_tooltip = "{/literal}{$LANG.print_preview_tooltip} {$invoice.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
var export_tooltip = "{/literal}{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {ldelim}1{rdelim} {$LANG.export_pdf_tooltip}{literal}";
var export_xls_tooltip = "{/literal}{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {ldelim}1{rdelim} {$LANG.export_xls_tooltip} {$spreadsheet} {$LANG.format_tooltip}{literal}"
var export_word_tooltip = "{/literal}{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {ldelim}1{rdelim} {$LANG.export_doc_tooltip} {$word_processor} {$LANG.format_tooltip}{literal}";
var process_payment_tooltip = "{/literal}{$LANG.process_payment} {$invoice.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
var email_tooltip = "{/literal}{$LANG.email}  {$invoice.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";



		'<!--0 Quick View --><a class="index_table" title="'+  +''+ quick_view_tooltip +'" href="index.php?module=invoices&view=quick_view&invoice={1}"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /></a>',
		'<!--1 Edit View --><a class="index_table" title="'+ edit_view_tooltip +'" href="index.php?module=invoices&view=details&invoice={1}&action=view"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
		'<!--2 Print View --><a class="index_table" title="'+ print_preview_tooltip +'" href="index.php?module=invoices&view=templates/template&invoice={1}&action=view&location=print"><img src="images/common/printer.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
	'<!--3 EXPORT TO PDF --><a title="'+export_tooltip+'"	class="index_table" href="pdfmaker.php?id={1}"><img src="images/common/page_white_acrobat.png" height="16" padding="-4px" border="-5px" valign="bottom" /><!-- pdf --></a>',
	'<!--4 XLS --><a title="'+ export_xls_tooltip +'" class="index_table" href="index.php?module=invoices&view=templates/template&invoice={1}&action=view&location=print&export={$spreadsheet}"><img src="images/common/page_white_excel.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>',
	'<!--5 DOC --><a title="'+ export_word_tooltip +'" class="index_table" href="index.php?module=invoices&view=templates/template&invoice={1}&action=view&location=print&export={$word_processor}"><img src="images/common/page_white_word.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>',
		'<!--6 Payment --><a title="'+ process_payment_tooltip +'" class="index_table" href="index.php?module=payments&view=process&invoice={1}&op=pay_selected_invoice"><img src="images/common/money_dollar.png" height="16" border="0" padding="-4px" valign="bottom" /></a>',
*/

			var c_id ;
			c_id = '{/literal}{$get.c_id}{literal}';
			var id;
			id = '{/literal}{$get.id}{literal}';

			var url_extension = "";

			if(c_id !== "")
			{
				url_extension = "&c_id="+c_id;
				
			};
			if(id !== "")
			{
				url_extension = "&id="+id;
		
			};

			var columns = 9;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
			
			function test(com,grid)
			{
				if (com=='Delete')
					{
						confirm('Delete ' + $('.trSelected',grid).length + ' items?')
					}
				else if (com=='Add')
					{
						alert('Add New Item');
					}			
			}

		//	console.log("URL: %s",url_extension);
			$("#manageGrid").flexigrid
			(
			{
			url: 'index.php?module=payments&view=xml'+url_extension,
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : 08 * percentage_width, sortable : false, align: 'center'},
				{display: 'Payment', name : 'id', width :07 * percentage_width, sortable : true, align: 'center'},
				{display: 'Invoice', name : 'ac_inv_id', width :05 * percentage_width, sortable : true, align: 'left'},
				{display: 'Customer', name : 'customer', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Biller', name : 'biller', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Amount', name : 'ac_amount', width :10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Notes', name : 'ac_notes', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Type', name : 'description', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Date', name : 'date', width : 10 * percentage_width, sortable : true, align: 'left'},
		
				],
				/*
			buttons : [
				{name: 'Add', bclass: 'add', onpress : test},
				{name: 'Delete', bclass: 'delete', onpress : test},
				{separator: true}
				],
			*/
			searchitems : [
				{display: 'ID', name : 'id'},
				{display: 'Biller ID', name : 'biller_id', isdefault: true}
				],
			sortname: "id",
			sortorder: "desc",
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			useRp: false,
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
			}
			);
{/literal}

</script>
