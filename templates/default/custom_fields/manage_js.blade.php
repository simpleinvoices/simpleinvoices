<script type="text/javascript">
		var columns = 4;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=custom_fields&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: @json($LANG['id'] ?? 'ID'), name : 'cf_id', width : 10 * percentage_width, sortable : false, align: 'left'},
				{display: @json($LANG['custom_field'] ?? 'Custom Field'), name : 'cf_custom_field', width : 40 * percentage_width, sortable : false, align: 'left'},
				{display: @json($LANG['custom_label'] ?? 'Custom Label'), name : 'cf_custom_label', width : 45 * percentage_width, sortable : false, align: 'left'}
			],
			sortname: 'cf_id',
			sortorder: 'asc',
			usepager: false,
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
