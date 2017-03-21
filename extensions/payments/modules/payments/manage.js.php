<script>
{literal}
    var columns      = 9;
    var padding      = 12;
    var grid_width   = $('.col').width();
    grid_width       = grid_width - (columns * padding);
    percentage_width = grid_width / 100;

    $("#manageGrid").flexigrid ({
        url: 'index.php?module=payments&view=xml&id={/literal}{$inv_id}{literal}&c_id={/literal}{$c_id}{literal}',
        dataType: 'xml',
        colModel : [
            {display: '{/literal}{$LANG.actions}{literal}'   , name : 'actions'     , width : 07 * percentage_width, sortable : false, align: 'center'},
            {display: '{/literal}{$LANG.payment}{literal}#'  , name : 'ap.id'       , width : 07 * percentage_width, sortable : true , align: 'right'},
            {display: '{/literal}{$LANG.invoice}{literal}'   , name : 'ac_inv_id'   , width : 10 * percentage_width, sortable : true , align: 'left'},
            {display: '{/literal}{$LANG.customer}{literal}'  , name : 'c.name'      , width : 15 * percentage_width, sortable : true , align: 'left'},
            {display: '{/literal}{$LANG.biller}{literal}'    , name : 'b.name'      , width : 15 * percentage_width, sortable : true , align: 'left'},
            {display: '{/literal}{$LANG.amount}{literal}'    , name : 'ac_amount'   , width : 09 * percentage_width, sortable : true , align: 'right'},
            {display: '{/literal}{$LANG.notes}{literal}'     , name : 'ac_notes'    , width : 18 * percentage_width, sortable : false, align: 'left'},
            {display: '{/literal}{$LANG.type}{literal}'      , name : 'type'        , width : 09 * percentage_width, sortable : true , align: 'left'},
            {display: '{/literal}{$LANG.date_upper}{literal}', name : 'ac_date'     , width : 10 * percentage_width, sortable : true , align: 'center'}
        ],
        searchitems : [
           {display: '{/literal}{$LANG.payment}{literal}'     , name : 'ap.id'},
           {display: '{/literal}{$LANG.invoice}{literal}'     , name : 'ac_inv_id', isdefault: true},
           {display: '{/literal}{$LANG.amount}{literal}'      , name : 'ac_amount'},
           {display: '{/literal}{$LANG.type}{literal}'        , name : 'type'},
           {display: '{/literal}{$LANG.biller}{literal}'      , name : 'b.name'},
           {display: '{/literal}{$LANG.customer}{literal}'    , name : 'c.name'},
           {display: '{/literal}{$LANG.date_upper}{literal}'  , name : 'ac_date'}
        ],
        sortname:  "{/literal}{$smarty.get.sortname|default:'ac_inv_id'}{literal}",
        sortorder: "{/literal}{$smarty.get.sortorder|default:'desc'}{literal}",
        usepager:  true,
        pagestat:  '{/literal}{$LANG.displaying_items}{literal}',
        procmsg:   '{/literal}{$LANG.processing}{literal}',
        nomsg:     '{/literal}{$LANG.no_items}{literal}',
        pagemsg:   '{/literal}{$LANG.page}{literal}',
        ofmsg:     '{/literal}{$LANG.of}{literal}',
        useRp:     false,
        rp:        {/literal}{$smarty.get.rp|default:'25'}{literal},
        showToggleBtn:      false,
        showTableToggleBtn: false,
        width:  'auto',
        height: 'auto'
    });
{/literal}
</script>
