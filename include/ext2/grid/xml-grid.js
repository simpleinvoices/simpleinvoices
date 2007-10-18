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
               totalRecords: '@total'
           }, [
               // set up the fields mapping into the xml doc
               // The first needs mapping, the others are very basic
               {name: 'id', mapping: 'id'},
               'biller_id', 'customer_id', 'date'
           ])
    });

    var cm = new Ext.grid.ColumnModel([
	    {header: "ID", width: 120, dataIndex: 'id'},
		{header: "Biller", width: 180, dataIndex: 'biller_id'},
		{header: "Customer", width: 115, dataIndex: 'customer_id'},
		{header: "Date", width: 100, dataIndex: 'date'}
	]);
    cm.defaultSortable = true;

    // create the grid
    var grid = new Ext.grid.GridPanel({
        ds: ds,
        cm: cm,
        renderTo:'example-grid',
        width:540,
        height:200
    });

    ds.load();
});
