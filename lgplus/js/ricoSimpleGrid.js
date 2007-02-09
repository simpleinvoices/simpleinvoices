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


if(typeof Rico=='undefined') throw("SimpleGrid requires the Rico JavaScript framework");
if(typeof RicoUtil=='undefined') throw("SimpleGrid requires the RicoUtil Library");
if(typeof RicoTranslate=='undefined') throw("SimpleGrid requires the RicoTranslate Library");

Rico.SimpleGrid = Class.create();

Rico.SimpleGrid.prototype = {

  initialize: function( tableId, menu, options ) {
    this.options = {
      resizeBackground : 'resize.gif',
      saveColumnInfo   : true,      // save column hide/show status & width in a cookie
      allowColResize   : true,      // allow user to resize columns
      windowResize     : true,      // Resize grid on window.resize event? Set to false when embedded in an accordian.
      click            : null,
      dblclick         : null,
      contextmenu      : null,
      menuEvent        : 'dblclick',  // event that triggers menus - click, dblclick, contextmenu, or none (no menus)
      defaultWidth     : 100,   // in the absence of any other width info, columns will be this many pixels wide
      scrollBarWidth   : 19,    // this is the value used in positioning calculations, it does not actually change the width of the scrollbar
      minScrollWidth   : 100,   // min scroll area width when width of frozen columns exceeds window width
      columnSpecs      : []
    }
    this.colWidths = new Array();
    this.hdrCells=new Array();
    this.headerColCnt=0;
    this.headerRowIdx=0;
    this.tabs=new Array(2);
    this.thead=new Array(2);
    this.tbody=new Array(2);
    if (!tableId) return;
    Rico.setDebugArea(tableId+"_debugmsgs");    // if used, this should be a textarea
    this.options.cookiePrefix='simpleGrid.'+tableId;
    Object.extend(this.options, options || {});
    this.tableId = tableId;
    this.createDivs();
    this.hdrTabs=new Array(2);
    for (var i=0; i<2; i++) {
      this.tabs[i]=$(tableId+'_tab'+i);
      this.hdrTabs[i]=$(tableId+'_tab'+i+'h');
      if (i==0) this.tabs[i].style.position='absolute';
      if (i==0) this.tabs[i].style.left='0px';
      this.hdrTabs[i].style.position='absolute';
      this.hdrTabs[i].style.top='0px';
      this.hdrTabs[i].style.zIndex=1;
      this.thead[i]=this.hdrTabs[i];
      this.tbody[i]=this.tabs[i];
      this.getColumnInfo(this.hdrTabs[i].rows);
      if (i==0) this.options.frozenColumns=this.headerColCnt;
    }
    if (this.headerColCnt==0) {
      alert('ERROR: no columns found in "'+this.tableId+'"');
      return;
    }
    this.hdrHt=Math.max(RicoUtil.nan2zero(this.hdrTabs[0].offsetHeight),this.hdrTabs[1].offsetHeight);
    for (var i=0; i<2; i++)
      if (i==0) this.tabs[i].style.top=this.hdrHt+'px';
    this.createColumnArray();
    this.pageSize=this.columns[0].dataColDiv.childNodes.length;
    this.sizeDivs();
    if (menu) this.attachMenuEvents(menu);
    this.scrollEventFunc=this.handleScroll.bindAsEventListener(this);
    this.pluginScroll();
    if (this.options.windowResize)
      Event.observe(window,"resize", this.sizeDivs.bindAsEventListener(this), false);
  },

  attachMenuEvents: function(menu) {
    this.menu=menu;
    if (menu.registerGrid) menu.registerGrid(this);
    this.hideScroll=navigator.userAgent.match(/Macintosh\b.*\b(Firefox|Camino)\b/i) || Rico.isOpera;
    this.options[this.options.menuEvent]=this.handleMenuClick.bindAsEventListener(this);
    switch (this.options.highlightElem) {
      case 'cursorRow':
        this.attachMenu(this.highlightDiv);
        break;
      case 'cursorCell':
        for (var i=0; i<2; i++)
          this.attachMenu(this.highlightDiv[i]);
        break;
    }
    for (var i=0; i<2; i++)
      this.attachMenu(this.tbody[i]);
  },

  attachMenu: function(elem) {
    if (this.options.click)
      Event.observe(elem, 'click', this.options.click, false);
    if (this.options.dblclick) {
      if (Rico.isSafari || Rico.isOpera)
        Event.observe(elem, 'click', this.handleDblClick.bindAsEventListener(this), false);
      else
        Event.observe(elem, 'dblclick', this.options.dblclick, false);
    }
    if (this.options.contextmenu) {
      if (Rico.isOpera)
        Event.observe(elem, 'click', this.handleContextMenu.bindAsEventListener(this), false);
      else
        Event.observe(elem, 'contextmenu', this.options.contextmenu, false);
    }
  },

  // implement double-click for browsers that don't support a double-click event (e.g. Safari)
  handleDblClick: function(e) {
    var elem=Event.element(e);
    if (this.dblClickElem == elem) {
      this.options.dblclick(e);
    } else {
      this.dblClickElem = elem;
      this.safariTimer=setTimeout(this.clearDblClick.bind(this),300);
    }
  },

  clearDblClick: function() {
    this.dblClickElem=null;
  },

  // implement right-click for browsers that don't support contextmenu event (e.g. Opera)
  // use control-click instead
  handleContextMenu: function(e) {
    if( typeof( e.which ) == 'number' )
      var b = e.which; //Netscape compatible
    else if( typeof( e.button ) == 'number' )
      var b = e.button; //DOM
    else
      return;
    if (b==1 && e.ctrlKey)
      this.options.contextmenu(e);
  },

  handleMenuClick: function(e) {
    this.cancelMenu();
    this.menuCell=RicoUtil.getParentByTagName(Event.element(e),'div');
    this.highlightEnabled=false;
    if (this.hideScroll) this.scrollDiv.style.overflow="hidden";
    if (this.menu.buildGridMenu) this.menu.buildGridMenu(this.menuCell);
    this.menu.showmenu(e,this.closeMenu.bind(this));
  },

  closeMenu: function() {
    if (this.hideScroll) this.scrollDiv.style.overflow="";
    this.highlightEnabled=true;
  },

  cancelMenu: function() {
    if (this.menu && this.menu.isVisible()) this.menu.cancelmenu();
  },

  // gather info from original headings
  getColumnInfo: function(hdrSrc) {
    Rico.writeDebugMsg("getColumnInfo start");
    //alert(hdrSrc.tagName+' '+hdrSrc.id+' len='+hdrSrc.length);
    if (hdrSrc.length == 0) return;
    this.headerRowCnt=hdrSrc.length;
    for (r=0; r<this.headerRowCnt; r++) {
      var headerRow = hdrSrc[r];
      var headerCells=headerRow.cells;
      if (r >= this.hdrCells.length) this.hdrCells[r]=new Array();
      for (c=0; c<headerCells.length; c++) {
        var obj={};
        obj.cell=headerCells[c];
        obj.colSpan=headerCells[c].colSpan || 1;  // Safari & Konqueror return default colspan of 0
        obj.initWidth=headerCells[c].offsetWidth
        this.hdrCells[r].push(obj);
      }
      if (headerRow.id.slice(-5)=='_main' || (!this.headerColCnt && r==this.headerRowCnt-1)) {
        this.headerColCnt=this.hdrCells[r].length;
        this.headerRowIdx=r;
      }
    }
    Rico.writeDebugMsg("getColumnInfo end");
  },

  // create column array
  createColumnArray: function() {
    this.columns = new Array();
    for (var c=0 ; c < this.headerColCnt; c++) {
      var tabidx=c<this.options.frozenColumns ? 0 : 1;
      this.columns.push(new Rico.TableColumn(this, c, this.hdrCells[this.headerRowIdx][c], tabidx));
    }
  },

  // create div structure
  createDivs: function() {
    Rico.writeDebugMsg("createDivs start");
    this.outerDiv   = this.createDiv("outer");
    this.scrollDiv  = this.createDiv("scroll",this.outerDiv);
    this.frozenTabs = this.createDiv("frozenTabs",this.outerDiv);
    this.innerDiv   = this.createDiv("inner",this.outerDiv);
    this.resizeDiv  = this.createDiv("resize",this.outerDiv);
    this.resizeDiv.style.display="none";
    this.exportDiv  = this.createDiv("export",this.outerDiv);
    this.exportDiv.style.display="none";
    Rico.writeDebugMsg("createDivs end");
  },

  createDiv: function(elemName,elemParent) {
    var id=this.tableId+"_"+elemName+"Div";
    newdiv=$(id);
    if (!newdiv) {
      var newdiv = document.createElement("div");
      newdiv.className = "ricoLG_"+elemName+"Div";
      newdiv.id = id;
      if (elemParent) elemParent.appendChild(newdiv);
    }
    return newdiv;
  },

  sizeDivs1: function() {
    this.setOtherHdrCellWidths();
    this.tabs[0].style.display=this.options.frozenColumns ? '' : 'none';
    this.hdrHt=Math.max(RicoUtil.nan2zero(this.thead[0].offsetHeight),this.thead[1].offsetHeight);
    this.dataHt=Math.max(RicoUtil.nan2zero(this.tbody[0].offsetHeight),this.tbody[1].offsetHeight);
    this.frzWi=this.borderWidth(this.tabs[0]);
    var borderWi=this.borderWidth(this.columns[0].dataCell);
    //alert(this.tableId+' frzWi='+this.frzWi+' borderWi='+borderWi);
    for (var i=0; i<this.options.frozenColumns; i++)
      if (this.columns[i].visible) this.frzWi+=parseInt(this.columns[i].colWidth)+borderWi;
    this.scrTabWi=this.borderWidth(this.tabs[1]);
    for (var i=this.options.frozenColumns; i<this.columns.length; i++)
      if (this.columns[i].visible) this.scrTabWi+=parseInt(this.columns[i].colWidth)+borderWi;
    this.scrWi=this.scrTabWi+this.options.scrollBarWidth;
    var wiLimit=RicoUtil.windowWidth()-this.options.scrollBarWidth-8;
    if (this.outerDiv.parentNode.clientWidth > 0)
      wiLimit=Math.min(this.outerDiv.parentNode.clientWidth, wiLimit);
    var overage=this.frzWi+this.scrWi-wiLimit;
    Rico.writeDebugMsg('sizeDivs1 '+this.tableId+': scrWi='+this.scrWi+' wiLimit='+wiLimit+' overage='+overage+' clientWidth='+this.outerDiv.parentNode.clientWidth);
    if (overage > 0 && this.options.frozenColumns < this.columns.length)
      this.scrWi=Math.max(this.scrWi-overage, this.options.minScrollWidth);
    this.scrollDiv.style.width=this.scrWi+'px';
    this.scrollDiv.style.top=this.hdrHt+'px';
    this.scrollDiv.style.left=this.frozenTabs.style.width=this.innerDiv.style.left=this.frzWi+'px';
    this.outerDiv.style.width=(this.frzWi+this.scrWi)+'px';
  },

  borderWidth: function(elem) {
    return RicoUtil.nan2zero(Element.getStyle(elem,'border-left-width')) + RicoUtil.nan2zero(Element.getStyle(elem,'border-right-width'));
  },

  sizeDivs: function() {
    if (this.outerDiv.offsetParent.style.display=='none') return;
    this.sizeDivs1();
    var maxHt=Math.max(this.options.maxHt || this.availHt(), 50);
    var totHt=Math.min(this.hdrHt+this.dataHt, maxHt);
    Rico.writeDebugMsg('sizeDivs '+this.tableId+': hdrHt='+this.hdrHt+' dataHt='+this.dataHt);
    this.dataHt=totHt-this.hdrHt;
    if (this.scrWi>0) this.dataHt+=this.options.scrollBarWidth;
    this.scrollDiv.style.height=this.dataHt+'px';
    var divAdjust=2;
    this.innerDiv.style.width=(this.scrWi-this.options.scrollBarWidth+divAdjust)+'px';
    this.innerDiv.style.height=this.hdrHt+'px';
    totHt+=divAdjust;
    this.resizeDiv.style.height=this.frozenTabs.style.height=totHt+'px';
    this.outerDiv.style.height=(totHt+this.options.scrollBarWidth)+'px';
    this.setHorizontalScroll();
  },

  setOtherHdrCellWidths: function() {
    for (var r=0; r<this.hdrCells.length; r++) {
      if (r==this.headerRowIdx) continue;
      var c=i=0;
      while (i<this.headerColCnt && c<this.hdrCells[r].length) {
        var hdrcell=this.hdrCells[r][c];
        var cell=hdrcell.cell;
        var origSpan=newSpan=hdrcell.colSpan;
        for (var w=j=0; j<origSpan; j++, i++) {
          if (this.columns[i].hdrCell.style.display=='none')
            newSpan--;
          else if (this.columns[i].hdrColDiv.style.display!='none')
            w+=parseInt(this.columns[i].colWidth);
        }
        if (!hdrcell.hdrColDiv || !hdrcell.hdrCellDiv) {
          var divs=cell.getElementsByTagName('div');
          hdrcell.hdrColDiv=(divs.length<1) ? RicoUtil.wrapChildren(cell,'ricoLG_col') : divs[0];
          hdrcell.hdrCellDiv=(divs.length<2) ? RicoUtil.wrapChildren(hdrcell.hdrColDiv,'ricoLG_cell') : divs[1];
        }
        if (newSpan==0) {
          cell.style.display='none';
        } else if (w==0) {
          hdrcell.hdrColDiv.style.display='none';
          cell.colSpan=newSpan;
        } else {
          cell.style.display='';
          hdrcell.hdrColDiv.style.display='';
          cell.colSpan=newSpan;
          hdrcell.hdrColDiv.style.width=w+'px';
        }
        c++;
      }
    }
  },

  availHt: function() {
    var divPos=Position.page(this.outerDiv);
    return RicoUtil.windowHeight()-divPos[1]-2*this.options.scrollBarWidth-15;  // allow for scrollbar and some margin
  },

  handleScroll: function(e) {
    var newTop=(this.hdrHt-this.scrollDiv.scrollTop)+'px';
    this.tabs[0].style.top=newTop;
    this.setHorizontalScroll();
  },

  setHorizontalScroll: function() {
    var newLeft=(-this.scrollDiv.scrollLeft)+'px';
    this.hdrTabs[1].style.left=newLeft;
  },

  pluginScroll: function() {
     if (this.scrollPluggedIn) return;
     Event.observe(this.scrollDiv,"scroll",this.scrollEventFunc, false);
     this.scrollPluggedIn=true;
  },

  unplugScroll: function() {
     Event.stopObserving(this.scrollDiv,"scroll", this.scrollEventFunc , false);
     this.scrollPluggedIn=false;
  },

  printVisible: function(exportType) {
    this.exportStart();
    var limit=this.pageSize;
    if (this.buffer && this.buffer.totalRows < limit) limit=this.buffer.totalRows;
    for(var r=0; r < limit; r++) {
      this.exportText+="<tr>";
      for (var c=0; c<this.columns.length; c++) {
        if (this.columns[c].visible)
          this.exportText+="<td style='"+this.exportStyle(this.columns[c].cell(r))+"'>"+this.columns[c].getFormattedValue(r)+"</td>";
      }
      this.exportText+="</tr>";
    }
    this.exportFinish(exportType);
  },

  exportStart: function() {
    this.exportText="<table border='1' cellspacing='0'><thead style='display: table-header-group;'><tr>";
    for (var c=0; c<this.columns.length; c++)
      if (this.columns[c].visible)
        this.exportText+="<td style='"+this.exportStyle(this.columns[c].hdrCellDiv)+"'>"+this.columns[c].displayName+"</td>";
    this.exportText+="</tr></thead><tbody>";
  },

  exportFinish: function(exportType) {
    if (this.hideMsg) this.hideMsg();
    this.exportText+="</tbody></table>";
    this.exportDiv.innerHTML=this.exportText;
    this.exportText=undefined;
    if (this.cancelMenu) this.cancelMenu();
    window.open(Rico.htmDir+'export-'+(exportType || 'plain')+'.html?'+this.exportDiv.id,'',this.options.exportWindow);
  },
  
  exportStyle: function(elem) {
    var styleList=['background-color','color','text-align','font-weight']
    for (var i=0,s=''; i < styleList.length; i++) {
      var curstyle=Element.getStyle(elem,styleList[i]);
      if (curstyle) s+=styleList[i]+':'+curstyle+';';
    }
    return s;
  }

};


Rico.TableColumn = Class.create();

Rico.TableColumn.UNFILTERED   = 0;
Rico.TableColumn.SYSTEMFILTER = 1;  /* system-generated filter, not shown to user */
Rico.TableColumn.USERFILTER   = 2;

Rico.TableColumn.UNSORTED   = 0;
Rico.TableColumn.SORT_ASC   = "ASC";
Rico.TableColumn.SORT_DESC  = "DESC";
Rico.TableColumn.MINWIDTH   = 10; // min column width when user is resizing

Rico.TableColumn.DOLLAR  = {type:'number', prefix:'$', decPlaces:2, ClassName:'alignright'};
Rico.TableColumn.EURO    = {type:'number', prefix:'&euro;', decPlaces:2, ClassName:'alignright'};
Rico.TableColumn.PERCENT = {type:'number', suffix:'%', decPlaces:2, multiplier:100, ClassName:'alignright'};
Rico.TableColumn.QTY     = {type:'number', decPlaces:0, ClassName:'alignright'};
Rico.TableColumn.DEFAULT = {type:"raw"};

Rico.TableColumn.prototype = {
  initialize: function(liveGrid,colIdx,hdrInfo,tabIdx) {
    Rico.writeDebugMsg("TableColumn.init index="+colIdx+" wi="+hdrInfo.initWidth+" tabIdx="+tabIdx);
    this.liveGrid  = liveGrid;
    this.index     = colIdx;
    this.hideWidth = Rico.isKonqueror || Rico.isSafari || liveGrid.headerRowCnt>1 ? 5 : 2;  // column width used for "hidden" columns. Anything less than 5 causes problems with Konqueror. Best to keep this greater than padding used inside cell.
    this.options   = liveGrid.options;
    this.tabIdx    = tabIdx;
    this.hdrCell   = hdrInfo.cell;
    this.body = document.getElementsByTagName("body")[0];  // work around FireFox bug (document.body doesn't exist after XSLT)
    this.displayName  = this.getDisplayName(this.hdrCell);
    var divs=this.hdrCell.getElementsByTagName('div');
    this.hdrColDiv=(divs.length<1) ? RicoUtil.wrapChildren(this.hdrCell,'ricoLG_col') : divs[0];
    this.hdrCellDiv=(divs.length<2) ? RicoUtil.wrapChildren(this.hdrColDiv,'ricoLG_cell') : divs[1];
    var sectionIndex= tabIdx==0 ? colIdx : colIdx-liveGrid.options.frozenColumns;
    this.dataCell = liveGrid.tbody[tabIdx].rows[0].cells[sectionIndex];
    var divs=this.dataCell.getElementsByTagName('div');
    this.dataColDiv=(divs.length<1) ? RicoUtil.wrapChildren(this.dataCell,'ricoLG_col') : divs[0];

    this.mouseDownHandler= this.handleMouseDown.bindAsEventListener(this);
    this.mouseMoveHandler= this.handleMouseMove.bindAsEventListener(this);
    this.mouseUpHandler  = this.handleMouseUp.bindAsEventListener(this);
    this.mouseOutHandler = this.handleMouseOut.bindAsEventListener(this);

    this.fieldName = 'col'+this.index;
    var spec = liveGrid.options.columnSpecs[colIdx];
    this.format=Object.extend( {}, Rico.TableColumn.DEFAULT);
    switch (typeof spec) {
      case 'object':
        if (typeof spec.format=='string') Object.extend(this.format, Rico.TableColumn[spec.format.toUpperCase()]);
        Object.extend(this.format, spec);
        break;
      case 'string':
        if (spec.slice(0,4)=='spec') spec=spec.slice(4).toUpperCase();  // for backwards compatibility
        this.format=typeof Rico.TableColumn[spec]=='object' ? Rico.TableColumn[spec] : Rico.TableColumn.DEFAULT;
        break;
    }
    this.dataColDiv.className += (this.format.ClassName) ? ' '+this.format.ClassName : ' '+liveGrid.tableId+'_col'+colIdx;
    this.visible=true;
    if (typeof this.format.visible=='boolean') this.visible=this.format.visible;
    if (typeof this.format.type!='string') this.format.type='raw';
    Rico.writeDebugMsg("TableColumn.init index="+colIdx+" fieldName="+this.fieldName+' type='+this.format.type);
    this.sortable     = typeof this.format.canSort=='boolean' ? this.format.canSort : liveGrid.options.canSortDefault;
    this.currentSort  = Rico.TableColumn.UNSORTED;
    this.filterable   = typeof this.format.canFilter=='boolean' ? this.format.canFilter : liveGrid.options.canFilterDefault;
    this.filterType   = Rico.TableColumn.UNFILTERED;
    this.hideable     = typeof this.format.canHide=='boolean' ? this.format.canHide : liveGrid.options.canHideDefault;
    if (typeof this.isNullable!='boolean') this.isNullable = /number|date/.test(this.format.type);
    this.isText       = /raw|text/.test(this.format.type);
    Rico.writeDebugMsg(" sortable="+this.sortable+" filterable="+this.filterable+" hideable="+this.hideable+" isNullable="+this.isNullable+' isText='+this.isText);
    this.fixHeaders( liveGrid.tableId, this.options.hdrIconsFirst );

    var wi=(typeof(this.format.width)=='number') ? this.format.width : hdrInfo.initWidth;
    wi=(typeof(wi)=='number') ? Math.max(wi,Rico.TableColumn.MINWIDTH) : liveGrid.options.defaultWidth;
    if (liveGrid.options.saveColumnInfo) {
      var c=this.getCookie(this.options.cookiePrefix+"."+this.fieldName);
      Rico.writeDebugMsg("TableColumn.init index="+colIdx+" cookie="+c);
      if (c=='hidden')
        this.visible=false;
      else if (c!=null && !isNaN(parseInt(c))) {
        this.visible=true;
        wi=c;
      }
    }
    this.setColWidth(wi);
    if (!this.visible) this.setDisplayNone();
    if (this.finishInit) this.finishInit();
  },

  fixHeaders: function( prefix, iconsfirst ) {
    var resizePath=Rico.imgDir+this.options.resizeBackground;
    if (Rico.isIE) resizePath=location.protocol+resizePath;
    if (this.sortable==true && this.options.headingSort==true) {
      var a=RicoUtil.wrapChildren(this.hdrCellDiv,'ricoSort',undefined,'a')
      a.href = "#";
      a.onclick = this.toggleSort.bindAsEventListener(this);
    }
    this.imgFilter = document.createElement('img');
    this.imgFilter.style.display='none';
    this.imgFilter.src=Rico.imgDir+this.options.filterImg;
    this.imgSort = document.createElement('img');
    this.imgSort.style.display='none';
    if (iconsfirst) {
      this.hdrCellDiv.insertBefore(this.imgSort,this.hdrCellDiv.firstChild);
      this.hdrCellDiv.insertBefore(this.imgFilter,this.hdrCellDiv.firstChild);
      this.imgSort.style.paddingRight='3px';
    } else {
      this.imgFilter.style.paddingLeft='3px';
      this.hdrCellDiv.appendChild(this.imgFilter);
      this.hdrCellDiv.appendChild(this.imgSort);
    }
    if (this.options.allowColResize) {
      this.hdrCell.style.width='';
      var resizer=this.hdrCellDiv.appendChild(document.createElement('div'));
      resizer.className='ricoLG_Resize';
      if (this.options.resizeBackground)
        resizer.style.backgroundImage='url('+resizePath+')';
      Event.observe(resizer,"mousedown", this.mouseDownHandler, false);
    }
  },

  // get the display name of a column
  getDisplayName: function(el) {
    var anchors=el.getElementsByTagName("A");
    //Check the existance of A tags
    if (anchors.length > 0)
      return anchors[0].innerHTML;
    else
      return el.innerHTML.stripTags();
  },
  
  _clear: function(gridCell) {
    gridCell.innerHTML='&nbsp;';
  },

  clearCell: function(rowIndex) {
    var gridCell=this.cell(rowIndex);
    this._clear(gridCell,rowIndex);
    if (!this.liveGrid.buffer) return;
    var acceptAttr=this.liveGrid.buffer.options.acceptAttr;
    for (var k=0; k<acceptAttr.length; k++) {
      switch (acceptAttr[k]) {
        case 'style': gridCell.style.cssText=''; break;
        case 'class': gridCell.className=''; break;
        default:      gridCell['_'+acceptAttr[k]]=''; break;
      }
    }
  },

  dataTable: function() {
    return this.liveGrid.tabs[this.tabIdx];
  },

  clearColumn: function() {
    var childCnt=this.dataColDiv.childNodes.length;
    for (var r=0; r<childCnt; r++)
      this.clearCell(r);
  },

  cell: function(r) {
    return this.dataColDiv.childNodes[r];
  },
  
  getFormattedValue: function(r) {
    return RicoUtil.getInnerText(this.cell(r));
  },

  setColWidth: function(wi) {
    if (typeof wi=='number') {
      wi=parseInt(wi);
      if (wi < Rico.TableColumn.MINWIDTH) return;
      wi=wi+'px';
    }
    Rico.writeDebugMsg('setColWidth '+this.index+': '+wi);
    this.colWidth=wi;
    this.hdrColDiv.style.width=wi;
    this.dataColDiv.style.width=wi;
  },

  pluginMouseEvents: function() {
    if (this.mousePluggedIn==true) return;
    Event.observe(this.body,"mousemove", this.mouseMoveHandler, false);
    Event.observe(this.body,"mouseup",   this.mouseUpHandler  , false);
    Event.observe(this.body,"mouseout",  this.mouseOutHandler , false);
    this.mousePluggedIn=true;
  },

  unplugMouseEvents: function() {
    Event.stopObserving(this.body,"mousemove", this.mouseMoveHandler, false);
    Event.stopObserving(this.body,"mouseup",   this.mouseUpHandler  , false);
    Event.stopObserving(this.body,"mouseout",  this.mouseOutHandler , false);
    this.mousePluggedIn=false;
  },

  handleMouseDown: function(e) {
    this.resizeStart=e.clientX;
    this.origWidth=parseInt(this.colWidth);
    var p=Position.positionedOffset(this.hdrCell);
    this.rtEdge=p[0]+this.hdrCell.offsetWidth;
    if (this.tabIdx>0) this.rtEdge+=RicoUtil.nan2zero(this.liveGrid.tabs[0].offsetWidth)-this.liveGrid.scrollDiv.scrollLeft;
    this.liveGrid.resizeDiv.style.left=this.rtEdge+"px";
    this.liveGrid.resizeDiv.style.display="";
    this.liveGrid.outerDiv.style.cursor='e-resize';
    this.tmpHighlight=this.liveGrid.highlightEnabled;
    this.liveGrid.highlightEnabled=false;
    this.pluginMouseEvents();
    Event.stop(e);
  },

  handleMouseMove: function(e) {
    var delta=e.clientX-this.resizeStart;
    var newWidth=this.origWidth+delta;
    if (newWidth < Rico.TableColumn.MINWIDTH) return;
    this.liveGrid.resizeDiv.style.left=(this.rtEdge+delta)+"px";
    this.colWidth=newWidth;
    Event.stop(e);
  },

  handleMouseUp: function(e) {
    this.unplugMouseEvents();
    Rico.writeDebugMsg('handleMouseUp '+this.liveGrid.tableId);
    this.liveGrid.outerDiv.style.cursor='';
    this.liveGrid.resizeDiv.style.display="none";
    this.setColWidth(this.colWidth);
    this.setWidthCookie(this.colWidth);
    this.liveGrid.highlightEnabled=this.tmpHighlight;
    this.liveGrid.sizeDivs();
    Event.stop(e);
  },

  handleMouseOut: function(e) {
    var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
    while (reltg != null && reltg.nodeName.toLowerCase() != 'body')
      reltg=reltg.parentNode;
    if (reltg!=null && reltg.nodeName.toLowerCase() == 'body') return true;
    this.handleMouseUp(e);
    return true;
  },

  setDisplayNone: function() {
    this.hdrCell.style.display='none';
    this.hdrColDiv.style.display='none';
    this.dataCell.style.display='none';
    this.dataColDiv.style.display='none';
  },

  // recalcTableWidth defaults to true
  hideColumn: function() {
    Rico.writeDebugMsg('hideColumn '+this.liveGrid.tableId);
    this.setDisplayNone();
    this.liveGrid.cancelMenu();
    this.visible=false;
    this.setWidthCookie("hidden");
    this.liveGrid.sizeDivs();
  },

  showColumn: function() {
    Rico.writeDebugMsg('showColumn '+this.liveGrid.tableId);
    this.hdrCell.style.display='';
    this.hdrColDiv.style.display='';
    this.dataCell.style.display='';
    this.dataColDiv.style.display='';
    this.liveGrid.cancelMenu();
    this.visible=true;
    this.setWidthCookie(this.colWidth);
    this.liveGrid.sizeDivs();
  },

  setImage: function() {
    if ( this.currentSort == Rico.TableColumn.SORT_ASC ) {
       this.imgSort.style.display='';
       this.imgSort.src=Rico.imgDir+this.options.sortAscendImg;
    } else if ( this.currentSort == Rico.TableColumn.SORT_DESC ) {
       this.imgSort.style.display='';
       this.imgSort.src=Rico.imgDir+this.options.sortDescendImg;
    } else {
       this.imgSort.style.display='none';
    }
    if (this.filterType == Rico.TableColumn.USERFILTER) {
       this.imgFilter.style.display='';
       this.imgFilter.title=this.getFilterText();
    } else {
       this.imgFilter.style.display='none';
    }
  },

  canHideShow: function() {
    return this.hideable;
  },

  canFilter: function() {
    return this.filterable;
  },

  getFilterText: function() {
    switch (this.filterOp) {
      case 'EQ':   return this.filterValues[0];
      case 'NE':   return 'not: '+this.filterValues.join(', ');
      case 'LE':   return '<= '+this.filterValues[0];
      case 'GE':   return '>= '+this.filterValues[0];
      case 'LIKE': return 'like: '+this.filterValues[0];
      case 'NULL': return '<empty>';
      case 'NOTNULL': return '<not empty>';
    }
    return '?';
  },

  getFilterQueryParm: function() {
    if (this.filterType == Rico.TableColumn.UNFILTERED) return '';
    var retval='&f['+this.index+'][op]='+this.filterOp;
    retval+='&f['+this.index+'][len]='+this.filterValues.length
    for (var i=0; i<this.filterValues.length; i++)
      retval+='&f['+this.index+']['+i+']='+escape(this.filterValues[i]);
    return retval;
  },

  setUnfiltered: function() {
    this.filterType = Rico.TableColumn.UNFILTERED;
    if (this.removeFilterFunc)
      this.removeFilterFunc();
    if (this.options.filterHandler)
      this.options.filterHandler();
  },

  setFilterEQ: function() {
    if (this.userFilter=='' && this.isNullable)
      this.setUserFilter('NULL');
    else
      this.setUserFilter('EQ');
  },
  setFilterNE: function() {
    if (this.userFilter=='' && this.isNullable)
      this.setUserFilter('NOTNULL');
    else
      this.setUserFilter('NE');
  },
  addFilterNE: function() {
    this.filterValues.push(this.userFilter);
    if (this.options.filterHandler)
      this.options.filterHandler();
  },
  setFilterGE: function() { this.setUserFilter('GE'); },
  setFilterLE: function() { this.setUserFilter('LE'); },
  setFilterKW: function() {
    var keyword=prompt(RicoTranslate.getPhrase("Enter keyword to search for")+RicoTranslate.getPhrase(" (use * as a wildcard):"),'');
    if (keyword!='' && keyword!=null) {
      if (keyword.indexOf('*')==-1) keyword='*'+keyword+'*';
      this.setFilter('LIKE',keyword,Rico.TableColumn.USERFILTER);
    } else {
      this.liveGrid.cancelMenu();
    }
  },

  setUserFilter: function(relop) {
    this.setFilter(relop,this.userFilter,Rico.TableColumn.USERFILTER);
  },

  setSystemFilter: function(relop,filter) {
    this.setFilter(relop,filter,Rico.TableColumn.SYSTEMFILTER);
  },

  setFilter: function(relop,filter,type,removeFilterFunc) {
    this.filterValues = [filter];
    this.filterType = type;
    this.filterOp = relop;
    this.removeFilterFunc=removeFilterFunc;
    if (this.options.filterHandler)
      this.options.filterHandler();
  },

  sortAsc: function() {
    this.setColumnSort(Rico.TableColumn.SORT_ASC);
  },

  sortDesc: function() {
    this.setColumnSort(Rico.TableColumn.SORT_DESC);
  },

  setColumnSort: function(direction) {
    this.liveGrid.clearSort();
    this.setSorted(direction);
    if (this.options.sortHandler)
      this.options.sortHandler();
  },

  isSortable: function() {
    return this.sortable;
  },

  isSorted: function() {
    return this.currentSort != Rico.TableColumn.UNSORTED;
  },

  getSortDirection: function() {
    return this.currentSort;
  },

  toggleSort: function() {
    if (this.liveGrid.buffer && this.liveGrid.buffer.totalRows==0) return;
    if ( this.currentSort == Rico.TableColumn.UNSORTED || this.currentSort == Rico.TableColumn.SORT_DESC )
      this.sortAsc();
    else if ( this.currentSort == Rico.TableColumn.SORT_ASC )
      this.sortDesc();
  },

  setUnsorted: function() {
     this.setSorted(Rico.TableColumn.UNSORTED);
  },

  setSorted: function(direction) {
    // direction must be one of Rico.TableColumn.UNSORTED, .SORT_ASC, or .SORT_DESC...
    this.currentSort = direction;
  },

  getCookieVal: function(offset) {
    var endstr = document.cookie.indexOf (';', offset);
    if (endstr == -1)
      endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
  },

  // Gets the value of the specified cookie.
  getCookie: function(name) {
    var arg = name + '=';
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
      var j = i + alen;
      if (document.cookie.substring(i, j) == arg)
        return this.getCookieVal (j);
      i = document.cookie.indexOf(' ', i) + 1;
      if (i == 0) break;
    }
    return null;
  },

  // Write width information to cookies
  setWidthCookie: function(saveValue) {
    if (this.options.saveColumnInfo==false) return;
    if (arguments.length==0) saveValue=this.colWidth;
    document.cookie= this.options.cookiePrefix+"."+this.fieldName+"="+saveValue;
  }

};

Rico.addPreloadMsg('exec: ricoSimpleGrid.js');
