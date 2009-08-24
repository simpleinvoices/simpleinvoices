<script type="text/javascript">
{literal}
			var columns = 8;
			var padding = 12;
			var action_menu = 140;
			var grid_width = $('.col').width();
			//var url = 'index.php?module=invoices&view=xml';
			
			grid_width = grid_width - (columns * padding) - action_menu;
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


			$("#manageGrid").flexigrid
			(
			{
			url: "{/literal}{$url}{literal}",
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: 'ID', name : 'id', width :05 * percentage_width, sortable : true, align: 'center'},
				{display: 'Biller', name : 'biller', width :25 * percentage_width, sortable : true, align: 'left'},
				{display: 'Customer', name : 'customer', width :25 * percentage_width, sortable : true, align: 'left'},
				{display: 'Date', name : 'date', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: 'Total', name : 'invoice_total', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Owing', name : 'owing', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: 'Aging', name : 'aging', width : 10 * percentage_width, sortable : true, align: 'left'}
				
				],
				/*
			buttons : [
				{name: 'Add', bclass: 'add', onpress : test},
				{name: 'Delete', bclass: 'delete', onpress : test},
				{separator: true}
				],
			*/
			searchitems : [
				{display: 'Invoice Number', name : 'iv.id'},
				{display: 'Biller', name : 'b.name'},
				{display: 'Customer', name : 'c.name', isdefault: true},
				{display: 'Aging', name : 'aging'}
				],
			sortname: "id",
			sortorder: "desc",
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			useRp: false,
			rp: 15,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
			}
			);
{/literal}

</script>
