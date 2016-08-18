<script type="text/javascript">
{literal}
var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
var inventory = "{/literal}{$defaults.inventory}{literal}";

			var columns = 6;/*5*/
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
				    {display: '{/literal}{$LANG.id}{literal}', name : 'id', width : 5 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.name}{literal}', name : 'description', width : 45 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$cfs.product_cf1}{literal}', name : 'product_cf1', width : 15 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.quantity}{literal}', name : 'quantity', width : 5 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 9 * percentage_width, sortable : true, align: 'center'}
				];
            } else {
                col_model = [ 
				    {display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				    {display: '{/literal}{$LANG.id}{literal}', name : 'id', width : 5 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.name}{literal}', name : 'description', width : 55 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$cfs.product_cf1}{literal}', name : 'product_cf1', width : 15 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 5 * percentage_width, sortable : true, align: 'center'}
				];
            }
/*			var columns = 6;
			var padding = 12;
			var grid_width = $('.col').width();
		
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 

			/ *
            * If Inventory in SImple Invoices is enabled than show quantity etc..
            * /
    
            if(inventory == '1')
            {
                col_model = [ 
				    {display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				    {display: '{/literal}{$LANG.id}{literal}', name : 'id', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.name}{literal}', name : 'description', width : 50 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.quantity}{literal}', name : 'quantity', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'center'}
				];
            } else {
                col_model = [ 
				    {display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				    {display: '{/literal}{$LANG.id}{literal}', name : 'id', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.name}{literal}', name : 'description', width : 55 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price', width : 15 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'center'}
				];
            }
*/
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=products&view=xml',
			dataType: 'xml',
			colModel : col_model,
			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'id'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'description', isdefault: true},
				{display: '{/literal}{$LANG.unit_price}{literal}', name : 'unit_price'}
				],
			sortname: '{/literal}{$smarty.get.sortname|default:'description'}{literal}',
			sortorder: '{/literal}{$smarty.get.sortorder|default:'asc'}{literal}',
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			pagestat: '{/literal}{$LANG.displaying_items}{literal}',
			procmsg: '{/literal}{$LANG.processing}{literal}',
			nomsg: '{/literal}{$LANG.no_items}{literal}',
			pagemsg: '{/literal}{$LANG.page}{literal}',
			ofmsg: '{/literal}{$LANG.of}{literal}',
			useRp: false,
			rp: {/literal}{$smarty.get.rp|default:'15'}{literal},
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
			}
			);
{/literal}
</script>
