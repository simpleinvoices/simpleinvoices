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
				{display: @json($LANG['id'] ?? ''), name : 'index_id', width :15 * percentage_width, sortable : true, align: 'left', mobileFormatter: function(val) {
					// "Invoice 0001" → "Inv.. ..001"
					var sp = val.indexOf(' ');
					if (sp > 0) {
						var num = val.substring(sp + 1);
						return val.substring(0, 3) + '.. ..' + num.slice(-3);
					}
					return val;
				}},
				{display: @json($LANG['biller'] ?? ''), name : 'biller', width :20 * percentage_width, sortable : true, align: 'left', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['customer'] ?? ''), name : 'customer', width :20 * percentage_width, sortable : true, align: 'left', mobileFormatter: function(val) {
					return val.length > 15 ? val.substring(0, 15) + '\u2026' : val;
				}},
				{display: @json($LANG['date_upper'] ?? ''), name : 'date', width : 15 * percentage_width, sortable : true, align: 'right', className: 'd-none d-sm-table-cell'},
				{display: @json($LANG['total'] ?? ''), name : 'invoice_total', width : 15 * percentage_width, sortable : true, align: 'right'},
				{display: @json($LANG['status'] ?? ''), name : 'status', width : 15 * percentage_width, sortable : true, align: 'center', displayMobile: ''}
			],
			searchitems : [
				{display: @json($LANG['invoice_number'] ?? ''), name : 'iv.index_id'},
				{display: @json($LANG['biller'] ?? ''), name : 'b.name'},
				{display: @json($LANG['customer'] ?? ''), name : 'c.name', isdefault: true}
			],
			searchLabel: @json($LANG['grid_search'] ?? ''),
			searchPlaceholder: @json($LANG['grid_search_placeholder'] ?? ($LANG['grid_search'] ?? '')),
			sortname: @json(get('sortname', 'index_id')),
			sortorder: @json(get('sortorder', 'desc')),
			usepager: true,
			pagestat: @json($LANG['displaying_items'] ?? ''),
			procmsg: @json($LANG['processing'] ?? ''),
			nomsg: @json($LANG['no_items'] ?? ''),
			pagemsg: @json($LANG['page'] ?? ''),
			ofmsg: @json($LANG['of'] ?? ''),
			useRp: true,
			rpOptions: [10, 20, 50, 100],
			rp: {{ max(10, (int)(get('rp') ?? 10)) }},
			largeDataset: {{ ($large_dataset ?? '') === ($LANG['enabled'] ?? '') ? 'true' : 'false' }},
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
		}
		);
</script>
