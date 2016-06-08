<script>
{literal}
			var columns = 6;
			var padding = 12;
			var grid_width = $('.col').width();

			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100;

			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=user&view=xml',
			dataType: 'xml',
            // @formatter:off
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}' , name : 'actions' , width :  8 * percentage_width, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.username}{literal}', name : 'username', width : 30 * percentage_width, sortable : true , align: 'left'},
                {display: '{/literal}{$LANG.email}{literal}'   , name : 'email'   , width : 32 * percentage_width, sortable : true , align: 'left'},
				{display: '{/literal}{$LANG.role}{literal}'    , name : 'role'    , width : 15 * percentage_width, sortable : true , align: 'left'},
				{display: '{/literal}{$LANG.enabled}{literal}' , name : 'enabled' , width :  8 * percentage_width, sortable : true , align: 'left'},
                {display: '{/literal}{$LANG.users}{literal}'   , name : 'user_id' , width :  8 * percentage_width, sortable : true, align: 'left'}
			],

			searchitems : [
                {display: '{/literal}{$LANG.username}{literal}', name : 'username', isdefault: true},
                {display: '{/literal}{$LANG.email}{literal}'   , name : 'email'},
				{display: '{/literal}{$LANG.role}{literal}'    , name : 'ur.name'}
			],
            // @formatter:on
			sortname: 'username',
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
