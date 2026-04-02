<script type="text/javascript">
		var columns = 3;
		var padding = 12;
		var action_menu = 30;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=preferences&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'pref_description', width : 70 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['status'] ?? 'Status'), name : 'enabled', width : 25 * percentage_width, sortable : true, align: 'center'}
			],
			statusLabels: { enabled: @json($LANG['enabled'] ?? 'Enabled'), disabled: @json($LANG['disabled'] ?? 'Disabled') },
			searchitems : [
				{display: @json($LANG['description'] ?? 'Description'), name : 'pref_description', isdefault: true}
			],
			searchLabel: @json($LANG['grid_search'] ?? 'Search'),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? 'Search')),
			sortname: 'pref_description',
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
