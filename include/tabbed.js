// TODO integrate the new layout with the template
Ext.onReady(function(){
	    
    // Group box containing actions
    var invoicePanel = new Ext.Panel({
    	frame:true,
    	title: 'Create an Invoice',
    	collapsible:true,
    	contentEl:'invoices',
    	titleCollapse: true
    });
    
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
    
    // West Panel containing action box
    var actionPanel = new Ext.Panel({
    	id:'action-panel',
    	region:'west',
    	split:true,
    	collapsible: true,
    	collapseMode: 'mini',
    	width:200,
    	minWidth: 150,
    	border: false,
    	baseCls:'x-plain',
    	items: [invoicePanel,customerPanel,productPanel,billerPanel,paymentPanel,optionPanel]
    });
    
    // Main (Tabbed) Panel
    var tabPanel = new Ext.TabPanel({
		region:'center',
		deferredRender:false,
		autoScroll: true, 
		margins:'0 4 4 0',
		activeTab:0,
		items:[{
			id:'tab1',
			contentEl:'tabs',
    		title: 'Main',
    		closable:false,
    		autoScroll:true
		}]
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
    		addTab("Manage Invoices",'./index.php?module=invoices&view=manage');
    	},
    	'new-invoice-total' : function(){
    		updateTab('tab1',"New Invoice: Total","index.php?module=invoices&view=total");
    	},
    	'new-invoice-itemised' : function(){
    		updateTab('tab1',"New Invoice: Itemised","index.php?module=invoices&view=itemised");
    	},
    	'new-invoice-consulting' : function(){
    		updateTab('tab1',"New Invoice: Consulting","index.php?module=invoices&view=consulting");
    	},
    	'invoice-types' : function(){
    		updateTab('invoice-tab','Invoice Help',"./docs.php?p=ReadMe#faqs-types");
    	}
    };
    
    function doAction(e, t){
    	e.stopEvent();
    	actions[t.id]();
    }
    
    // This must come after the viewport setup, so the body has been initialized
  	actionPanel.body.on('mousedown', doAction, null, {delegate:'a'});

	
   
 });