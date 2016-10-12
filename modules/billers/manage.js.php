<script type="text/javascript">

{literal}
/*
		'<!--0 Quick View --><a class="index_table" href="index.php?module=biller&view=details&id={1}&action=view"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /></a>',
		'<!--1 Edit View --><a class="index_table" href="index.php?module=biller&view=details&id={1}&action=edit"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
*/	
			var columns = 6;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=billers&view=xml',
			dataType: 'xml',
			'onError': function(data) {
				$("#manageGrid").flexAddData(null);
			},
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.id}{literal}', name : 'id', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'name', width : 40 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.email}{literal}', name : 'email', width : 30 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'center'}
				
				],
				

			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'id'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'name', isdefault: true},
				{display: '{/literal}{$LANG.email}{literal}', name : 'email'}
			],
			sortname: 'name',
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
