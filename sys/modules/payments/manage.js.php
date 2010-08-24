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

			url: 'index.php?module=payments&view=xml&id={/literal}{$inv_id}{literal}&c_id={/literal}{$c_id}{literal}',
			dataType: 'xml',
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 08 * percentage_width, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.payment}{literal}', name : 'id', width :07 * percentage_width, sortable : true, align: 'center'},
				{display: '{/literal}{$LANG.invoice}{literal}', name : 'ac_inv_id', width :10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.customer}{literal}', name : 'customer', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.biller}{literal}', name : 'biller', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.amount}{literal}', name : 'ac_amount', width :10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.notes}{literal}', name : 'ac_notes', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.type}{literal}', name : 'description', width :10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.date_upper}{literal}', name : 'date', width : 10 * percentage_width, sortable : true, align: 'left'}
		
				],
				/*
			buttons : [
				{name: 'Add', bclass: 'add', onpress : test},
				{name: 'Delete', bclass: 'delete', onpress : test},
				{separator: true}
				],
			*/
			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'ap.id'},
				{display: '{/literal}{$LANG.biller_id}{literal}', name : 'biller_id', isdefault: true}
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
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
			}
			);
{/literal}

</script>
