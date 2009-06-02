/*
* OLD file re extjs - ignore
*/

// TODO integrate the new layout with the template
Ext.onReady(function(){
	    
    // Group box containing actions
    var invoicePanel = new Ext.Panel({
    	frame:true,
    	title: 'Simple Invoices : Menu',
    	collapsible:false,
    	collapsed:false,
    	contentEl:'invoices',
    	titleCollapse: false
    });
	
var tbar1 = new Ext.Panel({
 tbar: [{
 iconCls:'add-feed',
 text:'Add Feed',
 handler: this.showWindow,
 scope: this
 },{
 id:'delete',
 iconCls:'delete-icon',
 text:'Remove',
 handler: function(){
 var s = this.getSelectionModel().getSelectedNode();
 if(s){
 this.removeFeed(s.attributes.url);
 }
 },
 scope: this
 }],
 html:"<br>sdfsdfsdfsdfsdf<br><br>"
    });
	
var accordion = new Ext.Panel({
    //title: 'Accordion Layout',
    layout:'accordion',
	collapsible:false,
	collapsed:false,
	//border:false,
    defaults: {
        // applied to each contained panel
        //bodyStyle: 'padding:15px'
    },
    layoutConfig: {
        // layout-specific configs go here
        //titleCollapse: false,
        animate: false,
		collapsible:false,
        activeOnTop: true
    },
    items: [{
	    collapsible:false,
        title: 'Panel 1',
        html: '<p>Panel content!</p>'
    }]
});
/*
   
    // Group box containing actions
    var customerPanel = new Ext.Panel({
    	frame:true,
    	title: 'Customers',
    	collapsible:true,
    	collapsed:true,
    	contentEl:'customers',
    	titleCollapse: true
    });
    
    // Group box containing actions
    var productPanel = new Ext.Panel({
    	frame:true,
    	title: 'Products',
    	collapsible:true,
    	collapsed:true,
    	contentEl:'products',
    	titleCollapse: true
    });
    
     // Group box containing actions
    var billerPanel = new Ext.Panel({
    	frame:true,
    	title: 'Billers',
    	collapsible:true,
    	collapsed:true,
    	contentEl:'billers',
    	titleCollapse: true
    });
    
    // Group box containing actions
    var paymentPanel = new Ext.Panel({
    	frame:true,
    	title: 'Payments',
    	collapsible:true,
    	collapsed:true,
    	contentEl:'payments',
    	titleCollapse: true
    });
    
    // Group box containing actions
    var optionPanel = new Ext.Panel({
    	frame:true,
    	title: 'Options',
    	collapsible:true,
    	collapsed:true,
    	contentEl:'options',
    	titleCollapse: true
    });
    */
    // West Panel containing action box
    var actionPanel = new Ext.Panel({
    	id:'action-panel',
    	region:'west',
    	split:true,
    	collapsible: true,
		margins:'0 0 0 5',
    	collapseMode: 'mini',
    	width:200,
    	minWidth: 150,
    	border: false,
    	baseCls:'x-plain',
		//items:[tbar1]
		items: [invoicePanel]
    	//items: [invoicePanel,customerPanel,productPanel,billerPanel,paymentPanel,optionPanel]
    });
    
    // Main (Tabbed) Panel
    var tabPanel = new Ext.TabPanel({
		region:'center',
		deferredRender:false,
		resizeTabs:true, // turn on tab resizing
		enableTabScroll:true,
		//defaults: {autoScroll:true},
        //plugins: new Ext.ux.TabCloseMenu(),
		autoScroll: true, 
		margins:'0 4 4 0',
		activeTab:0,

		items:[{
			id:'tab1',
			contentEl:'tabs',
			autoLoad: 'index.php',
    		title: 'Main',
    		closable:false,
    		autoScroll:true
		},
		{
			id:'help',
			contentEl:'tabs',
			autoLoad: 'docs.php?p=ReadMe#faqs-types',
    		title: 'Documentation',
    		closable:false,
    		autoScroll:true
		}
		
		]
    });
    
    
    var headerBox = new Ext.BoxComponent({ 
					region:'north',
                    el: 'header',
                    height:10,
                    margins:'0 0 4 0'
                });
    
   	// Configure viewport
    viewport = new Ext.Viewport({
           layout:'border',
           items:[headerBox,actionPanel,tabPanel]});
    
    // Adds tab to center panel
	function addTab(tabId,tabTitle, targetUrl){
		tabPanel.add({
			id:tabId,
			title: tabTitle,
			iconCls: 'tabs',
			autoScroll:true,
			autoLoad: {url: targetUrl, callback: this.initSearch, scope: this},
			closable:true
		}).show();
	}
	
	// Update the contents of a tab if it exists, otherwise create a new one
	function updateTab(tabId,title, url) {
    	var tab = tabPanel.getItem(tabId);
    	if(tab){
    		tab.getUpdater().update(url);
    		tab.setTitle(title);
    	}else{
    		tab = addTab(tabId,title,url);
    	}
    	tabPanel.setActiveTab(tab);
    }
    
    // Man link ids to functions
    var actions = {
        'manage-invoices' : function(){
    		updateTab('tab1',"Manage Invoices","index.php?module=invoices&view=manage");
    	},
    	'new-invoice-total' : function(){
    		updateTab('tab-inv-total',"New Invoice: Total","index.php?module=invoices&view=total");
    	},
    	'new-invoice-itemised' : function(){
    		updateTab('tab-inv-itemised',"New Invoice: Itemised","index.php?module=invoices&view=itemised");
    	},
    	'new-invoice-consulting' : function(){
    		updateTab('tab-inv-consulting',"New Invoice: Consulting","index.php?module=invoices&view=consulting");
    	},
    	'invoice-types' : function(){
    		updateTab('help-tab','Invoice Help',"docs.php?p=ReadMe#faqs-types");
    	},
    	'manage-customers' : function(){
    		updateTab('tab1',"Manage Customers","index.php?module=customers&view=manage");
    	},
    	'new-customer' : function(){
    		updateTab('tab1',"New Customer","index.php?module=customers&view=add");
    	},
    	'manage-products' : function(){
    		updateTab('tab1',"Manage Products","index.php?module=products&view=manage");
    	},
    	'new-product' : function(){
    		updateTab('tab1',"New Product","index.php?module=products&view=add");
    	},
    	'manage-billers' : function(){
    		updateTab('tab1',"Manage Billers","index.php?module=billers&view=manage");
    	},
    	'new-biller' : function(){
    		updateTab('tab1',"New Biller","index.php?module=billers&view=add");
    	},
    	'manage-payments' : function(){
    		updateTab('tab1',"Manage Payments","index.php?module=payments&view=manage");
    	},
    	'new-payment' : function(){
    		updateTab('tab1',"New Payment","index.php?module=payments&view=add");
    	},
    	'system-defaults' : function(){
    		updateTab('tab1',"System Defaults","index.php?module=system_defaults&view=manage");
    	},
    	'tax-rates' : function(){
    		updateTab('tab1',"Tax Rates","index.php?module=tax_rates&view=manage");
    	},
    	'invoices-preferences' : function(){
    		updateTab('tab1',"Invoice Preferences","index.php?module=invoices&view=manage");
    	},
    	'payment-types' : function(){
    		updateTab('tab1',"Payment Types","index.php?module=payments&view=manage");
    	},
    	'database-upgrade' : function(){
    		updateTab('tab1',"Database Upgrade","index.php?module=options&view=manage_sqlpatches");
    	},
    	'backup-database' : function(){
    		updateTab('tab1',"Backup Database","index.php?module=options&view=backup_database");
    	}
    };
    
    function doAction(e, t){
    	e.stopEvent();
    	actions[t.id]();
    }
    
    // This must come after the viewport setup, so the body has been initialized
  	actionPanel.body.on('mousedown', doAction, null, {delegate:'a'});

	
   
 });