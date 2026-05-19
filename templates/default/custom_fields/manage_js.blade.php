<script type="text/javascript">
		var columns = 3;
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
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['custom_field'] ?? ''), name : 'cf_custom_field', width : 45 * percentage_width, sortable : false, align: 'left'},
				{display: @json($LANG['custom_label'] ?? ''), name : 'cf_custom_label', width : 50 * percentage_width, sortable : false, align: 'left'}
			],
			sortname: 'cf_custom_field',
			sortorder: 'asc',
			usepager: false,
			pagestat: @json($LANG['displaying_items'] ?? ''),
			procmsg: @json($LANG['processing'] ?? ''),
			nomsg: @json($LANG['no_items'] ?? ''),
			pagemsg: @json($LANG['page'] ?? ''),
			ofmsg: @json($LANG['of'] ?? ''),
			rp: 1000,
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
