<script type="text/javascript">

{literal}

var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
var inventory = "{/literal}{$defaults.inventory}{literal}";


			var columns = 5;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
            /*
            * If Inventory in SImple Invoices is enabled than show quantity etc..
            */
    
            if(inventory == '1')
            {
                col_model = [ 
				    {display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				    {display: '{/literal}{$LANG.name}{literal}', name : 'description', width : 50 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price', width : 10 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.quantity}{literal}', name : 'quantity', width : 10 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 20 * percentage_width, sortable : true, align: 'left'}
				];
            } else {
                col_model = [ 
				    {display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
                    {display: '{/literal}{$LANG.name}{literal}', name : 'description', width : 50 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price', width : 20 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 20 * percentage_width, sortable : true, align: 'left'}
				];
            }



			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=products&view=xml',
			dataType: 'xml',
			colModel : col_model,
			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'id'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'description', isdefault: true}
				],
			sortname: 'description',
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
