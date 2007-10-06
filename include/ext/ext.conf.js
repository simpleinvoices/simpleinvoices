Ext.onReady(function() {
	if($("#live-grid").size()){
		var grid = new Ext.grid.TableGrid("live-grid");
		grid.render();
		
		var gridFoot = grid.getView().getFooterPanel(true);
		
		 var ds  = new Ext.data.Store({
	        // create reader that reads the Topic records
	        reader: new Ext.data.JsonReader({
	            root: 'topics',
	            totalProperty: 'totalCount',
	            id: 'post_id'
	        }, [
	            {name: 'title', mapping: 'topic_title'},
	            {name: 'author', mapping: 'author'},
	            {name: 'totalPosts', mapping: 'topic_replies', type: 'int'},
	            {name: 'lastPost', mapping: 'post_time', type: 'date', dateFormat: 'timestamp'},
	            {name: 'excerpt', mapping: 'post_text'}
	        ])
	    });
		
	    // add a paging toolbar to the grid's footer
	    var paging = new Ext.PagingToolbar(gridFoot, ds, {
	        pageSize: 25,
	        displayInfo: true,
	        displayMsg: 'total {2} results found. Current shows {0} - {1}',
	        emptyMsg: "not result to display"
	    });
		
	}
});