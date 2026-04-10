<script type="text/javascript">
		var columns = 7;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid("#manageGrid", {
			url: @json('index.php?module=payments&view=xml&id=' . ($inv_id ?? '') . '&c_id=' . ($c_id ?? '')),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['payment'] ?? ''), name : 'id', width : 7 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['invoice'] ?? ''), name : 'ac_inv_id', width : 10 * percentage_width, sortable : true, align: 'left', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['customer'] ?? ''), name : 'customer', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['biller'] ?? ''), name : 'biller', width : 15 * percentage_width, sortable : true, align: 'left', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['amount'] ?? ''), name : 'ac_amount', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['date_upper'] ?? ''), name : 'date', width : 10 * percentage_width, sortable : true, align: 'center', className: 'd-none d-sm-table-cell'}
			],
			searchitems : [
				{display: @json($LANG['id'] ?? ''), name : 'ap.id'},
				{display: @json($LANG['biller'] ?? ''), name : 'b.name', isdefault: true},
				{display: @json($LANG['customer'] ?? ''), name : 'c.name'}
			],
			searchLabel: @json($LANG['grid_search'] ?? ''),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
			sortname: @json(get('sortname', 'id')),
			sortorder: @json(get('sortorder', 'desc')),
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? ''),
			procmsg: @json($LANG['processing'] ?? ''),
			nomsg: @json($LANG['no_items'] ?? ''),
			pagemsg: @json($LANG['page'] ?? ''),
			ofmsg: @json($LANG['of'] ?? ''),
			rp: {{ (int) get('rp', 10) }},
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		});
</script>
