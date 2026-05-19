<script type="text/javascript">
		var columns = 6;
		var padding = 12;
		var action_menu = 50;
		var colEl = document.querySelector('.col');
		var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
		grid_width = grid_width - ((columns - 0.5) * padding) - action_menu;
		var percentage_width = grid_width / 100;

		siTablerGrid("#manageGrid", {
			url: @json($url ?? 'index.php?module=cron&view=xml'),
			dataType: 'xml',
			colModel : [
				{display: '', name : 'actions', width : action_menu, sortable : false, align: 'left', className: 'w-1'},
				{display: @json($LANG['start_date_short'] ?? ''), name : 'start_date', width : 22 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['end_date_short'] ?? ''), name : 'end_date', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['recur_each'] ?? ''), name : 'recurrence', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['email_biller'] ?? ''), name : 'email_biller', width : 15 * percentage_width, sortable : true, align: 'left'},
				{display: @json($LANG['email_customer'] ?? ''), name : 'email_customer', width : 15 * percentage_width, sortable : true, align: 'left'}
			],
			searchitems : [
				{display: @json($LANG['invoice_number'] ?? ''), name : 'iv.id'},
				{display: @json($LANG['biller'] ?? ''), name : 'b.name'},
				{display: @json($LANG['customer'] ?? ''), name : 'cron.id', isdefault: true},
				{display: @json($LANG['aging'] ?? ''), name : 'aging'}
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
