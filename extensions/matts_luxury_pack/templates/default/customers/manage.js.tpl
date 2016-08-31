{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/customers/manage.js.tpl
 * 	Customer manage template
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2016-08-31
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
<script type="text/javascript">

	var columns = 8;{*/*7*/*}
	var padding = 12;
	var grid_width = $('.col').width();
		
	grid_width = grid_width - (columns * padding);
	percentage_width = grid_width / 100; 
{literal}
		$('#manageGrid').flexigrid({
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
				{display: '{/literal}{$LANG.name}{literal}', name : 'c.name', isdefault: true},
				{display: '{/literal}{$LANG.street}{literal}', name : 'c.street_address'},
				{display: '{/literal}{$LANG.contactp}{literal}', name : 'c.attention'}
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
			rp: {/literal}{if $smarty.get.rp}{$smarty.get.rp}{elseif $defaults.default_nrows}{$defaults.default_nrows}{else}15{/if}{literal},
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
		});
{/literal}</script>
