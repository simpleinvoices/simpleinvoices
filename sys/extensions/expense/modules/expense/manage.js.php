<script type="text/javascript">

{literal}

var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";

			var columns = 5;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: "{/literal}{$url}{literal}",
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : 9 * percentage_width, sortable : false, align: 'center'},
				{display: 'Date', name : 'date', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Amount', name : 'amount', width : 7.5 * percentage_width, sortable : true, align: 'left'},
				{display: 'Tax', name : 'tax', width : 7.5 * percentage_width, sortable : true, align: 'left'},
				{display: 'Total', name : 'total', width : 7.5 * percentage_width, sortable : true, align: 'left'},
				{display: 'Account', name : 'expense_account_id', width : 13.5 * percentage_width, sortable : true, align: 'left'},
				{display: 'Biller', name : 'biller_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Customer', name : 'customer_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Invoice', name : 'invoice_id', width : 5 * percentage_width, sortable : true, align: 'left'},
				{display: 'Status', name : 'status', width : 15 * percentage_width, sortable : true, align: 'left'}
				],
				

			sortname: 'EID',
			sortorder: 'desc',
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			useRp: false,
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
			}
			);


{/literal}

</script>
