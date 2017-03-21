<script>
{literal}
    var columns = 8;
    var padding = 12;
    var action_menu = 140;
    var grid_width = $('.col').width();

    grid_width = grid_width - (columns * padding) - action_menu;
    percentage_width = grid_width / 100;

    $("#manageGrid").flexigrid
    (
        {
            url: "{/literal}{$url}{literal}",
            dataType: 'xml',
            colModel : [
                {display: '{/literal}{$LANG.actions}{literal}'   , name : 'actions'      , width : action_menu          , sortable : false, align: 'center'},
                {display: '{/literal}{$LANG.invoice_id}{literal}', name : 'index_id'     , width : 13 * percentage_width, sortable : true , align: 'center'},
                {display: '{/literal}{$LANG.biller}{literal}'    , name : 'b.name'       , width : 22 * percentage_width, sortable : true , align: 'left'},
                {display: '{/literal}{$LANG.customer}{literal}'  , name : 'c.name'       , width : 23 * percentage_width, sortable : true , align: 'left'},
                {display: '{/literal}{$LANG.date_upper}{literal}', name : 'date'         , width : 12 * percentage_width, sortable : true , align: 'center'},
                {display: '{/literal}{$LANG.total}{literal}'     , name : 'invoice_total', width : 10 * percentage_width, sortable : true , align: 'right'},
                {display: '{/literal}{$LANG.owing}{literal}'     , name : 'owing'        , width : 10 * percentage_width, sortable : true , align: 'right'},
                {display: '{/literal}{$LANG.aging}{literal}'     , name : 'aging'        , width : 10 * percentage_width, sortable : true , align: 'right'}
            ],
            searchitems : [
                {display: '{/literal}{$LANG.invoice_id}{literal}', name : 'index_id', isdefault: true},
                {display: '{/literal}{$LANG.biller}{literal}'    , name : 'b.name'},
                {display: '{/literal}{$LANG.customer}{literal}'  , name : 'c.name'},
                {display: '{/literal}{$LANG.date_upper}{literal}', name : 'date'},
                {display: '{/literal}{$LANG.total}{literal}'     , name : 'invoice_total'},
                {display: '{/literal}{$LANG.owing}{literal}'     , name : 'owing'},
                {display: '{/literal}{$LANG.aging}{literal}'     , name : 'aging'}
            ],
            sortname: "{/literal}{$smarty.get.sortname|default:'index_id'}{literal}",
            sortorder: "{/literal}{$smarty.get.sortorder|default:'desc'}{literal}",
            usepager: true,
            pagestat: '{/literal}{$LANG.displaying_items}{literal}',
            procmsg: '{/literal}{$LANG.processing}{literal}',
            nomsg: '{/literal}{$LANG.no_items}{literal}',
            pagemsg: '{/literal}{$LANG.page}{literal}',
            ofmsg: '{/literal}{$LANG.of}{literal}',
            useRp: false,
            rp: {/literal}{$smarty.get.rp|default:'15'}{literal},
            showToggleBtn: false,
            showTableToggleBtn: false,
            width: 'auto',
            height: 'auto'
        }
    );
{/literal}

</script>
