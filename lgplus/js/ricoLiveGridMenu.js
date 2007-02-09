if(typeof Rico=='undefined')
  throw("GridMenu requires the Rico JavaScript framework");

Rico.GridMenu = Class.create();

Rico.GridMenu.prototype = {

initialize: function(options) {
  this.options = {
    width           : '20em',
    dataMenuHandler : null          // put custom items on the menu
  };
  Object.extend(this.options, options || {});
  Object.extend(this, new Rico.Menu(this.options));
  this.sortmenu = new Rico.Menu(this.options);
  this.filtermenu = new Rico.Menu(this.options);
  this.exportmenu = new Rico.Menu(this.options);
  this.hideshowmenu = new Rico.Menu(this.options);
},

registerGrid: function(liveGrid) {
  this.liveGrid = liveGrid;
  this.createDiv();
  this.sortmenu.createDiv();
  this.filtermenu.createDiv();
  this.exportmenu.createDiv();
  this.hideshowmenu.createDiv();
},

// Build context menu for grid
buildGridMenu: function(r,c) {
  this.clearMenu();
  var totrows=this.liveGrid.buffer.totalRows;
  var onBlankRow=r >= totrows;
  var column=this.liveGrid.columns[c];
  if (this.options.dataMenuHandler) {
     var showMenu=this.options.dataMenuHandler(this.liveGrid,r,c,onBlankRow);
     if (!showMenu) return false;
  }

  // menu items for sorting
  if (column.sortable && totrows>0) {
    this.sortmenu.clearMenu();
    this.addSubMenuItem(RicoTranslate.getPhrase("Sort by")+": "+column.displayName, this.sortmenu, false);
    this.sortmenu.addMenuItem("Ascending", column.sortAsc.bind(column), true);
    this.sortmenu.addMenuItem("Descending", column.sortDesc.bind(column), true);
  }

  // menu items for filtering
  if (column.canFilter() && !onBlankRow) {
    this.filtermenu.clearMenu();
    this.addSubMenuItem(RicoTranslate.getPhrase("Filter by")+": "+column.displayName, this.filtermenu, false);
    column.userFilter=column.getValue(r);
    if (column.filterType == Rico.TableColumn.USERFILTER) {
      if (column.filterOp=='LIKE' && !onBlankRow)
        this.filtermenu.addMenuItem("Change keyword...", column.setFilterKW.bind(column), true);
      if (column.filterOp=='NE' && !onBlankRow)
        this.filtermenu.addMenuItem("Exclude this value\t also", column.addFilterNE.bind(column), true);
      this.filtermenu.addMenuItem("Remove filter", column.setUnfiltered.bind(column), true);
    } else if (!onBlankRow) {
      this.filtermenu.addMenuItem("Include only this value", column.setFilterEQ.bind(column), true);
      this.filtermenu.addMenuItem("Greater than or equal to this value", column.setFilterGE.bind(column), column.userFilter!='');
      this.filtermenu.addMenuItem("Less than or equal to this value", column.setFilterLE.bind(column), column.userFilter!='');
      if (column.isText)
        this.filtermenu.addMenuItem("Contains keyword...", column.setFilterKW.bind(column), true);
      this.filtermenu.addMenuItem("Exclude this value", column.setFilterNE.bind(column), true);
    }
  }

  // menu items for Print/Export
  if (this.liveGrid.options.maxPrint > 0 && totrows>0) {
    this.exportmenu.clearMenu();
    this.addSubMenuItem('Print\t/Export',this.exportmenu);
    this.exportmenu.addMenuItem("Visible rows to web page", this.liveGrid.printVisible.bind(this.liveGrid,'plain'), true);
    this.exportmenu.addMenuItem("All rows to web page", this.liveGrid.printAll.bind(this.liveGrid,'plain'), this.liveGrid.buffer.totalRows <= this.liveGrid.options.maxPrint);
    if (Rico.isIE) {
      this.exportmenu.addMenuBreak();
      this.exportmenu.addMenuItem("Visible rows to spreadsheet", this.liveGrid.printVisible.bind(this.liveGrid,'owc'), true);
      this.exportmenu.addMenuItem("All rows to spreadsheet", this.liveGrid.printAll.bind(this.liveGrid,'owc'), this.liveGrid.buffer.totalRows <= this.liveGrid.options.maxPrint);
    }
  }

  // menu items for hide/unhide
  var hiddenCols=this.liveGrid.listInvisible();
  for (var showableCnt=0,x=0; x<hiddenCols.length; x++)
    if (hiddenCols[x].canHideShow()) showableCnt++;
  if (showableCnt > 0 || column.canHideShow()) {
    this.hideshowmenu.clearMenu();
    this.addSubMenuItem('Hide\t/Show',this.hideshowmenu);
    var visibleCnt=this.liveGrid.columns.length-hiddenCols.length;
    var enabled=(visibleCnt>1 && column.visible && column.canHideShow());
    this.hideshowmenu.addMenuItem(RicoTranslate.getPhrase('Hide')+': '+column.displayName, column.hideColumn.bind(column), enabled);
    for (var cnt=0,x=0; x<hiddenCols.length; x++) {
      if (hiddenCols[x].canHideShow()) {
        if (cnt++==0) this.hideshowmenu.addMenuBreak();
        this.hideshowmenu.addMenuItem(RicoTranslate.getPhrase('Show')+': '+hiddenCols[x].displayName, hiddenCols[x].showColumn.bind(hiddenCols[x]));
      }
    }
    if (hiddenCols.length > 1)
      this.hideshowmenu.addMenuItem(RicoTranslate.getPhrase('Show All'), this.liveGrid.showAll.bind(this.liveGrid));
  }
  return true;
}

}

Rico.addPreloadMsg('exec: ricoLiveGridMenu.js');
