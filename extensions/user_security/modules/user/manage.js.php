<script>
{literal}
var columns = 6;
var padding = 12;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding);
percentage_width = grid_width / 100;

$('#manageGrid').flexigrid ( {
    url: 'index.php?module=user&view=xml',
    dataType: 'xml',
    // @formatter:off
    colModel : [
        {display: '{/literal}{$LANG.actions}{literal}' , name : 'actions'  , width :  8 * percentage_width, sortable : false, align: 'center'},
        {display: '{/literal}{$LANG.username}{literal}', name : 'username' , width : 30 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.email}{literal}'   , name : 'email'    , width : 30 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.role}{literal}'    , name : 'role_name', width : 10 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.enabled}{literal}' , name : 'enabled'  , width :  6 * percentage_width, sortable : true , align: 'left'},
        {display: '{/literal}{$LANG.user_id}{literal}' , name : 'uid'      , width : 16 * percentage_width, sortable : true , align: 'left'}
    ],
    searchitems : [
        {display: '{/literal}{$LANG.username}{literal}', name : 'username', isdefault: true},
        {display: '{/literal}{$LANG.email}{literal}'   , name : 'email'  },
        {display: '{/literal}{$LANG.role}{literal}'    , name : 'ur.name'}
    ],
//         {display: '{/literal}{$LANG.user_id}{literal}' , name : 'user_id'}
    // @formatter:on
    sortname: 'username',
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
