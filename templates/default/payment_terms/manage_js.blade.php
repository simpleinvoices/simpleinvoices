<script type="text/javascript">
		var columns = 6;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid('#manageGrid', {
			url: @json($url ?? 'index.php?module=payment_terms&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['payment_term_code'] ?? 'Code'), name : 'term_code', width : 14 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['payment_term_label'] ?? 'Label'), name : 'term_label', width : 28 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['payment_term_calc_kind'] ?? 'Calculation'), name : 'calc_kind', width : 18 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['payment_term_param'] ?? 'Parameter'), name : 'param_int', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['payment_term_sort_order'] ?? 'Sort'), name : 'sort_order', width : 10 * percentage_width, sortable : true, align: 'right'}
			],
			searchitems : [
				{display: @json($LANG['payment_term_code'] ?? 'Code'), name : 'term_code', isdefault: true},
				{display: @json($LANG['payment_term_label'] ?? 'Label'), name : 'term_label', isdefault: false}
			],
			searchLabel: @json($LANG['grid_search'] ?? ''),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
			sortname: 'sort_order',
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
