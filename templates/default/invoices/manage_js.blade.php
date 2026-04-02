<script type="text/javascript">
		var columns = 7;
		var padding = 12;
		var action_menu = 20;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns -0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid("#manageGrid", {
			url: @json($url ?? 'index.php?module=invoices&view=xml'),
			dataType: 'xml',
			useCard: false,
			toolbarSelector: '#manageGridToolbar',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['id'] ?? 'ID'), name : 'index_id', width :15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['biller'] ?? 'Biller'), name : 'biller', width :20 * percentage_width, sortable : true, align: 'left', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['customer'] ?? 'Customer'), name : 'customer', width :20 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['date_upper'] ?? 'Date'), name : 'date', width : 15 * percentage_width, sortable : true, align: 'right', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['total'] ?? 'Total'), name : 'invoice_total', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['status'] ?? 'Status'), name : 'status', width : 15 * percentage_width, sortable : true, align: 'center', displayMobile: ''}
			],
			searchitems : [
				{display: @json($LANG['invoice_number'] ?? 'Invoice #'), name : 'iv.index_id'},
				{display: @json($LANG['biller'] ?? 'Biller'), name : 'b.name'},
				{display: @json($LANG['customer'] ?? 'Customer'), name : 'c.name', isdefault: true}
			],
			searchLabel: @json($LANG['grid_search'] ?? 'Search'),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? 'Search')),
			sortname: @json(get('sortname', 'index_id')),
			sortorder: @json(get('sortorder', 'desc')),
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? 'Displaying items'),
			procmsg: @json($LANG['processing'] ?? 'Processing'),
			nomsg: @json($LANG['no_items'] ?? 'No items'),
			pagemsg: @json($LANG['page'] ?? 'Page'),
			ofmsg: @json($LANG['of'] ?? 'of'),
			useRp: true,
			rpOptions: [10, 20, 50, 100],
			rp: {{ max(10, (int)(get('rp') ?? 10)) }},
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		}
		);
</script>
