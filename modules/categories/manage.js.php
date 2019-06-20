<script type="text/javascript">

{literal}

var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";


			var columns = 6;
			var padding = 12;
			var grid_width = $('.col').width();
		
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
			
			col_model = [ 
				    {display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				    {display: '{/literal}{$LANG.id}{literal}', name : 'category_id', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.name}{literal}', name : 'name', width : 50 * percentage_width, sortable : true, align: 'left'},
				    {display: '{/literal}{$LANG.slug}{literal}', name : 'slug', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.reference}{literal}', name : 'referencia', width : 10 * percentage_width, sortable : true, align: 'right'},
				    {display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'center'}
				];

			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=categories&view=xml',
			dataType: 'xml',
			colModel : col_model,
			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'category_id'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'name', isdefault: true},
				{display: '{/literal}{$LANG.slug}{literal}', name : 'slug'},
				{display: '{/literal}{$LANG.reference}{literal}', name : 'referencia'}
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
