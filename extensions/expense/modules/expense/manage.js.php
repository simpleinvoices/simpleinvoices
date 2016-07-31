<script>
{literal}
var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";

var columns = 5;
var padding = 12;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding);
percentage_width = grid_width / 100; 

// @formatter:off
$('#manageGrid').flexigrid ({
    url: "{/literal}{$url}{literal}",
    dataType: 'xml',
    colModel : [
        {display: '{/literal}{$LANG.actions}{literal}'         , name : 'actions'           , width :  9   * percentage_width, sortable : false, align: 'center'},
        {display: '{/literal}{$LANG.date_upper}{literal}'      , name : 'date'              , width : 10   * percentage_width, sortable : true , align: 'center'},
        {display: '{/literal}{$LANG.amount}{literal}'          , name : 'amount'            , width :  7.5 * percentage_width, sortable : true , align: 'right'},
        {display: '{/literal}{$LANG.tax}{literal}'             , name : 'tax'               , width :  7.5 * percentage_width, sortable : true , align: 'right'},
        {display: '{/literal}{$LANG.total}{literal}'           , name : 'total'             , width :  7.5 * percentage_width, sortable : true , align: 'right'},
        {display: '{/literal}{$LANG.expense_accounts}{literal}', name : 'expense_account_id', width : 13.5 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.biller}{literal}'          , name : 'biller_id'         , width : 10   * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.customer}{literal}'        , name : 'customer_id'       , width : 10   * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.invoice}{literal}'         , name : 'invoice_id'        , width :  5   * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.status}{literal}'          , name : 'status'            , width : 15   * percentage_width, sortable : true , align: 'left'}
    ],
    searchitems : [
        {display: '{/literal}{$LANG.id}{literal}'              , name : 'e.id'},
        {display: '{/literal}{$LANG.status}{literal}'          , name : 'e.status_wording', isdefault: true},
        {display: '{/literal}{$LANG.expense_accounts}{literal}', name : 'ea.name'},
        {display: '{/literal}{$LANG.biller}{literal}'          , name : 'b.name'},
        {display: '{/literal}{$LANG.customer}{literal}'        , name : 'c.name'},
        {display: '{/literal}{$LANG.product}{literal}'         , name : 'p.description'}
    ],
    sortname: 'EID',
    sortorder: 'desc',
    usepager: true,
    useRp: false,
    rp: 25,
    showToggleBtn: false,
    showTableToggleBtn: false,
    height: 'auto'
});
// @formatter:on

{/literal}
</script>
