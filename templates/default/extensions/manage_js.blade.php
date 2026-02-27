<script type="text/javascript">
		var columns = 5;
		var padding = 12;
		var action_menu = 140;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=extensions&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: @json($LANG['actions'] ?? 'Actions'), name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['id'] ?? 'ID'), name : 'id', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['name'] ?? 'Name'), name : 'name', width : 25 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'description', width : 45 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['status'] ?? 'Status'), name : 'enabled', width : 15 * percentage_width, sortable : true, align: 'center'}
			],
			searchitems : [
				{display: @json($LANG['id'] ?? 'ID'), name : 'id'},
				{display: @json($LANG['name'] ?? 'Name'), name : 'name'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'description', isdefault: true}
			],
			sortname: 'name',
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
