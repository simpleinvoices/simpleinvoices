/**
  *
  *  PORTIONS OF THIS FILE ARE BASED ON RICO LIVEGRID 1.1.2
  *
  *  Copyright 2005 Sabre Airline Solutions
  *
  *  Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
  *  file except in compliance with the License. You may obtain a copy of the License at
  *
  *         http://www.apache.org/licenses/LICENSE-2.0
  *
  *  Unless required by applicable law or agreed to in writing, software distributed under the
  *  License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
  *  either express or implied. See the License for the specific language governing permissions
  *  and limitations under the License.
  *
  *
  *  Enhanced by Matt Brown
  *  2006-2007
  *  Email: dowdybrown@yahoo.com
  *  Web:   dowdybrown.com
  *
  *  Resizable columns
  *  Hide/show columns
  *  Filtering
  *  Enabled dynamic table sizing (accepts <rowcount> tag in xmlhttp response)
  *  Enabled compatibility with Opera, Safari, Konqueror
  *  Can optionally use a textarea to display debug messages about xmlhttprequest traffic
  *
  **/


if(typeof Rico=='undefined') throw("LiveGrid requires the Rico JavaScript framework");
if(typeof RicoUtil=='undefined') throw("LiveGrid requires the RicoUtil Library");
if(typeof RicoTranslate=='undefined') throw("LiveGrid requires the RicoTranslate Library");
if(typeof Rico.TableColumn=='undefined') throw("LiveGrid requires the SimpleGrid Library");

// Rico.Buffer -----------------------------------------------------

Rico.Buffer = {};

// define base buffer routines
// assumes initial data is passed in via an existing html table (no ajax)
Rico.Buffer.Base = Class.create();

Rico.Buffer.Base.prototype = {

  initialize: function(dataTable, options) {
    this.clear();
    this.updateInProgress = false;
    this.lastOffset = 0;
    this.rcvdRowCount = false;  // true if an eof element was included in the last xml response
    this.foundRowCount = false; // true if an xml response is ever received with eof true
    this.totalRows = 0;
    this.rowcntContent = "";
    this.rcvdOffset = -1;
    this.rcvdRows = 0;
    this.options = {
      fixedHdrRows     : 0,
      canFilter        : false, // does buffer object support filtering?
      isEncoded        : true,  // is the data received via ajax html encoded?
      acceptAttr       : []     // attributes that can be copied from original/ajax data (e.g. className, style, id)
    }
    Object.extend(this.options, options || {});
    if (dataTable) this.loadRowsFromTable(dataTable);
  },

  registerGrid: function(liveGrid) {
    this.liveGrid = liveGrid;
  },

  setTotalRows: function( newTotalRows ) {
    if (this.totalRows == newTotalRows) return;
    this.totalRows = newTotalRows;
    if (this.liveGrid) {
      Rico.writeDebugMsg("setTotalRows, newTotalRows="+newTotalRows);
      this.liveGrid.updateHeightDiv();
    }
  },

  loadRowsFromTable: function(tableElement) {
    this.rows = this.dom2jstable(tableElement,this.options.fixedHdrRows);
    this.startPos = 0;
    this.size = this.rows.length;
    this.setTotalRows(this.size);
    this.rowcntContent = this.size.toString();
    this.rcvdRowCount = true;
    this.foundRowCount = true;
  },

  dom2jstable: function(rowsElement,firstRow) {
    var newRows = new Array();
    var trs = rowsElement.getElementsByTagName("tr");
    this.rcvdRows=trs.length;
    var acceptAttr=this.options.acceptAttr;
    for ( var i=firstRow || 0; i < trs.length; i++ ) {
      var row = new Array();
      var cells = trs[i].getElementsByTagName("td");
      for ( var j=0; j < cells.length ; j++ ) {
        row[j]={};
        row[j].content=RicoUtil.getContentAsString(cells[j],this.options.isEncoded);
        for (var k=0; k<acceptAttr.length; k++) {
          row[j]['_'+acceptAttr[k]]=cells[j].getAttribute(acceptAttr[k]);
        }
        if (Rico.isIE) row[j]._class=cells[j].getAttribute('className');
      }
      newRows.push( row );
    }
    return newRows;
  },

  insertRow: function(beforeRowIndex) {
    var newRow=[];
    for (var i=0; i<this.rows[0].length; i++) {
      newRow[i]={};
      newRow[i].content='';
    }
    this.rows.splice(beforeRowIndex,0,newRow);
  },

  sortBuffer: function(colnum,sortdir,coltype,getvalfunc) {
    this.sortColumn=colnum;
    this.getValFunc=getvalfunc;
    var sortFunc;
    switch (coltype) {
      case 'number': sortFunc=this._sortNumeric.bind(this); break;
      case 'control':sortFunc=this._sortControl.bind(this); break;
      default:       sortFunc=this._sortAlpha.bind(this); break;
    }
    this.rows.sort(sortFunc);
    if (sortdir=='DESC') this.rows.reverse();
  },

  _sortAlpha: function(a,b) {
    var aa = this.sortColumn<a.length ? RicoUtil.getInnerText(a[this.sortColumn].content) : '';
    var bb = this.sortColumn<b.length ? RicoUtil.getInnerText(b[this.sortColumn].content) : '';
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
  },

  _sortNumeric: function(a,b) {
    var aa = this.sortColumn<a.length ? parseFloat(RicoUtil.getInnerText(a[this.sortColumn].content)) : 0;
    if (isNaN(aa)) aa = 0;
    var bb = this.sortColumn<b.length ? parseFloat(RicoUtil.getInnerText(b[this.sortColumn].content)) : 0;
    if (isNaN(bb)) bb = 0;
    return aa-bb;
  },

  _sortControl: function(a,b) {
    var aa = this.sortColumn<a.length ? RicoUtil.getInnerText(a[this.sortColumn].content) : '';
    var bb = this.sortColumn<b.length ? RicoUtil.getInnerText(b[this.sortColumn].content) : '';
    if (this.getValFunc) {
      aa=this.getValFunc(aa);
      bb=this.getValFunc(bb);
    }
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
  },

  clear: function() {
    this.rows = new Array();
    this.startPos = -1;
    this.size = 0;
    this.windowPos = 0;
  },

  isInRange: function(position) {
    var lastRow=Math.min(this.totalRows, position + this.liveGrid.pageSize)
    return (position >= this.startPos) && (lastRow <= this.endPos()); // && (this.size != 0);
  },

  endPos: function() {
    return this.startPos + this.rows.length;
  },

  fetch: function(offset) {
    this.liveGrid.refreshContents(offset);
    return;
  },

  exportAllRows: function(populate,finish) {
    populate(this.getRows(0,this.totalRows));
    finish();
  },

  setWindow: function(start, count) {
    this.windowStart = start - this.startPos;
    this.windowEnd = Math.min(this.windowStart + count,this.size);
    this.windowPos = start;
  },

  getWindowValue: function(windowRow,col) {
    var cell=this.getWindowCell(windowRow,col);
    return cell ? cell.content : null;
  },

  getWindowCell: function(windowRow,col) {
    var bufrow=this.windowStart+windowRow;
    return bufrow < this.windowEnd && col < this.rows[bufrow].length ? this.rows[bufrow][col] : null;
  },

  setWindowValue: function(windowRow,col,newval) {
    var bufrow=this.windowStart+windowRow;
    if (bufrow >= this.windowEnd) return false;
    return this.setValue(bufrow,col,newval);
  },

  setValue: function(row,col,newval) {
    if (row>=this.size) return false;
    if (!this.rows[row][col]) this.rows[row][col]={};
    this.rows[row][col].content=newval;
    this.rows[row][col].modified=true;
    return true;
  },

  getRows: function(start, count) {
    var begPos = start - this.startPos;
    var endPos = Math.min(begPos + count,this.size);
    var results = new Array();
    for ( var i=begPos; i < endPos; i++ )
      results.push(this.rows[i]);
    return results
  }

};


// Rico.LiveGrid -----------------------------------------------------

Rico.LiveGrid = Class.create();

Rico.LiveGrid.prototype = {

  initialize: function( tableId, menu, buffer, options ) {

    Object.extend(this, new Rico.SimpleGrid());
    Object.extend(this, new Rico.LiveGridMethods());
    this.tableId = tableId;
    this.buffer = buffer;
    Rico.setDebugArea(tableId+"_debugmsgs");    // if used, this should be a textarea

    Object.extend(this.options, {
      visibleRows      : -1,    // -1 or 'window'=size grid to client window; -2 or 'data'=size grid to min(window,data)
      frozenColumns    : 0,
      offset           : 0,     // first row to be displayed
      prefetchBuffer   : true,  // load table on page load?
      minPageRows      : 1,
      maxPageRows      : 50,
      canSortDefault   : true,  // can be overridden in the column specs
      canFilterDefault : buffer.options.canFilter, // can be overridden in the column specs
      canHideDefault   : true,  // can be overridden in the column specs
      cookiePrefix     : 'liveGrid.'+tableId,
      
      // highlight & selection parameters
      highlightElem    : 'none',// what gets highlighted/selected (cursorRow, cursorCell, menuRow, menuCell, selection, or none)
      highlightSection : 3,     // which section gets highlighted (frozen=1, scrolling=2, all=3, none=0)
      selectionClass   : 'ricoLG_selection',
      
      // export/print parameters
      maxPrint         : 1000,  // max # of rows that can be printed/exported, 0=disable print/export feature
      exportWindow     : "height=300,width=500,scrollbars=1,menubar=1,resizable=1",
      
      // heading parameters
      headingSort      : true,  // make headings a clickable link that will sort column
      hdrIconsFirst    : true,  // true: put sort & filter icons before header text, false: after
      sortAscendImg    : 'sort_asc.gif',
      sortDescendImg   : 'sort_desc.gif',
      filterImg        : 'filtercol.gif'
    });
    // other options:
    //   sortCol: initial sort column

    this.options.sortHandler = this.sortHandler.bind(this);
    this.options.filterHandler = this.filterHandler.bind(this);
    this.options.onRefreshComplete = this.bookmarkHandler.bind(this);
    this.options.rowOverHandler = this.rowMouseOver.bindAsEventListener(this);
    this.options.mouseDownHandler = this.selectMouseDown.bindAsEventListener(this);
    this.options.mouseOverHandler = this.selectMouseOver.bindAsEventListener(this);
    this.options.mouseUpHandler  = this.selectMouseUp.bindAsEventListener(this);
    Object.extend(this.options, options || {});

    switch (typeof this.options.visibleRows) {
      case 'string':
        this.sizeTo=this.options.visibleRows;
        this.options.visibleRows=this.sizeTo=='window' ? -1 : -2;
        break;
      case 'number':
        switch (this.options.visibleRows) {
          case -1: this.sizeTo='window'; break;
          case -2: this.sizeTo='data'; break;
        }
        break;
      default:
        this.sizeTo='window';
        this.options.visibleRows=-1;
    }
    this.highlightEnabled=this.options.highlightSection>0;
    this.pageSize=0;
    this.createTables();
    if (this.headerColCnt==0) {
      alert('ERROR: no columns found in "'+this.tableId+'"');
      return;
    }
    this.createColumnArray();

    this.bookmark=$(tableId+"_bookmark");
    this.sizeDivs();
    Rico.writeDebugMsg("sizeDivs complete");
    this.createDataCells(this.options.visibleRows);
    if (this.pageSize == 0) return;
    this.buffer.registerGrid(this);
    if (this.buffer.setBufferSize) this.buffer.setBufferSize(this.pageSize);
    this.scrollTimeout = null;
    this.lastScrollPos = 0;
    if (menu) this.attachMenuEvents(menu);

    // preload the images...
    new Image().src = Rico.imgDir+this.options.filterImg;
    new Image().src = Rico.imgDir+this.options.sortAscendImg;
    new Image().src = Rico.imgDir+this.options.sortDescendImg;
    Rico.writeDebugMsg("images preloaded");

    this.setSortUI( this.options.sortCol, this.options.sortDir );
    if (this.listInvisible().length==this.columns.length)
      this.columns[0].showColumn();
    this.sizeDivs();
    this.scrollDiv.style.display="";
    if (buffer.totalRows>0)
      this.updateHeightDiv();
    if (this.options.prefetchBuffer==true) {
      if (this.bookmark) this.bookmark.innerHTML = RicoTranslate.getPhrase("Loading...");
      this.buffer.fetch(this.options.offset);
    }
    this.scrollEventFunc=this.handleScroll.bindAsEventListener(this);
    this.wheelEventFunc=this.handleWheel.bindAsEventListener(this);
    this.wheelEvent=(Rico.isIE || Rico.isOpera || Rico.isSafari) ? 'mousewheel' : 'DOMMouseScroll';
    if (this.options.offset && this.options.offset < buffer.totalRows)
      setTimeout(this.scrollToRow.bind(this,this.options.offset),50);  // Safari requires a delay
    this.pluginScroll();
    if (this.options.windowResize)
      Event.observe(window,"resize", this.resizeWindow.bindAsEventListener(this), false);
  }
};

Rico.LiveGridMethods = Class.create();

Rico.LiveGridMethods.prototype = {

  initialize: function() {
  },

  // transform original table(s) into a frozen table and a horizontally scrollable table
  // and add a div to contain them
  // returns the number of visible rows (-1 indicates failure)
  createTables: function() {
    var insertloc;
    var result = -1;
    var table = $(this.tableId);
    if (!table) return result;
    if (table.tagName.toLowerCase()=='table') {
      var theads=table.getElementsByTagName("thead");
      if (theads.length == 1) {
        Rico.writeDebugMsg("createTables: using thead section, id="+this.tableId);
        var hdrSrc=theads[0].rows;
      } else {
        Rico.writeDebugMsg("createTables: using tbody section, id="+this.tableId);
        var hdrSrc=new Array(table.rows[0]);
      }
      insertloc=table;
    } else if (this.options.columnSpecs.length > 0) {
      insertloc=table;
      Rico.writeDebugMsg("createTables: inserting at "+table.tagName+", id="+this.tableId);
    } else {
      alert("ERROR!\n\nUnable to initialize '"+this.tableId+"'\n\nLiveGrid terminated");
      return result;
    }

    this.createDivs();
    this.scrollTabs = this.createDiv("scrollTabs",this.innerDiv);
    this.shadowDiv  = this.createDiv("shadow",this.scrollDiv);
    this.messageDiv = this.createDiv("message",this.outerDiv);
    this.messageDiv.style.display="none";
    this.messageShadow=new Rico.Shadow(this.messageDiv);
    this.scrollDiv.style.display="none";
    switch (this.options.highlightElem) {
      case 'cursorRow':
        this.highlightDiv = this.createDiv("highlight",this.outerDiv);
        this.highlightDiv.style.display="none";
        break;
      case 'cursorCell':
        this.highlightDiv=[];
        for (var i=0; i<2; i++) {
          this.highlightDiv[i] = this.createDiv("highlight",i==0 ? this.frozenTabs : this.scrollTabs);
          this.highlightDiv[i].style.display="none";
          this.highlightDiv[i].id+=i;
        }
        break;
    }

    // create new tables
    for (var i=0; i<2; i++) {
      this.tabs[i] = document.createElement("table");
      this.tabs[i].className = 'ricoLG_table';
      this.tabs[i].border=0;
      this.tabs[i].cellPadding=0;
      this.tabs[i].cellSpacing=0;
      this.tabs[i].id = this.tableId+"_tab"+i;
      this.thead[i]=this.tabs[i].createTHead();
      this.thead[i].className='ricoLG_top';
      if (this.tabs[i].tBodies.length==0)
        this.tbody[i]=this.tabs[i].appendChild(document.createElement("tbody"));
      else
        this.tbody[i]=this.tabs[i].tBodies[0];
      this.tbody[i].className='ricoLG_bottom';
      this.tbody[i].insertRow(-1);
    }
    this.frozenTabs.appendChild(this.tabs[0]);
    this.scrollTabs.appendChild(this.tabs[1]);
    insertloc.parentNode.insertBefore(this.outerDiv,insertloc);
    if (hdrSrc)
      this.loadHdrSrc(hdrSrc);
    else
      this.createHdr();
    for( var c=0; c < this.headerColCnt; c++ )
      this.tbody[c<this.options.frozenColumns ? 0 : 1].rows[0].insertCell(-1);
    if (table) table.parentNode.removeChild(table);
  },

  createDataCells: function(visibleRows) {
    if (visibleRows < 0) {
      this.autoAppendRows();
    } else {
      for( var r=0; r < visibleRows; r++ )
        this.appendBlankRow();
    }
    var s=this.options.highlightSection;
    if (s & 1) this.attachHighlightEvents(this.tbody[0]);
    if (s & 2) this.attachHighlightEvents(this.tbody[1]);
    return;
  },

  createHdr: function() {
    for (var i=0; i<2; i++) {
      var start=(i==0) ? 0 : this.options.frozenColumns;
      var limit=(i==0) ? this.options.frozenColumns : this.options.columnSpecs.length;
      if (this.options.PanelNamesOnTabHdr && this.options.panels) {
        // place panel names on first row of thead
        var r = this.thead[i].insertRow(-1);
        r.className='ricoLG_hdg';
        var lastIdx=-1, span, newCell=null, spanIdx=0;
        for( var c=start; c < limit; c++ ) {
          if (lastIdx == this.options.columnSpecs[c].panelIdx) {
            span++;
          } else {
            if (newCell) newCell.colSpan=span;
            newCell = r.insertCell(-1);
            span=1;
            lastIdx=this.options.columnSpecs[c].panelIdx;
            newCell.innerHTML=this.options.panels[lastIdx];
          }
        }
        if (newCell) newCell.colSpan=span;
      }
      var mainRow = this.thead[i].insertRow(-1);
      mainRow.id=this.tableId+'_tab'+i+'h_main';
      mainRow.className='ricoLG_hdg';
      for( var c=start; c < limit; c++ ) {
        var newCell = mainRow.insertCell(-1);
        newCell.innerHTML=this.options.columnSpecs[c].Hdg;
      }
      this.getColumnInfo(this.thead[i].rows);
    }
  },

  loadHdrSrc: function(hdrSrc) {
    this.getColumnInfo(hdrSrc);
    for (var i=0; i<2; i++) {
      for (var r=0; r<hdrSrc.length; r++) {
        var newrow = this.thead[i].insertRow(-1);
        newrow.className='ricoLG_hdg';
      }
    }
    if (hdrSrc.length==1) {
      var cells=hdrSrc[0].cells;
      for (var c=0; cells.length > 0; c++)
        this.thead[c<this.options.frozenColumns ? 0 : 1].rows[0].appendChild(cells[0]);
    } else {
      for (var r=0; r<hdrSrc.length; r++) {
        var cells=hdrSrc[r].cells;
        for (var c=0; cells.length > 0; c++) {
          if (cells[0].className=='ricoFrozen') {
            this.thead[0].rows[r].appendChild(cells[0]);
            if (r==this.headerRowIdx) this.options.frozenColumns=c+1;
          } else {
            this.thead[1].rows[r].appendChild(cells[0]);
          }
        }
      }
    }
  },

  sizeDivs: function() {
    this.sizeDivs1();
    if (this.pageSize == 0) return;
    this.rowHeight = Math.round(this.dataHt/this.pageSize);
    var scrHt=this.dataHt;
    if (this.scrWi>0 || Rico.isIE || Rico.isSafari)
      scrHt+=this.options.scrollBarWidth;
    this.scrollDiv.style.height=scrHt+'px';
    this.innerDiv.style.width=(this.scrWi-this.options.scrollBarWidth+1)+'px';
    this.resizeDiv.style.height=this.frozenTabs.style.height=this.innerDiv.style.height=(this.hdrHt+this.dataHt+1)+'px';
    pad=(this.scrWi-this.scrTabWi < this.options.scrollBarWidth) ? 2 : 0;
    this.shadowDiv.style.width=(this.scrTabWi+pad)+'px';
    this.outerDiv.style.height=(this.hdrHt+scrHt)+'px';
    this.setHorizontalScroll();
  },

  setHorizontalScroll: function() {
    var scrleft=this.scrollDiv.scrollLeft;
    this.scrollTabs.style.left=(-scrleft)+'px';
  },

  // size header cells in rows other than the main one
  resizeWindow: function() {
    Rico.writeDebugMsg('resizeWindow lastRow='+this.lastRowPos);
    this.cancelMenu();
    this.unhighlight();
    var resetHt=false;
    if (this.sizeTo) {
      var availHt=this.availHt();
      var tabHt=Math.max(this.tabs[0].offsetHeight,this.tabs[1].offsetHeight);
      if (availHt-tabHt > this.rowHeight) {
        var oldSize=this.pageSize;
        this.autoAppendRows();
        if (oldSize != this.pageSize) {
          resetHt=true;
          this.isPartialBlank=true;
          var adjStart=this.adjustRow(this.lastRowPos);
          this.buffer.fetch(adjStart);
        }
      } else if (availHt < tabHt) {
        resetHt=true;
        this.autoRemoveRows(tabHt-availHt);
        //this.isPartialBlank=true;
        //this.buffer.fetch(this.lastRowPos);
      }
    }
    this.sizeDivs();
    if (resetHt) this.updateHeightDiv();
  },

  topOfLastPage: function() {
    return Math.max(this.buffer.totalRows-this.pageSize,0);
  },

  updateHeightDiv: function() {
    var notdisp=this.topOfLastPage();
    var ht = this.scrollDiv.clientHeight + this.rowHeight * notdisp;
    //if (Rico.isOpera) ht+=this.options.scrollBarWidth-3;
    Rico.writeDebugMsg("updateHeightDiv, ht="+ht+' scrollDiv.clientHeight='+this.scrollDiv.clientHeight+' rowsNotDisplayed='+notdisp);
    this.shadowDiv.style.height=ht+'px';
  },

  autoRemoveRows: function(overage) {
    var removeCnt=parseInt(overage / this.rowHeight) + 1;
    for (var i=0; i<removeCnt; i++)
      this.removeRow();
  },

  removeRow: function() {
    if (this.pageSize <= this.options.minPageRows) return;
    this.pageSize--;
    Rico.writeDebugMsg("removeRow #"+this.pageSize);
    for( var c=0; c < this.headerColCnt; c++ ) {
      var cell=this.columns[c].cell(this.pageSize);
      this.columns[c].dataColDiv.removeChild(cell);
    }
  },

  autoAppendRows: function() {
    var availHt=this.availHt();
    do {
      if (this.sizeTo=='data' && this.pageSize>=this.buffer.totalRows) break;
      this.appendBlankRow();
      var tabHt=Math.max(this.tabs[0].offsetHeight,this.tabs[1].offsetHeight);
      this.dataHt=Math.max(RicoUtil.nan2zero(this.tbody[0].offsetHeight),this.tbody[1].offsetHeight);
      this.rowHeight = Math.round(this.dataHt/this.pageSize);
      Rico.writeDebugMsg('autoAppendRows: '+availHt+' '+tabHt+' '+this.dataHt+' '+this.rowHeight);
    } while (availHt-tabHt > this.rowHeight && this.pageSize < this.options.maxPageRows);
  },

  // on older systems, this can be fairly slow
  appendBlankRow: function() {
    if (this.pageSize >= this.options.maxPageRows) return;
    Rico.writeDebugMsg("appendBlankRow #"+this.pageSize);
    var cls=this.defaultRowClass(this.pageSize);
    for( var c=0; c < this.headerColCnt; c++ ) {
      var newdiv = document.createElement("div");
      newdiv.className = 'ricoLG_cell '+cls;
      newdiv.id=this.tableId+'_'+this.pageSize+'_'+c;
      this.columns[c].dataColDiv.appendChild(newdiv);
      newdiv.innerHTML='&nbsp;';
      if (this.columns[c]._create)
        this.columns[c]._create(newdiv,this.pageSize);
    }
    this.pageSize++;
  },

  defaultRowClass: function(rownum) {
    return (rownum % 2==0) ? 'ricoLG_evenRow' : 'ricoLG_oddRow';
  },

  handleMenuClick: function(e) {
    //Event.stop(e);
    this.cancelMenu();
    var cell=Event.element(e);
    if (cell.className=='ricoLG_highlightDiv') {
      var idx=this.highlightIdx;
    } else {
      cell=RicoUtil.getParentByTagName(cell,'div','ricoLG_cell');
      if (!cell) return;
      var idx=this.winCellIndex(cell);
      idx.tabIdx=this.columns[idx.column].tabIdx;
      if ((this.options.highlightSection & (idx.tabIdx+1))==0) return;
    }
    switch (this.options.highlightElem) {
      case 'menuRow':
        this.selectRow(idx.row);
        break;
      case 'menuCell':
        this.selectCell(cell);
        break;
      case 'cursorRow':
      case 'cursorCell':
        this.highlight(idx);
        break;
    }
    this.highlightEnabled=false;
    if (this.hideScroll) this.scrollDiv.style.overflow="hidden";
    this.menuCell=cell;
    this.menuIdx=idx;
    if (this.menu.buildGridMenu) {
      var showMenu=this.menu.buildGridMenu(idx.row, idx.column, idx.tabIdx);
      if (!showMenu) return;
    }
    this.menu.showmenu(e,this.closeMenu.bind(this));
  },

  closeMenu: function() {
    if (this.hideScroll) this.scrollDiv.style.overflow="";
    this.unhighlight();
    this.highlightEnabled=true;
  },

  // return index of cell within the window
  winCellIndex: function(cell) {
    var a=cell.id.split(/_/);
    var l=a.length;
    var r=parseInt(a[l-2]);
    var c=parseInt(a[l-1]);
    return {row:r, column:c};
  },

  // return index of cell within the dataset
  bufCellIndex: function(cell) {
    var idx=this.winCellIndex(cell);
    idx.row+=this.buffer.windowPos;
    if (idx.row >= this.buffer.size) idx.onBlankRow=true;
    return idx;
  },

  attachHighlightEvents: function(tBody) {
    switch (this.options.highlightElem) {
      case 'selection':
        Event.observe(tBody,"mousedown", this.options.mouseDownHandler, false);
        tBody.ondrag = function () { return false; };
        tBody.onselectstart = function () { return false; };
        break;
      case 'cursorRow':
      case 'cursorCell':
        Event.observe(tBody,"mouseover", this.options.rowOverHandler, false);
        break;
    }
  },

  getVisibleSelection: function() {
    var cellList=[];
    if (this.SelectIdxStart && this.SelectIdxEnd) {
      var r1=Math.max(Math.min(this.SelectIdxEnd.row,this.SelectIdxStart.row),this.buffer.windowPos);
      var r2=Math.min(Math.max(this.SelectIdxEnd.row,this.SelectIdxStart.row),this.buffer.windowEnd-1);
      var c1=Math.min(this.SelectIdxEnd.column,this.SelectIdxStart.column);
      var c2=Math.max(this.SelectIdxEnd.column,this.SelectIdxStart.column);
      for (var r=r1; r<=r2; r++)
        for (var c=c1; c<=c2; c++)
          cellList.push({row:r-this.buffer.windowPos,column:c});
    }
    if (this.SelectCtrl) {
      for (var i=0; i<this.SelectCtrl.length; i++) {
        if (this.SelectCtrl[i].row>=this.buffer.windowPos && this.SelectCtrl[i].row<this.buffer.windowEnd)
          cellList.push({row:this.SelectCtrl[i].row-this.buffer.windowPos,column:this.SelectCtrl[i].column});
      }
    }
    return cellList;
  },

  HideSelection: function(cellList) {
    var cellList=this.getVisibleSelection();
    for (var i=0; i<cellList.length; i++)
      this.unselectCell(this.columns[cellList[i].column].cell(cellList[i].row));
  },

  ShowSelection: function(cellList) {
    var cellList=this.getVisibleSelection();
    for (var i=0; i<cellList.length; i++)
      this.selectCell(this.columns[cellList[i].column].cell(cellList[i].row));
  },

  ClearSelection: function() {
    this.HideSelection();
    this.SelectIdxStart=null;
    this.SelectIdxEnd=null;
    this.SelectCtrl=[];
  },

  AdjustSelection: function(cell) {
    var newIdx=this.bufCellIndex(cell);
    if (this.SelectTabIdx != this.columns[newIdx.column].tabIdx) return;
    this.HideSelection();
    this.SelectIdxEnd=newIdx;
    this.ShowSelection();
  },

  FillSelection: function(newVal) {
    if (this.SelectIdxStart && this.SelectIdxEnd) {
      var r1=Math.min(this.SelectIdxEnd.row,this.SelectIdxStart.row);
      var r2=Math.max(this.SelectIdxEnd.row,this.SelectIdxStart.row);
      var c1=Math.min(this.SelectIdxEnd.column,this.SelectIdxStart.column);
      var c2=Math.max(this.SelectIdxEnd.column,this.SelectIdxStart.column);
      for (var r=r1; r<=r2; r++)
        for (var c=c1; c<=c2; c++)
          this.buffer.setValue(r,c,newVal);
    }
    if (this.SelectCtrl) {
      for (var i=0; i<this.SelectCtrl.length; i++)
        this.buffer.setValue(this.SelectCtrl[i].row,this.SelectCtrl[i].column,newVal);
    }
    var cellList=this.getVisibleSelection();
    for (var i=0; i<cellList.length; i++)
      this.columns[cellList[i].column].displayValue(cellList[i].row);
  },

  selectMouseDown: function(e) {
    if (this.highlightEnabled==false) return true;
    this.cancelMenu();
    var cell=Event.element(e);
    Event.stop(e);
    if (!Event.isLeftClick(e)) return;
    cell=RicoUtil.getParentByTagName(cell,'div','ricoLG_cell');
    if (!cell) return;
    var newIdx=this.bufCellIndex(cell);
    if (newIdx.onBlankRow) return;
    if (e.ctrlKey) {
      if (!this.SelectIdxStart) return;
      if (!this.isSelected(cell)) {
        this.selectCell(cell);
        this.SelectCtrl.push(this.bufCellIndex(cell));
      } else {
        for (var i=0; i<this.SelectCtrl.length; i++) {
          if (this.SelectCtrl[i].row==newIdx.row && this.SelectCtrl[i].column==newIdx.column) {
            this.unselectCell(cell);
            this.SelectCtrl.splice(i,1);
            break;
          }
        }
      }
    } else if (e.shiftKey) {
      if (!this.SelectIdxStart) return;
      this.AdjustSelection(cell);
    } else {
      this.ClearSelection();
      this.SelectIdxStart=this.SelectIdxEnd=this.bufCellIndex(cell);
      this.SelectTabIdx=this.columns[this.SelectIdxStart.column].tabIdx;
      this.selectCell(cell);
      this.pluginSelect();
    }
  },

  pluginSelect: function() {
    if (this.selectPluggedIn) return;
    var tBody=this.tbody[this.SelectTabIdx];
    Event.observe(tBody,"mouseover", this.options.mouseOverHandler, false);
    Event.observe(tBody,"mouseup",  this.options.mouseUpHandler,  false);
    this.selectPluggedIn=true;
  },

  unplugSelect: function() {
    var tBody=this.tbody[this.SelectTabIdx];
    Event.stopObserving(tBody,"mouseover", this.options.mouseOverHandler , false);
    Event.stopObserving(tBody,"mouseup", this.options.mouseUpHandler , false);
    this.selectPluggedIn=false;
  },

  selectMouseUp: function(e) {
    this.unplugSelect();
    var cell=Event.element(e);
    cell=RicoUtil.getParentByTagName(cell,'div','ricoLG_cell');
    if (!cell) return;
    if (this.SelectIdxStart && this.SelectIdxEnd)
      this.AdjustSelection(cell);
    else
      this.ClearSelection();
  },

  selectMouseOver: function(e) {
    var cell=Event.element(e);
    cell=RicoUtil.getParentByTagName(cell,'div','ricoLG_cell');
    if (!cell) return;
    this.AdjustSelection(cell);
    Event.stop(e);
  },

  isSelected: function(cell) {
    var n=cell.className.split(' ');
    return (n && n.length>0 && n[0]==this.options.selectionClass);
  },

  selectCell: function(cell) {
    var n=cell.className.split(' ');
    if (n && n.length>0 && n[0]==this.options.selectionClass) return;
    n.unshift(this.options.selectionClass);
    cell.className=n.join(' ');
  },

  unselectCell: function(cell) {
    if (cell==null) return;
    var n=cell.className.split(' ');
    if (n && n.length>0 && n[0]!=this.options.selectionClass) return;
    n.shift();
    cell.className=n.join(' ');
  },

  selectRow: function(r) {
    for (var c=0; c<this.columns.length; c++)
      this.selectCell(this.columns[c].cell(r));
  },

  unselectRow: function(r) {
    for (var c=0; c<this.columns.length; c++)
      this.unselectCell(this.columns[c].cell(r));
  },

  rowMouseOver: function(e) {
    if (!this.highlightEnabled) return;
    var cell=Event.element(e);
    cell=RicoUtil.getParentByTagName(cell,'div','ricoLG_cell');
    if (!cell) return;
    this.highlightCell(cell);
  },

  highlightCell: function(cell) {
    var newIdx=this.winCellIndex(cell);
    newIdx.tabIdx=this.columns[newIdx.column].tabIdx;
    if ((this.options.highlightSection & (newIdx.tabIdx+1))==0) return;
    this.highlight(newIdx);
  },

  highlight: function(newIdx) {
    switch (this.options.highlightElem) {
      case 'cursorCell':
        var div=this.highlightDiv[newIdx.tabIdx];
        div.style.left=(this.columns[newIdx.column].dataCell.offsetLeft-1)+'px';
        div.style.width=this.columns[newIdx.column].colWidth;
        this.highlightDiv[1-newIdx.tabIdx].style.display='none';
        break;
      case 'cursorRow':
        var div=this.highlightDiv;
        var s1=this.options.highlightSection & 1;
        var s2=this.options.highlightSection & 2;
        div.style.left=s1 ? '0px' : this.frozenTabs.style.width;
        div.style.width=((s1 ? this.frozenTabs.offsetWidth : 0) + (s2 ? this.innerDiv.offsetWidth : 0) - 4)+'px';
        div.style.display='';
        break;
      default: return;
    }
    div.style.top=(this.hdrHt+newIdx.row*this.rowHeight-1)+'px';
    div.style.height=(this.rowHeight-1)+'px';
    div.style.display='';
    this.highlightIdx=newIdx;
  },

  unhighlight: function() {
    switch (this.options.highlightElem) {
      case 'menuRow':
        if (this.menuIdx) this.unselectRow(this.menuIdx.row);
        break;
      case 'menuCell':
        this.unselectCell(this.menuCell);
        break;
      case 'cursorCell':
        for (var i=0; i<2; i++)
          this.highlightDiv[i].style.display='none';
        break;
      case 'cursorRow':
        this.highlightDiv.style.display='none';
        break;
    }
  },

  hideMsg: function() {
    if (this.messageDiv.style.display=="none") return;
    this.messageDiv.style.display="none";
    this.messageShadow.hide();
  },

  showMsg: function(msg) {
    this.messageDiv.innerHTML=RicoTranslate.getPhrase(msg);
    this.messageDiv.style.display="";
    var msgWidth=this.messageDiv.offsetWidth;
    var msgHeight=this.messageDiv.offsetHeight;
    var divwi=this.outerDiv.offsetWidth;
    var divht=this.outerDiv.offsetHeight;
    this.messageDiv.style.top=parseInt((divht-msgHeight)/2)+'px';
    this.messageDiv.style.left=parseInt((divwi-msgWidth)/2)+'px';
    this.messageShadow.show();
    Rico.writeDebugMsg("showMsg: "+msg);
  },

  resetContents: function(resetHt) {
    Rico.writeDebugMsg("resetContents("+resetHt+")");
    this.buffer.clear();
    this.clearRows();
    if (typeof resetHt=='undefined' || resetHt==true) {
      this.buffer.setTotalRows(0);
    } else {
      this.scrollToRow(0);
    }
    if (this.bookmark) this.bookmark.innerHTML="&nbsp;";
  },

  setImages: function() {
    for (n=0; n<this.columns.length; n++)
      this.columns[n].setImage();
  },

  setSortUI: function( columnNameOrNum, sortDirection ) {
    var colnum;
    Rico.writeDebugMsg("setSortUI: "+columnNameOrNum+' '+sortDirection);
    if (typeof sortDirection!='string') return;
    if (typeof columnNameOrNum=='string') {
      for ( var i = 0 ; i < this.columns.length ; i++ ) {
        if ( this.columns[i].fieldName == columnNameOrNum ) {
          colnum=i;
          break;
        }
      }
    } else {
      colnum=columnNameOrNum;
    }
    if (typeof colnum!='number') return;
    this.clearSort();
    this.columns[colnum].setSorted(sortDirection);
    this.setImages();
    this.buffer.sortBuffer(colnum,sortDirection,this.columns[colnum].format.type,this.columns[colnum]._sortfunc);
  },

  // clear sort flag on all columns
  clearSort: function() {
    for (var x=0;x<this.columns.length;x++)
    this.columns[x].setUnsorted();
  },

  sortHandler: function() {
    this.cancelMenu();
    this.setImages();
    for (var n=0; n<this.columns.length; n++)
      if (this.columns[n].isSorted()) {
        Rico.writeDebugMsg("sortHandler: sorting column "+n);
        this.buffer.sortBuffer(n,this.columns[n].getSortDirection(),this.columns[n].format.type,this.columns[n]._sortfunc);
        this.clearRows();
        this.scrollDiv.scrollTop = 0;
        break;
      }
    this.buffer.fetch(0);
  },

  filterHandler: function() {
    this.cancelMenu();
    this.ClearSelection();
    this.setImages();
    Rico.writeDebugMsg("filterHandler");
    this.resetContents(true);
    this.buffer.foundRowCount = false;
    this.buffer.fetch(0);
  },

  bookmarkHandler: function(firstrow,lastrow) {
    if (isNaN(firstrow) || !this.bookmark) return;
    var totrows=this.buffer.totalRows;
    if (totrows < lastrow) lastrow=totrows;
    if (totrows<=0) {
      var newhtml = RicoTranslate.getPhrase("No matching records");
    } else if (lastrow<0) {
      var newhtml = RicoTranslate.getPhrase("No records");
    } else {
      var newhtml = RicoTranslate.getPhrase("Listing records")+" "+firstrow+" - "+lastrow;
      var totphrase = this.buffer.foundRowCount ? "of" : "of about";
      newhtml+=" "+RicoTranslate.getPhrase(totphrase)+" "+totrows;
    }
    this.bookmark.innerHTML = newhtml;
  },

  // Return an array of column objects which have invisible status
  listInvisible: function() {
    var hiddenColumns=new Array();
    for (var x=0;x<this.columns.length;x++)
      if (this.columns[x].visible==false)
        hiddenColumns.push(this.columns[x]);
    return hiddenColumns;
  },

  // Show all columns
  showAll: function() {
    var invisible=this.listInvisible();
    for (var x=0;x<invisible.length;x++)
      invisible[x].showColumn();
  },

  clearRows: function() {
    if (this.isBlank==true) return;
    for (var c=0; c < this.columns.length; c++)
      this.columns[c].clearColumn();
    this.ClearSelection();
    this.isBlank = true;
  },

  blankRow: function(r) {
     for (var c=0; c < this.columns.length; c++)
        this.columns[c].clearCell(r);
  },

  refreshContents: function(startPos) {
    Rico.writeDebugMsg("refreshContents: startPos="+startPos+" lastRow="+this.lastRowPos+" PartBlank="+this.isPartialBlank+" pageSize="+this.pageSize);
    this.hideMsg();
    this.cancelMenu();
    this.highlightEnabled=this.options.highlightSection!='none';
    if (startPos == this.lastRowPos && !this.isPartialBlank && !this.isBlank) return;
    this.isBlank = false;
    var viewPrecedesBuffer = this.buffer.startPos > startPos
    var contentStartPos = viewPrecedesBuffer ? this.buffer.startPos: startPos;
    var contentEndPos = Math.min(this.buffer.startPos + this.buffer.size, startPos + this.pageSize);
    var onRefreshComplete = this.options.onRefreshComplete;

    if ((startPos + this.pageSize < this.buffer.startPos)
        || (this.buffer.startPos + this.buffer.size < startPos)
        || (this.buffer.size == 0)) {
      this.clearRows();
      if (onRefreshComplete != null)
          onRefreshComplete(contentStartPos+1,contentEndPos);
      return;
    }

    //window.status='refreshContents: contentStartPos='+contentStartPos+' contentEndPos='+contentEndPos+' viewPrecedesBuffer='+viewPrecedesBuffer;
    if (this.options.highlightElem=='selection') this.HideSelection();
    var rowSize = contentEndPos - contentStartPos;
    this.buffer.setWindow(contentStartPos, rowSize );
    var blankSize = this.pageSize - rowSize;
    var blankOffset = viewPrecedesBuffer ? 0: rowSize;
    var contentOffset = viewPrecedesBuffer ? blankSize: 0;

    for (var r=0; r < rowSize; r++) { //initialize what we have
      for (var c=0; c < this.columns.length; c++)
        this.columns[c].displayValue(r + contentOffset);
    }
    for (var i=0; i < blankSize; i++)     // blank out the rest
      this.blankRow(i + blankOffset);
    if (this.options.highlightElem=='selection') this.ShowSelection();
    this.isPartialBlank = blankSize > 0;
    this.lastRowPos = startPos;
    Rico.writeDebugMsg("refreshContents complete, startPos="+startPos);
    // Check if user has set a onRefreshComplete function
    if (onRefreshComplete != null)
      onRefreshComplete(contentStartPos+1,contentEndPos);
  },

  scrollToRow: function(rowOffset) {
     var p=this.rowToPixel(rowOffset);
     Rico.writeDebugMsg("scrollToRow, rowOffset="+rowOffset+" pixel="+p);
     this.scrollDiv.scrollTop = p;
     if ( this.options.onscroll )
        this.options.onscroll( this, rowOffset );
  },

  scrollUp: function() {
     this.moveRelative(-1);
  },

  scrollDown: function() {
     this.moveRelative(1);
  },

  pageUp: function() {
     this.moveRelative(-this.pageSize);
  },

  pageDown: function() {
     this.moveRelative(this.pageSize);
  },

  adjustRow: function(rowOffset) {
     var notdisp=this.topOfLastPage();
     if (notdisp == 0) return 0;
     return Math.min(notdisp,rowOffset);
  },

  rowToPixel: function(rowOffset) {
     return this.adjustRow(rowOffset) * this.rowHeight;
  },

  // returns row to display at top of scroll div
  pixeltorow: function(p) {
     var notdisp=this.topOfLastPage();
     if (notdisp == 0) return 0;
     var prow=parseInt(p/this.rowHeight);
     return Math.min(notdisp,prow);
  },

  moveRelative: function(relOffset) {
     newoffset=Math.max(this.scrollDiv.scrollTop+relOffset*this.rowHeight,0);
     newoffset=Math.min(newoffset,this.scrollDiv.scrollHeight);
     //Rico.writeDebugMsg("moveRelative, newoffset="+newoffset);
     this.scrollDiv.scrollTop=newoffset;
  },

  pluginScroll: function() {
     if (this.scrollPluggedIn) return;
     Rico.writeDebugMsg("pluginScroll: wheelEvent="+this.wheelEvent);
     Event.observe(this.scrollDiv,"scroll",this.scrollEventFunc, false);
     for (var t=0; t<2; t++)
       Event.observe(this.tabs[t],this.wheelEvent,this.wheelEventFunc, false);
     this.scrollPluggedIn=true;
  },

  unplugScroll: function() {
     if (!this.scrollPluggedIn) return;
     Rico.writeDebugMsg("unplugScroll");
     Event.stopObserving(this.scrollDiv,"scroll", this.scrollEventFunc , false);
     for (var t=0; t<2; t++)
       Event.stopObserving(this.tabs[t],this.wheelEvent,this.wheelEventFunc, false);
     this.scrollPluggedIn=false;
  },

  handleWheel: function(e) {
    var delta = 0;
    if (e.wheelDelta) {
      if (Rico.isOpera)
        delta = e.wheelDelta/120;
      else if (Rico.isSafari)
        delta = -e.wheelDelta/12;
      else
        delta = -e.wheelDelta/120;
    } else if (e.detail) {
      delta = e.detail/3; /* Mozilla/Gecko */
    }
    if (delta) this.moveRelative(delta);
    Event.stop(e);
    return false;
  },

  handleScroll: function(e) {
     if ( this.scrollTimeout )
       clearTimeout( this.scrollTimeout );
     this.setHorizontalScroll();
     var scrtop=this.scrollDiv.scrollTop;
     var vscrollDiff = this.lastScrollPos-scrtop;
     if (vscrollDiff == 0.00) return;
     var newrow=this.pixeltorow(scrtop);
     if (newrow == this.lastRowPos && !this.isPartialBlank && !this.isBlank) return;
     var stamp1 = new Date();
     //Rico.writeDebugMsg("handleScroll, newrow="+newrow+" scrtop="+scrtop);
     this.buffer.fetch(newrow);
     if (this.options.onscroll) this.options.onscroll(this, newrow);
     this.scrollTimeout = setTimeout(this.scrollIdle.bind(this), 1200 );
     this.lastScrollPos = this.scrollDiv.scrollTop;
     var stamp2 = new Date();
     //Rico.writeDebugMsg("handleScroll, time="+(stamp2.getTime()-stamp1.getTime()));
  },

  scrollIdle: function() {
     if ( this.options.onscrollidle )
        this.options.onscrollidle();
  },

  printAll: function(exportType) {
    this.exportStart();
    this.buffer.exportAllRows(this.exportBuffer.bind(this),this.exportFinish.bind(this,exportType));
  },

  // send all rows to print window
  exportBuffer: function(rows) {
    for(var r=0; r < rows.length; r++) {
      this.exportText+="<tr>";
      for (var c=0; c<this.columns.length; c++) {
        if (this.columns[c].visible)
          this.exportText+="<td>"+this.columns[c]._format(rows[r][c].content)+"</td>";
      }
      this.exportText+="</tr>";
    }
  }

};

Rico.TableColumn.prototype.format_text = function(v) {
  if (typeof v!='string')
    return '&nbsp;';
  else
    return v.stripTags();
}

Rico.TableColumn.prototype.format_showTags = function(v) {
  if (typeof v!='string')
    return '&nbsp;';
  else
    return v.replace(/&/g, '&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

Rico.TableColumn.prototype.format_number = function(v) {
  if (typeof v=='undefined' || v=='' || v==null)
    return '&nbsp;';
  else
    return v.formatNumber(this.format);
}

Rico.TableColumn.prototype.format_datetime = function(v) {
  if (typeof v=='undefined' || v=='' || v==null)
    return '&nbsp;';
  else {
    var d=new Date;
    d.setISO8601(v);
    return d.formatDate(this.format.dateFmt || 'translateDateTime');
  }
}

Rico.TableColumn.prototype.format_date = function(v) {
  if (typeof v=='undefined' || v=='' || v==null)
    return '&nbsp;';
  else {
    var d=new Date;
    d.setISO8601(v);
    return d.formatDate(this.format.dateFmt || 'translateDate');
  }
}

Rico.TableColumn.prototype.finishInit = function() {
  if (this.format.type=='control') {
    // copy all properties/methods that start with '_'
    for (var property in this.format.control)
      if (property.charAt(0)=='_')
        this[property] = this.format.control[property];
  } else if (this['format_'+this.format.type]) {
    this._format=this['format_'+this.format.type].bind(this);
  }
}

Rico.TableColumn.prototype.getValue = function(rowIndex) {
  return this.liveGrid.buffer.getWindowValue(rowIndex,this.index);
}

Rico.TableColumn.prototype.getFormattedValue = function(rowIndex) {
  return this._format(this.getValue(rowIndex));
}

Rico.TableColumn.prototype.getBufferCell = function(rowIndex) {
  return this.liveGrid.buffer.getWindowCell(rowIndex,this.index);
}

Rico.TableColumn.prototype.setValue = function(rowIndex,newval) {
  this.liveGrid.buffer.setWindowValue(rowIndex,this.index,newval);
}

Rico.TableColumn.prototype._format = function(v) {
  return v;
}

Rico.TableColumn.prototype._display = function(v,gridCell) {
  gridCell.innerHTML=this._format(v);
}

Rico.TableColumn.prototype.displayValue = function(rowIndex) {
  var bufCell=this.getBufferCell(rowIndex);
  if (!bufCell) {
    this.clearCell(rowIndex);
    return;
  }
  var gridCell=this.cell(rowIndex);
  this._display(bufCell.content,gridCell,rowIndex);
  var acceptAttr=this.liveGrid.buffer.options.acceptAttr;
  for (var k=0; k<acceptAttr.length; k++) {
    var bufAttr=bufCell['_'+acceptAttr[k]] || '';
    switch (acceptAttr[k]) {
      case 'style': gridCell.style.cssText=bufAttr; break;
      case 'class': gridCell.className=bufAttr; break;
      default:      gridCell['_'+acceptAttr[k]]=bufAttr; break;
    }
  }
}

Rico.TableColumn.checkbox = Class.create();

Rico.TableColumn.checkbox.prototype = {

  initialize: function(checkedValue, uncheckedValue, defaultValue, readOnly) {
    this._checkedValue=checkedValue;
    this._uncheckedValue=uncheckedValue;
    this._defaultValue=defaultValue || false;
    this._readOnly=readOnly || false;
    this._checkboxes=[];
  },

  _create: function(gridCell,windowRow) {
    this._checkboxes[windowRow]=RicoUtil.createFormField(gridCell,'input','checkbox',this.liveGrid.tableId+'_chkbox_'+this.index+'_'+windowRow);
    this._clear(gridCell,windowRow);
    if (this._readOnly)
      this._checkboxes[windowRow].disabled=true;
    else
      Event.observe(this._checkboxes[windowRow], "click", this._onclick.bindAsEventListener(this), false);
  },

  _onclick: function(e) {
    var elem=Event.element(e);
    var windowRow=parseInt(elem.id.split(/_/).pop());
    var newval=elem.checked ? this._checkedValue : this._uncheckedValue;
    this.setValue(windowRow,newval);
  },

  _clear: function(gridCell,windowRow) {
    this._checkboxes[windowRow].checked=this._defaultValue;
  },

  _display: function(v,gridCell,windowRow) {
    this._checkboxes[windowRow].checked=(v==this._checkedValue);
  }

}

Rico.TableColumn.link = Class.create();

Rico.TableColumn.link.prototype = {

  initialize: function(href,target) {
    this._href=href;
    this._target=target;
    this._anchors=[];
  },

  _create: function(gridCell,windowRow) {
    this._anchors[windowRow]=RicoUtil.createFormField(gridCell,'a',null,this.liveGrid.tableId+'_a_'+this.index+'_'+windowRow);
    if (this._target) this._anchors[windowRow].target=this._target;
    this._clear(gridCell,windowRow);
  },

  _clear: function(gridCell,windowRow) {
    this._anchors[windowRow].href='';
    this._anchors[windowRow].innerHTML='';
  },

  _display: function(v,gridCell,windowRow) {
    this._anchors[windowRow].innerHTML=v;
    var getWindowValue=this.liveGrid.buffer.getWindowValue.bind(this.liveGrid.buffer);
    this._anchors[windowRow].href=this._href.replace(/\{\d+\}/g, 
      function ($1) {
        var colIdx=parseInt($1.substr(1));
        return getWindowValue(windowRow,colIdx);
      }
    );
  }

}

Rico.TableColumn.lookup = Class.create();

Rico.TableColumn.lookup.prototype = {

  initialize: function(map, defaultCode, defaultDesc) {
    this._map=map;
    this._defaultCode=defaultCode || '';
    this._defaultDesc=defaultDesc || '&nbsp;';
    this._sortfunc=this._sortvalue.bind(this);
    this._codes=[];
    this._descriptions=[];
  },

  _create: function(gridCell,windowRow) {
    this._descriptions[windowRow]=RicoUtil.createFormField(gridCell,'span',null,this.liveGrid.tableId+'_desc_'+this.index+'_'+windowRow);
    this._codes[windowRow]=RicoUtil.createFormField(gridCell,'input','hidden',this.liveGrid.tableId+'_code_'+this.index+'_'+windowRow);
    this._clear(gridCell,windowRow);
  },

  _clear: function(gridCell,windowRow) {
    this._codes[windowRow].value=this._defaultCode;
    this._descriptions[windowRow].innerHTML=this._defaultDesc;
  },

  _sortvalue: function(v) {
    return this._getdesc(v).replace(/&amp;/g, '&').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&nbsp;/g,' ');
  },

  _getdesc: function(v) {
    var desc=this._map[v];
    return (typeof desc=='string') ? desc : this._defaultDesc;
  },

  _display: function(v,gridCell,windowRow) {
    this._codes[windowRow].value=v;
    this._descriptions[windowRow].innerHTML=this._getdesc(v);
  }

}

Rico.addPreloadMsg('exec: ricoLiveGrid.js');
