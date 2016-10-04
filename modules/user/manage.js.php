<script>

{literal}
var columns = 5;
var padding = 12;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding);
percentage_width = grid_width / 100;

$('#manageGrid').flexigrid ( {
    url: 'index.php?module=user&view=xml',
    dataType: 'xml',
    // @formatter:off
    colModel : [
        {display: '{/literal}{$LANG.actions}{literal}' , name : 'actions' , width : 10 * percentage_width, sortable : false, align: 'center'},
        {display: '{/literal}{$LANG.email}{literal}'   , name : 'email'   , width : 40 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.role}{literal}'    , name : 'role'    , width : 30 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.enabled}{literal}' , name : 'enabled' , width : 10 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.users}{literal}'   , name : 'user_id' , width : 10 * percentage_width, sortable : true , align: 'left'}
    ],
    searchitems : [
        {display: '{/literal}{$LANG.email}{literal}'   , name : 'email'},
        {display: '{/literal}{$LANG.role}{literal}'    , name : 'ur.name'}
    ],
    // @formatter:on
    sortname: 'name',
    sortorder: 'asc',
    usepager: true,
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
} );
{/literal}
</script>
