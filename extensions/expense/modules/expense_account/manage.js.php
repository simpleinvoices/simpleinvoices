<script>
{literal}
var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";

var columns = 5;
var padding = 12;
var grid_width = $('.col').width();

grid_width = grid_width - (columns * padding);
percentage_width = grid_width / 100; 

$('#manageGrid').flexigrid ({
    url: 'index.php?module=expense_account&view=xml',
    dataType: 'xml',
    colModel : [
        {display: '{/literal}{$LANG.actions}{literal}'         , name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
        {display: '{/literal}{$LANG.id}{literal}'              , name : 'id',      width : 10 * percentage_width, sortable : true,  align: 'right'},
        {display: '{/literal}{$LANG.expense_accounts}{literal}', name : 'name',    width : 80 * percentage_width, sortable : true,  align: 'left'}
    ],

    searchitems : [
        {display: '{/literal}{$LANG.id}{literal}', name : 'id'},
        {display: '{/literal}{$LANG.expense_accounts}{literal}', name : 'name', isdefault: true}
                ],
    sortname: 'id',
    sortorder: 'asc',
    usepager: true,
    useRp: false,
    rp: 25,
    showToggleBtn: false,
    showTableToggleBtn: false,
    height: 'auto'
});

{/literal}
</script>
