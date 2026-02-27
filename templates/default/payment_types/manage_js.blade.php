<script type="text/javascript">
		var columns = 3;
		var padding = 12;
		var action_menu = 140;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=payment_types&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: @json($LANG['actions'] ?? 'Actions'), name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'pt_description', width : 70 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['enabled'] ?? 'Enabled'), name : 'enabled', width : 25 * percentage_width, sortable : true, align: 'center'}
			],
			searchitems : [
				{display: @json($LANG['id'] ?? 'ID'), name : 'pt_id'},
				{display: @json($LANG['description'] ?? 'Description'), name : 'pt_description', isdefault: true}
			],
			sortname: 'pt_description',
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
