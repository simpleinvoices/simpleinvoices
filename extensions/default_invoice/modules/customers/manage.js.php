<script type="text/javascript">


{literal}
/*
var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
'<!--0 Quick View --><a class="index_table" href="index.php?module=customers&view=details&id={1}&action=view"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" alt="" /></a>',
'<!--1 Edit View --><a class="index_table" href="index.php?module=customers&view=details&id={1}&action=edit"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" alt="" /><!-- print --></a>',
*/


			var columns = 7;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=customers&view=xml',
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				{display: 'ID', name : 'id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Name', name : 'name', width : 40 * percentage_width, sortable : true, align: 'left'},
				{display: 'Last Invoice', name : 'last_invoice', width : 10 * percentage_width, sortable : false, align: 'right'},
				{display: 'Unit Price', name : 'customer_total', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Owing', name : 'owing', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Enabled', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'left'}
				
				],
				

			searchitems : [
				{display: 'ID', name : 'id'},
				{display: 'Name', name : 'name', isdefault: true}
				],
			sortname: 'name',
			sortorder: 'asc',
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
