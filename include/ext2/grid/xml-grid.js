/*
 * Ext JS Library 2.0 Alpha 1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){


  Ext.QuickTips.init();
  
  //Create a clickable button for the Manage pages - toolbar
  Ext.LinkButton = Ext.extend(Ext.Button, 
  {
	template: new Ext.Template(
	'<table border="0" cellpadding="0" cellspacing="0" class="x-btn-wrap"><tbody><tr>',
	'<td class="x-btn-left"><i> </i></td><td class="x-btn-center"><a class="x-btn-text" href="{1}" target="{2}">{0}</a></td><td class="x-btn-right"><i> </i></td>',
	"</tr></tbody></table>"),

	onRender: function(ct, position)
	{
		var btn, targs = [this.text || ' ', this.href, this.target || "_self"];
		
		if(position){
			btn = this.template.insertBefore(position, targs, true);
		}else{
			btn = this.template.append(ct, targs, true);
		}
		
		var btnEl = btn.child("a:first");
		btnEl.on('focus', this.onFocus, this);
		btnEl.on('blur', this.onBlur, this);

		this.initButtonEl(btn, btnEl);
		btn.un(this.clickEvent, this.onClick, this);
		Ext.ButtonToggleMgr.register(this);
	}
  });

  

      var action1 = new Ext.Action({
        text: 'Help',
        handler: function(){
			showWindow();
			//Ext.MessageBox.alert('Simple Invoices help', 'This is a test help box thing.');
			//Ext.Msg.prompt('Enter Text', 'Enter new text for Action 1:');
			//href:'index.php';
			//alert('test');
            //Ext.msg('Click','You clicked on "Action 1".');
        },
        iconCls: 'blist'
    });
	
	 function showWindow() {   
      var win = new Ext.Window({
         width:400,
         height:400,
         title:"Simple Invoices help",
		 autoLoad: 'documentation/en-gb/help/age.html',
         autoScroll:true,
         modal:true
         //html:'documentation/en-gb/help/age.html'
         //animateTarget:"btnHello"
      });
      win.show()
	}
	
    // create the Data Store
    var ds = new Ext.data.GroupingStore({
        // load using HTTP
        //url: 'sheldon2.xml',
		url: 'index.php?module=invoices&view=xml',
        // the return will be XML, so lets set up a reader
        reader: new Ext.data.XmlReader({
               // records will have an "Item" tag
               record: 'tablerow',
               id: 'id',
			   type_id: 'type_id',
               totalRecords: 'total'
           }, [
               // set up the fields mapping into the xml doc
               // The first needs mapping, the others are very basic
		'actions',
               {name: 'id', mapping: 'id'},
				{name: 'type_id', mapping: 'type_id'},
              'Biller','Customer','INV_TOTAL','INV_PAID','INV_OWING','Date','Aging','Type'
           ]),
		// turn on remote sorting
		groupField: 'Aging',
        remoteSort: true
    });
	
	ds.setDefaultSort('id', 'desc');

// pluggable renders
	function renderActions(value, p, record){
           var quickViewLink = String.format(
                '<!--0 Quick View --><a class="index_table" title="{$LANG.quick_view_tooltip} {$invoice.preference.pref_inv_wording} {8}" href="index.php?module=invoices&view=quick_view&invoice={1}"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);
		
			var editViewLink = String.format(
               '<!--1 Edit View --><a class="index_table" title="{$LANG.edit_view_tooltip} {$invoice.preference.pref_inv_wording} {8}" href="index.php?module=invoices&view=details&invoice={1}&action=view"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);
				
			var printViewLink = String.format(
				'<!--2 Print View --><a class="index_table" title="{$LANG.print_preview_tooltip} {$invoice.preference.pref_inv_wording} {8}" href="index.php?module=invoices&view=templates/template&invoice={1}&action=view&location=print"><img src="images/common/printer.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);

			var pdfLink = String.format(
				'<!--3 EXPORT TO PDF --><a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {8} {$LANG.export_pdf_tooltip}"	class="index_table" href="pdfmaker.php?id={1}"><img src="images/common/page_white_acrobat.png" height="16" padding="-4px" border="-5px" valign="bottom" /><!-- pdf --></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);				

			var xlsLink = String.format(
				'<!--4 XLS --><a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording}{8} {$LANG.export_xls_tooltip} {$spreadsheet} {$LANG.format_tooltip}" class="index_table" href="index.php?module=invoices&view=templates/template&invoice={1}&action=view&location=print&export={$spreadsheet}"><img src="images/common/page_white_excel.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $spreadsheet --></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);	

			var docLink = String.format(
				'<!--5 DOC --><a title="{$LANG.export_tooltip} {$invoice.preference.pref_inv_wording} {8} {$LANG.export_doc_tooltip} {$word_processor} {$LANG.format_tooltip}" class="index_table" href="index.php?module=invoices&view=templates/template&invoice={1}&action=view&location=print&export={$word_processor}"><img src="images/common/page_white_word.png" height="16" border="0" padding="-4px" valign="bottom" /><!-- $word_processor --></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);	

			var paymentLink = String.format(
				'<!--6 Payment --><a title="{$LANG.process_payment} {$invoice.preference.pref_inv_wording} {8}" class="index_table" href="index.php?module=payments&view=process&invoice={1}&op=pay_selected_invoice"><img src="images/common/money_dollar.png" height="16" border="0" padding="-4px" valign="bottom" /></a>',		
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);	
				
				
			var emailLink = String.format(
				'<!--7 Email --><a href="index.php?module=invoices&view=email&stage=1&invoice={1}" title="{$LANG.email}  {$invoice.preference.pref_inv_wording} {8}"><img src="images/common/mail-message-new.png" height="16" border="0" padding="-4px" valign="bottom" /></a>',
				value, 
				record.id, 
				record.data.type_id, 
				record.data.forumid);	

		//Return a nice big link for the Actions column in the Manage Invoices page
			return quickViewLink + editViewLink + printViewLink + pdfLink + xlsLink + docLink + paymentLink + emailLink;
     }

    var cm = new Ext.grid.ColumnModel([
	    {header: "Actions", width: 105, dataIndex: 'actions', sortable:false, renderer: renderActions },
	    {header: "ID", width: 50, dataIndex: 'id'},
		{header: "Biller", width: 180, dataIndex: 'Biller'},
		{header: "Customer", width: 115, dataIndex: 'Customer'},
		{header: "Total", width: 75, dataIndex: 'INV_TOTAL'},
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
		
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
		

		bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: ds,
            displayInfo: true,
            displayMsg: 'Displaying invoices {0} - {1} of {2}'
        }),
		layout:'fit',
        	             tbar:[
						 action1, 
						 '-', 
						 new Ext.LinkButton({
						     text:'New Invoice - Total style',
                             tooltip:'Create a new invoice using the Total style',
							 href:'index.php',
							 IconCls: 'add'
						})
						 , '-',
						 {
                             text:'New Invoice - Total style',
                             tooltip:'Create a new invoice using the Total style',
							 type: 'submit',
							 href:'index.php',
                             iconCls:'add'
                         }, '-', {
                             text:'New Invoice - Consulting style',
                             tooltip:'Create a new invoice using the Consulting style',
							 href:'http://www.simpleinvoices.org',
							 hrefTarget:'self',
                             iconCls:'option'
                         },'-',{
                             text:'New Invoice - Itemised style',
                             tooltip:'Create a new invoice using the Itemised style',
							 href:'index.php',
                             iconCls:'remove'
                         }]
		
    });
	
    function onButtonClick(btn){
        Ext.example.msg('Button Click','You clicked the "{0}" button.', btn.text);
    }
	
pnl = new Ext.Viewport( {
 id:'panel',
 frame:false,
 layout:'fit',
 items:grid
});



		ds.load({params:{start:0, limit:25}});

    //ds.load();
});
