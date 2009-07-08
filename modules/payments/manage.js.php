<script type="text/javascript">

{literal}

			var columns = 9;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 

		//	console.log("URL: %s",url_extension);
			$("#manageGrid").flexigrid
			(
			{

			url: 'index.php?module=payments&amp;view=xml',
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
				{display: 'ID', name : 'ap.id'},
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
