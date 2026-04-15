<script type="text/javascript">
		var inventory = @json($defaults['inventory'] ?? '0');
		var columns = (inventory == '1') ? 5 : 4;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		var col_model;
		if (inventory == '1') {
			col_model = [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['name'] ?? ''), name : 'description', width : 50 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['unit_price'] ?? ''), name : 'unit_price', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['quantity'] ?? ''), name : 'quantity', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['status'] ?? ''), name : 'enabled', width : 15 * percentage_width, sortable : true, align: 'center'}
			];
		} else {
			col_model = [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['name'] ?? ''), name : 'description', width : 60 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['unit_price'] ?? ''), name : 'unit_price', width : 20 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['status'] ?? ''), name : 'enabled', width : 15 * percentage_width, sortable : true, align: 'center'}
			];
		}

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=products&view=xml'),
			dataType: 'xml',
			colModel : col_model,
			statusLabels: { enabled: @json($LANG['enabled'] ?? ''), disabled: @json($LANG['disabled'] ?? '') },
			searchitems : [
				{display: @json($LANG['name'] ?? ''), name : 'description', isdefault: true},
				{display: @json($LANG['unit_price'] ?? ''), name : 'unit_price'}
			],
			searchLabel: @json($LANG['grid_search'] ?? ''),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
			sortname: 'description',
			sortorder: 'asc',
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? ''),
			procmsg: @json($LANG['processing'] ?? ''),
			nomsg: @json($LANG['no_items'] ?? ''),
			pagemsg: @json($LANG['page'] ?? ''),
			ofmsg: @json($LANG['of'] ?? ''),
			useRp: true,
			rpOptions: [10, 20, 50, 100],
			rp: {{ max(10, (int)(get('rp') ?? 10)) }},
			largeDataset: {{ ($large_dataset ?? '') === ($LANG['enabled'] ?? '') ? 'true' : 'false' }},
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
