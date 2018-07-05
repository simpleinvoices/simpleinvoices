<script>
{literal}
var view_tooltip = "{/literal}{$LANG.quick_view_tooltip}{ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip}{$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";

var columns = 6;
var padding = 12;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding);
percentage_width = grid_width / 100; 

$('#manageGrid').flexigrid ({
    url: 'index.php?module=custom_flags&view=xml',
    dataType: 'xml',
    colModel: [ 
        {display: '{/literal}{$LANG.actions}{literal}'          , name: 'actions'    , width:  5 * percentage_width, sortable: false, align: 'center'},
        {display: '{/literal}{$LANG.associated_table}{literal}' , name: 'table'      , width:  8 * percentage_width, sortable: true , align: 'left'},
        {display: '{/literal}{$LANG.flag_number}{literal}'      , name: 'flg_id'     , width:  4 * percentage_width, sortable: true , align: 'right'},
        {display: '{/literal}{$LANG.field_label_upper}{literal}', name: 'field_label', width: 10 * percentage_width, sortable: false, align: 'left'},
        {display: '{/literal}{$LANG.enabled}{literal}'          , name: 'enabled'    , width:  7 * percentage_width, sortable: false, align: 'center'},
        {display: '{/literal}{$LANG.field_help_upper}{literal}' , name: 'field_help' , width: 66 * percentage_width, sortable: false, align: 'left'}
    ],
    searchitems: [ 
        {display: '{/literal}{$LANG.associated_table}{literal}', name: 'table'},
        {display: '{/literal}{$LANG.flag_number}{literal}'     , name: 'flg_id', isdefault: true}
    ],
    sortname : 'associated_table',
    sortorder: 'asc',
    usepager : true,
    pagestat : '{/literal}{$LANG.displaying_items}{literal}',
    procmsg  : '{/literal}{$LANG.processing}{literal}',
    nomsg    : '{/literal}{$LANG.no_items}{literal}',
    pagemsg  : '{/literal}{$LANG.page}{literal}',
    ofmsg    : '{/literal}{$LANG.of}{literal}',
    useRp    : false,
    rp       : 25,
    showToggleBtn: false,
    showTableToggleBtn: false,
    height   : 'auto',
    nowrap   : false
});
{/literal}
</script>
