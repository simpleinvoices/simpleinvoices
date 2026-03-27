<script type="text/javascript">
{literal}
			var columns = 5;
			var padding = 12;
			var colEl = document.querySelector('.col');
			var grid_width = colEl ? colEl.getBoundingClientRect().width : 800;
			grid_width = grid_width - (columns * padding);
			var percentage_width = grid_width / 100; 
		
			
			siTablerGrid('#manageGrid', {
			url: 'index.php?module=product_attribute&view=xml',
			dataType: 'xml',
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.id}{literal}', name : 'id', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'name', width : 40 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 20 * percentage_width, sortable : true, align: 'center'},
				{display: '{/literal}{$LANG.visible}{literal}', name : 'visible', width : 20 * percentage_width, sortable : true, align: 'center'}
				],

			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'id'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'name', isdefault: true}
				],
			searchLabel: '{/literal}{$LANG.grid_search}{literal}',
			searchPlaceholder: '{/literal}{$LANG.grid_search_placeholder}{literal}',
			sortname: 'id',
			sortorder: 'asc',
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			pagestat: '{/literal}{$LANG.displaying_items}{literal}',
			procmsg: '{/literal}{$LANG.processing}{literal}',
			nomsg: '{/literal}{$LANG.no_items}{literal}',
			pagemsg: '{/literal}{$LANG.page}{literal}',
			ofmsg: '{/literal}{$LANG.of}{literal}',
			useRp: false,
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
			}
			);
{/literal}
</script>
