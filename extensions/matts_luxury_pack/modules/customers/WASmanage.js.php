<script type="text/javascript">
/* extensions/customer_add_tabbed/modules/customers/manage.js.php */
/*WHY DOESN'T THIS DISPLAY ???  MOFIDIED THE CORE FILE (./modules/customers/manage.js.php)*/

{literal}
/*
var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
'<!--0 Quick View --><a class="index_table" href="index.php?module=customers&view=details&id={1}&action=view"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /></a>',
'<!--1 Edit View --><a class="index_table" href="index.php?module=customers&view=details&id={1}&action=edit"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
*/


			var columns = 8;/*7*/
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=customers&view=xml',
			dataType: 'xml',
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 7 * percentage_width, sortable : false, align: 'center'},
//				{display: '{/literal}{$LANG.id}{literal}', name : 'CID', width : 7 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.id}{literal}', name : 'CID', width : 5 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'name', width : 25 * percentage_width, sortable : true, align: 'left'},
/**/
				{display: '{/literal}{$LANG.street}{literal}', name : 'address', width : 21 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.contactp}{literal}', name : 'contactp', width : 11 * percentage_width, sortable : true, align: 'left'},
/**/
//				{display: '{/literal}{$LANG.total}{literal}', name : 'customer_total', width : 10 * percentage_width, sortable : true, align: 'right'},
//				{display: '{/literal}{$LANG.paid}{literal}', name : 'paid', width : 10 * percentage_width, sortable : true, align: 'right'},
//				{display: '{/literal}{$LANG.owing}{literal}', name : 'owing', width : 10 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.total}{literal}', name : 'customer_total', width : 8 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.paid}{literal}', name : 'paid', width : 8 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.owing}{literal}', name : 'owing', width : 8 * percentage_width, sortable : true, align: 'right'},
				{display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 6 * percentage_width, sortable : true, align: 'center'}
				],
				

			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'c.id'},
				{display: '{/literal}{$LANG.name}{literal}', name : 'c.name', isdefault: true}
				],
			sortname: '{/literal}{$smarty.get.sortname|default:'name'}{literal}',
			sortorder: '{/literal}{$smarty.get.sortorder|default:'asc'}{literal}',
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			pagestat: '{/literal}{$LANG.displaying_items}{literal}',
			procmsg: '{/literal}{$LANG.processing}{literal}',
			nomsg: '{/literal}{$LANG.no_items}{literal}',
			pagemsg: '{/literal}{$LANG.page}{literal}',
			ofmsg: '{/literal}{$LANG.of}{literal}',
			useRp: false,
			rp: '{/literal}{$smarty.get.rp|default:'15'}{literal}',
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
			}
			);

{/literal}

</script>
