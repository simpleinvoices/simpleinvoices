<script type="text/javascript">
		var columns = 5;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=tax_rates&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['id'] ?? 'ID'), name : 'tax_id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'tax_description', width : 50 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['rate'] ?? 'Rate'), name : 'tax_percentage', width : 20 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['status'] ?? 'Status'), name : 'enabled', width : 15 * percentage_width, sortable : true, align: 'center'}
			],
			statusLabels: { enabled: @json($LANG['enabled'] ?? 'Enabled'), disabled: @json($LANG['disabled'] ?? 'Disabled') },
			searchitems : [
				{display: @json($LANG['id'] ?? 'ID'), name : 'tax_id'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'tax_description', isdefault: true},
				{display: @json($LANG['tax_percentage'] ?? 'Tax %'), name : 'tax_percentage'}
			],
			sortname: 'tax_description',
			sortorder: 'asc',
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? 'Displaying items'),
			procmsg: @json($LANG['processing'] ?? 'Processing'),
			nomsg: @json($LANG['no_items'] ?? 'No items'),
			pagemsg: @json($LANG['page'] ?? 'Page'),
			ofmsg: @json($LANG['of'] ?? 'of'),
			rp: 10,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
