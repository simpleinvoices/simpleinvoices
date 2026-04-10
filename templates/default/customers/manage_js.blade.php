<script type="text/javascript">
		var columns = 6;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=customers&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['name'] ?? ''), name : 'name', width : 35 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['total'] ?? ''), name : 'customer_total', width : 12 * percentage_width, sortable : true, align: 'right', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['paid'] ?? ''), name : 'paid', width : 12 * percentage_width, sortable : true, align: 'right', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['owing'] ?? ''), name : 'owing', width : 12 * percentage_width, sortable : true, align: 'right', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['status'] ?? ''), name : 'enabled', width : 8 * percentage_width, sortable : true, align: 'center'}
			],
			statusLabels: { enabled: @json($LANG['enabled'] ?? ''), disabled: @json($LANG['disabled'] ?? '') },
			searchitems : [
				{display: @json($LANG['name'] ?? ''), name : 'c.name', isdefault: true}
			],
			searchLabel: @json($LANG['grid_search'] ?? ''),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
			sortname: 'name',
			sortorder: 'asc',
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? ''),
			procmsg: @json($LANG['processing'] ?? ''),
			nomsg: @json($LANG['no_items'] ?? ''),
			pagemsg: @json($LANG['page'] ?? ''),
			ofmsg: @json($LANG['of'] ?? ''),
			rp: 10,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
