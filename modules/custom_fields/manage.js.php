<script type="text/javascript">
{literal}
			var columns = 4;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=custom_fields&view=xml',
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				{display: 'ISO', name : 'id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Name', name : 'biller_id', width : 40 * percentage_width, sortable : true, align: 'left'},
				{display: 'Printable Name', name : 'customer_id', width : 40 * percentage_width, sortable : true, align: 'left'}
				],

			searchitems : [
				{display: 'ISO', name : 'id'},
				{display: 'Name', name : 'name', isdefault: true}
				],
			sortname: 'id',
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
