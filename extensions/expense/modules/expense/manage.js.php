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
			url: 'index.php?module=expense&view=xml',
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : 9 * percentage_width, sortable : false, align: 'center'},
				{display: 'Date', name : 'date', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Amount', name : 'amount', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Account', name : 'expense_account_id', width : 16 * percentage_width, sortable : true, align: 'left'},
				{display: 'Biller', name : 'biller_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Customer', name : 'customer_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Invoice', name : 'invoice_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Product', name : 'product_id', width : 10 * percentage_width, sortable : true, align: 'left'}
				],
				

			searchitems : [
				{display: 'ID', name : 'id'},
				{display: 'Name', name : 'description', isdefault: true}
				],
			sortname: 'id',
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
