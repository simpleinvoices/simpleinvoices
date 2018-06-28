<script>
{literal}
var columns = 8;
var padding = 12;
var action_menu = 140;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding) - action_menu;
percentage_width = grid_width / 100;

$("#manageGrid").flexigrid ({
    url: "{/literal}{$url}{literal}",
    dataType: 'xml',
    colModel : [
        {display: '{/literal}{$LANG.actions}{literal}'         , name : 'actions'       , width : action_menu          , sortable : false, align: 'center'},
        {display: '{/literal}{$LANG.id}{literal}'              , name : 'index_name'    , width : 10 * percentage_width, sortable : true, align : 'left'},
        {display: '{/literal}{$LANG.start_date_short}{literal}', name : 'start_date'    , width : 10 * percentage_width, sortable : true, align : 'left'},
        {display: '{/literal}{$LANG.end_date_short}{literal}'  , name : 'end_date'      , width : 10 * percentage_width, sortable : true, align : 'left'},
        {display: '{/literal}{$LANG.recur_each}{literal}'      , name : 'recurrence'    , width : 15 * percentage_width, sortable : true, align : 'left'},
        {display: '{/literal}{$LANG.email_biller}{literal}'    , name : 'email_biller'  , width : 10 * percentage_width, sortable : true, align : 'left'},
        {display: '{/literal}{$LANG.email_customer}{literal}'  , name : 'email_customer', width : 15 * percentage_width, sortable : true, align : 'left'},
        {display: '{/literal}{$LANG.customer}{literal}'        , name : 'customer'      , width : 30 * percentage_width, sortable : true, align : 'left'}
    ],
    searchitems : [
        {display: '{/literal}{$LANG.invoice_number}{literal}', name : 'iv.id'},
        {display: '{/literal}{$LANG.biller}{literal}'        , name : 'b.name'},
        {display: '{/literal}{$LANG.customer}{literal}'      , name : 'cron.id', isdefault: true},
        {display: '{/literal}{$LANG.aging}{literal}'         , name : 'aging'}
    ],
    sortname          : "id",
    sortorder         : "desc",
    usepager          : true,
    pagestat          : '{/literal}{$LANG.displaying_items}{literal}',
    procmsg           : '{/literal}{$LANG.processing}{literal}',
    nomsg             : '{/literal}{$LANG.no_items}{literal}',
    pagemsg           : '{/literal}{$LANG.page}{literal}',
    ofmsg             : '{/literal}{$LANG.of}{literal}',
    useRp             : false,
    rp                : 15,
    showToggleBtn     : false,
    showTableToggleBtn: false,
    width             : 'auto',
    height            : 'auto'
});
{/literal}
</script>
