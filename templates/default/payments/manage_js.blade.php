<script type="text/javascript">
		var columns = 9;
		var padding = 12;
		var action_menu = 140;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid("#manageGrid", {
			url: @json('index.php?module=payments&view=xml&id=' . ($inv_id ?? '') . '&c_id=' . ($c_id ?? '')),
			dataType: 'xml',
			colModel : [
				{display: @json($LANG['actions'] ?? 'Actions'), name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['payment'] ?? 'Payment'), name : 'id', width : 7 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['invoice'] ?? 'Invoice'), name : 'ac_inv_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['customer'] ?? 'Customer'), name : 'customer', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['biller'] ?? 'Biller'), name : 'biller', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['amount'] ?? 'Amount'), name : 'ac_amount', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['notes'] ?? 'Notes'), name : 'ac_notes', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['type'] ?? 'Type'), name : 'description', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['date_upper'] ?? 'Date'), name : 'date', width : 10 * percentage_width, sortable : true, align: 'center'}
			],
			searchitems : [
				{display: @json($LANG['id'] ?? 'ID'), name : 'ap.id'},
				{display: @json($LANG['biller'] ?? 'Biller'), name : 'b.name', isdefault: true},
				{display: @json($LANG['customer'] ?? 'Customer'), name : 'c.name'}
			],
			sortname: @json($smarty['get']['sortname'] ?? 'id'),
			sortorder: @json($smarty['get']['sortorder'] ?? 'desc'),
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? 'Displaying items'),
			procmsg: @json($LANG['processing'] ?? 'Processing'),
			nomsg: @json($LANG['no_items'] ?? 'No items'),
			pagemsg: @json($LANG['page'] ?? 'Page'),
			ofmsg: @json($LANG['of'] ?? 'of'),
			useRp: false,
			rp: {{ (int)($smarty['get']['rp'] ?? 25) }},
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
