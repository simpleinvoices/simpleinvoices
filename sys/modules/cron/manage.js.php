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
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.id}{literal}', name : 'index_name', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.start_date_short}{literal}', name : 'start_date', width :25 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.end_date_short}{literal}', name : 'end_date', width :20 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.recur_each}{literal}', name : 'recurrence', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.email_biller}{literal}', name : 'email_biller', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.email_customer}{literal}', name : 'email_customer', width : 15 * percentage_width, sortable : true, align: 'left'}
				
				],
				/*
			buttons : [
				{name: 'Add', bclass: 'add', onpress : test},
				{name: 'Delete', bclass: 'delete', onpress : test},
				{separator: true}
				],
			*/
			searchitems : [
				{display: '{/literal}{$LANG.invoice_number}{literal}', name : 'iv.id'},
				{display: '{/literal}{$LANG.biller}{literal}', name : 'b.name'},
				{display: '{/literal}{$LANG.customer}{literal}', name : 'id', isdefault: true},
				{display: '{/literal}{$LANG.aging}{literal}', name : 'aging'}
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
