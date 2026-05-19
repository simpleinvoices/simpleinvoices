<script type="text/javascript">
	var columns = 4;
	var padding = 12;
	var action_menu = 30;
	var colEl = document.querySelector('.col');
	var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
	grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
	var percentage_width = grid_width / 100;

	siTablerGrid('#manageGrid', {
		url: @json($url ?? 'index.php?module=currencies&view=xml'),
		dataType: 'xml',
		colModel : [
			{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
			{display: @json($LANG['currency_code'] ?? 'Code'), name : 'currency_code', width : 25 * percentage_width, sortable : true, align: 'left'},
			{display: @json($LANG['currency_sign'] ?? 'Symbol'), name : 'currency_sign', width : 25 * percentage_width, sortable : true, align: 'left'},
			{display: @json($LANG['currency_position'] ?? 'Position'), name : 'currency_position', width : 25 * percentage_width, sortable : true, align: 'center'}
		],
		searchitems : [
			{display: @json($LANG['currency_code'] ?? 'Code'), name : 'currency_code', isdefault: true},
			{display: @json($LANG['currency_sign'] ?? 'Symbol'), name : 'currency_sign', isdefault: false}
		],
		searchLabel: @json($LANG['grid_search'] ?? ''),
		searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
		sortname: 'currency_code',
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
