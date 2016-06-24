<script>
{literal}
    var columns = 7;
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
        {display: 'Actions', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
        {display: 'ID', name : 'id', width : 10 * percentage_width, sortable : true, align: 'left'},
        {display: 'Name', name : 'name', width : 40 * percentage_width, sortable : true, align: 'left'},
        {display: 'Last Invoice', name : 'last_invoice', width : 10 * percentage_width, sortable : false, align: 'right'},
        {display: 'Unit Price', name : 'customer_total', width : 10 * percentage_width, sortable : true, align: 'left'},
        {display: 'Owing', name : 'owing', width : 10 * percentage_width, sortable : true, align: 'left'},
        {display: 'Enabled', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'left'}
        ],

    searchitems : [
        {display: 'ID', name : 'id'},
        {display: 'Name', name : 'name', isdefault: true}
        ],
    sortname: 'name',
    sortorder: 'asc',
    usepager: true,
    // title: 'Manage Custom Fields'
    useRp: false,
    rp: 25,
    showToggleBtn: false,
    showTableToggleBtn: false,
    height: 'auto'
    }
    );

{/literal}
</script>
