<script type="text/javascript">
{literal}
			var columns = 8;
			var padding = 12;
			var action_menu = 140;
			var grid_width = $('.col').width();

			grid_width = grid_width - (columns * padding) - action_menu;
			percentage_width = grid_width / 100;

			function do_filter_due(){
			    window.location = 'index.php?module=invoices&view=manage&having=money_owed';
			}
			function do_filter_paid(){
			    window.location = 'index.php?module=invoices&view=manage&having=paid';
			}
			function do_filter_draft(){
			    window.location = 'index.php?module=invoices&view=manage&having=draft';
			}
			function do_filter_real(){
			    window.location = 'index.php?module=invoices&view=manage&having=real';
			}
			function do_filter_all(){
			    window.location = 'index.php?module=invoices&view=manage';
			}

			$("#manageGrid").flexigrid
			(
			{
			url: "{/literal}{$url}{literal}",
			dataType: 'xml',
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : action_menu, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.id}{literal}', name : 'index_name', width :10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.biller}{literal}', name : 'biller', width :20 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.customer}{literal}', name : 'customer', width :30 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.date_upper}{literal}', name : 'date', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.total}{literal}', name : 'invoice_total', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.owing}{literal}', name : 'owing', width : 10 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.aging}{literal}', name : 'aging', width : 10 * percentage_width, sortable : true, align: 'left'}

				],

			buttons : [
				{name: '{/literal}{$LANG.filters}{literal}'},
				{separator: true},
				{name: '{/literal}{$LANG.due}{literal}', bclass: 'filter_due', onpress : do_filter_due},
				{name: '{/literal}{$LANG.paid}{literal}', bclass: 'filter_paid', onpress : do_filter_paid},
				{name: '{/literal}{$LANG.draft}{literal}', bclass: 'filter_draft', onpress : do_filter_draft},
				{name: '{/literal}{$LANG.real}{literal}', bclass: 'filter_real', onpress : do_filter_real},
				{name: '{/literal}{$LANG.all}{literal}', bclass: 'filter_all', onpress : do_filter_all}
				],

			searchitems : [
				{display: '{/literal}{$LANG.invoice_number}{literal}', name : 'index_id'},
				{display: '{/literal}{$LANG.biller}{literal}', name : 'b.name'},
				{display: '{/literal}{$LANG.customer}{literal}', name : 'c.name', isdefault: true}
				],
			sortname: "id",
			sortorder: "desc",
			usepager: true,
			/*title: 'Manage Custom Fields',*/
			pagestat: '{/literal}{$LANG.displaying_items}{literal}',
			procmsg: '{/literal}{$LANG.processing}{literal}',
			nomsg: '{/literal}{$LANG.no_items}{literal}',
			pagemsg: '{/literal}{$LANG.page}{literal}',
			ofmsg: '{/literal}{$LANG.of}{literal}',
			useRp: false,
			rp: '{/literal}{$defaults.rows_per_page|htmlsafe}{literal}',
			showToggleBtn: false,
			showTableToggleBtn: false,
			width: 'auto',
			height: 'auto'
			}
			);



/*
 * Show an indicator next to the selected filter option - based on $GET['having']
 */
$(document).ready(function() {


       var $get_filter = getUrlVars()["having"];
       if($get_filter== 'money_owed')
       {
            filter_type = 'due';
       }
       if($get_filter == 'paid')
       {
            filter_type = 'paid';
       }
       if($get_filter == 'draft')
       {
            filter_type = 'draft';
       }
       if($get_filter == 'real')
       {
            filter_type = 'real';
       }
       if($get_filter == null)
       {
            filter_type = 'all';
       }

      $('.filter_'+filter_type).css('background',"url({/literal}{$include_dir}{literal}images/common/tag-right.png) no-repeat center left");
});

{/literal}

</script>
