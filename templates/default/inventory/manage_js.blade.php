<script type="text/javascript">
		var columns = 6;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid("#manageGrid", {
			url: @json($url ?? 'index.php?module=inventory&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['date_upper'] ?? ''), name : 'date', width : 15 * percentage_width, sortable : true, align: 'center'},
				{display: @json($LANG['product'] ?? ''), name : 'description', width : 30 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['quantity'] ?? ''), name : 'quantity', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['cost_price'] ?? ''), name : 'cost', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['total_cost'] ?? ''), name : 'total_cost', width : 20 * percentage_width, sortable : true, align: 'right'}
			],
			searchitems : [
				{display: @json($LANG['product'] ?? ''), name : 'p.description', isdefault: true},
				{display: @json($LANG['date_upper'] ?? ''), name : 'iv.date'},
				{display: @json($LANG['quantity'] ?? ''), name : 'iv.quantity'},
				{display: @json($LANG['cost_price'] ?? ''), name : 'iv.cost'},
				{display: @json($LANG['total_cost'] ?? ''), name : 'iv.quantity * iv.cost'}
			],
			searchLabel: @json($LANG['grid_search'] ?? ''),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
			sortname: 'id',
			sortorder: 'desc',
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
