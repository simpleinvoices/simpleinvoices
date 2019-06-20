<script type="text/javascript">
{literal}
			var columns = 4;
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
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : action_menu - 50, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.date_upper}{literal}', name : 'date', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.product}{literal}', name : 'description', width :35 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.quantity}{literal}', name : 'quantity', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.cost_price}{literal}', name : 'cost', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.total_cost}{literal}', name : 'total_cost', width :20 * percentage_width, sortable : true, align: 'left'}
				
				],
				/*
			buttons : [
				{name: 'Add', bclass: 'add', onpress : test},
				{name: 'Delete', bclass: 'delete', onpress : test},
				{separator: true}
				],
			*/
			searchitems : [
				{display: '{/literal}{$LANG.product}{literal}', name : 'p.description', isdefault: true},
				{display: '{/literal}{$LANG.date_upper}{literal}', name : 'iv.date'},
				{display: '{/literal}{$LANG.quantity}{literal}', name : 'iv.quantity'},
				{display: '{/literal}{$LANG.cost_price}{literal}', name : 'iv.cost'},
				{display: '{/literal}{$LANG.total_cost}{literal}', name : 'iv.quantity * iv.cost'}
				],
			sortname: "id",
			sortorder: "desc",
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			pagestat: '{/literal}{$LANG.displaying_items}{literal}',
			procmsg: '{/literal}{$LANG.processing}{literal}',
			nomsg: '{/literal}{$LANG.no_items}{literal}',
			pagemsg: '{/literal}{$LANG.page}{literal}',
			ofmsg: '{/literal}{$LANG.of}{literal}',
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
