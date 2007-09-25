Ext.onReady(function() {
	if($("live-grid")){
		var grid = new Ext.grid.TableGrid("live-grid");
		grid.render();
	}
});