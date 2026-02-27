<script type="text/javascript">
		var inventory = @json($defaults['inventory'] ?? '0');
		var columns = (inventory == '1') ? 6 : 5;
		var padding = 12;
		var action_menu = 140;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		var col_model;
		if (inventory == '1') {
			col_model = [
				{display: @json($LANG['actions'] ?? 'Actions'), name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['id'] ?? 'ID'), name : 'id', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['name'] ?? 'Name'), name : 'description', width : 40 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['unit_price'] ?? 'Unit Price'), name : 'unit_price', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['quantity'] ?? 'Quantity'), name : 'quantity', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['enabled'] ?? 'Enabled'), name : 'enabled', width : 15 * percentage_width, sortable : true, align: 'center'}
			];
		} else {
			col_model = [
				{display: @json($LANG['actions'] ?? 'Actions'), name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['id'] ?? 'ID'), name : 'id', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['name'] ?? 'Name'), name : 'description', width : 50 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['unit_price'] ?? 'Unit Price'), name : 'unit_price', width : 20 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['enabled'] ?? 'Enabled'), name : 'enabled', width : 15 * percentage_width, sortable : true, align: 'center'}
			];
		}

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=products&view=xml'),
			dataType: 'xml',
			colModel : col_model,
			searchitems : [
				{display: @json($LANG['id'] ?? 'ID'), name : 'id'},
				{display: @json($LANG['name'] ?? 'Name'), name : 'description', isdefault: true},
				{display: @json($LANG['unit_price'] ?? 'Unit Price'), name : 'unit_price'}
			],
			sortname: 'description',
			sortorder: 'asc',
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? 'Displaying items'),
			procmsg: @json($LANG['processing'] ?? 'Processing'),
			nomsg: @json($LANG['no_items'] ?? 'No items'),
			pagemsg: @json($LANG['page'] ?? 'Page'),
			ofmsg: @json($LANG['of'] ?? 'of'),
			useRp: false,
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
