<script type="text/javascript">
{literal}
			var columns = 5;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
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
