<script type="text/javascript">

{literal}
/*
		'<!--0 Quick View --><a class="index_table" href="index.php?module=biller&view=details&id={1}&action=view"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /></a>',
		'<!--1 Edit View --><a class="index_table" href="index.php?module=biller&view=details&id={1}&action=edit"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
*/	
			var columns = 5;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=billers&amp;view=xml',
			dataType: 'xml',
			colModel : [
				{display: 'Actions', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				{display: 'Name', name : 'name', width : 40 * percentage_width, sortable : true, align: 'left'},
				{display: 'Email', name : 'email', width : 30 * percentage_width, sortable : true, align: 'left'},
				{display: 'Enabled', name : 'enabled', width : 20 * percentage_width, sortable : true, align: 'left'}
				
				],
				

			searchitems : [
				{display: 'Name', name : 'name', isdefault: true},
				{display: 'Email', name : 'email'}
			],
			sortname: 'name',
			sortorder: 'asc',
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			useRp: false,
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
			}
			);
	
		
{/literal}

</script>
