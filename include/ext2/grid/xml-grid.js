/*
 * Ext JS Library 2.0 Alpha 1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){

    // create the Data Store
    var ds = new Ext.data.Store({
        // load using HTTP
        //url: 'sheldon2.xml',
		url: 'http://localhost/simpleinvoices-dev/trunk/index.php?module=invoices&view=xml',
        // the return will be XML, so lets set up a reader
        reader: new Ext.data.XmlReader({
               // records will have an "Item" tag
               record: 'tablerow',
               id: 'id',
               totalRecords: 'total'
           }, [
               // set up the fields mapping into the xml doc
               // The first needs mapping, the others are very basic
			'actions',
               {name: 'id', mapping: 'id'},

              'Biller','Customer','INV_TOTAL','INV_PAID','INV_OWING','Date','Aging','Type'
           ]),
		// turn on remote sorting
        remoteSort: true
    });
	
	ds.setDefaultSort('id', 'desc');

    var cm = new Ext.grid.ColumnModel([
	    {header: "Actions", width: 50, dataIndex: 'actions', sortable:false,renderer: this.formatAction },
	    {header: "ID", width: 50, dataIndex: 'id'},
		{header: "Biller", width: 180, dataIndex: 'Biller'},
		{header: "Customer", width: 115, dataIndex: 'Customer'},
		{header: "Total", width: 75, dataIndex: 'INV_TOTAL'},
		{header: "Paid", width: 75, dataIndex: 'INV_PAID'},
		{header: "Owing", width: 75, dataIndex: 'INV_OWING'},
		{header: "Date", width: 75, dataIndex: 'Date'},
		{header: "Aging", width: 75, dataIndex: 'Aging'},
		{header: "Type", width: 100, dataIndex: 'Type'}
	]);
    cm.defaultSortable = true;

    // create the grid
    var grid = new Ext.grid.GridPanel({
        ds: ds,
        cm: cm,
		title:'Manage Invoices',
        //renderTo: document.body,
        renderTo:'manageInvoicesGrid',
        //width:800,
        //height:600,
		//autoHeight: true,
        viewConfig: {
             forceFit:true
        },

		bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: ds,
            displayInfo: true,
            displayMsg: 'Displaying invoices {0} - {1} of {2}'
        }),
		layout:'fit',
        	             tbar:[{
                             text:'Add New Invoice',
                             tooltip:'Add a new row',
                             iconCls:'add'
                         }, '-', {
                             text:'New Consulting style invoice',
                             tooltip:'Blah blah blah blaht',
                             iconCls:'option'
                         },'-',{
                             text:'new Itemised style invoice',
                             tooltip:'Remove the selected item',
                             iconCls:'remove'
                         }]
		
/*
     formatAction: function(value, p, record) {
         return String.format(
                 '<div class="topic"><b>{0}</b><span class="author">{1}</span></div>',
                 value, record.data.author, record.id, record.data.forumid
                 );
     }
*/
    });
	

pnl = new Ext.Viewport( {
 id:'panel',
 frame:false,
 layout:'fit',
 items:grid
});



		ds.load({params:{start:0, limit:25}});

    //ds.load();
});
