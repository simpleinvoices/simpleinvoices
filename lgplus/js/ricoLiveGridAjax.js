if(typeof Rico=='undefined') throw("LiveGridAjax requires the Rico JavaScript framework");
if(typeof RicoUtil=='undefined') throw("LiveGridAjax requires the RicoUtil object");
if(typeof Rico.Buffer=='undefined') throw("LiveGridAjax requires the Rico.Buffer object");


// Data source is a static XML file located on the server

Rico.Buffer.AjaxXML = Class.create();

Rico.Buffer.AjaxXML.prototype = {

  initialize: function(url,options,ajaxOptions) {
    Object.extend(this, new Rico.Buffer.Base());
    Object.extend(this, new Rico.Buffer.AjaxXMLMethods());
    this.dataSource=url;
    this.options.bufferTimeout = 20000;  // time to wait for ajax response (milliseconds)
    Object.extend(this.options, options || {});
    this.ajaxOptions = { parameters: null, method : 'get' };
    Object.extend(this.ajaxOptions, ajaxOptions || {});
    this.requestCount=0;
    this.processingRequest=false;
    this.pendingRequest=-1;
  }
}

Rico.Buffer.AjaxXMLMethods = Class.create();

Rico.Buffer.AjaxXMLMethods.prototype = {

  initialize: function() {
  },
  
  fetch: function(offset) {
    if ( this.isInRange(offset) ) {
      Rico.writeDebugMsg("AjaxXML fetch: in buffer");
      this.liveGrid.refreshContents(offset);
      return;
    }
    this.processingRequest=true
    Rico.writeDebugMsg("AjaxXML fetch, offset="+offset);
    this.liveGrid.showMsg("Waiting for data...");
    this.timeoutHandler = setTimeout( this.handleTimedOut.bind(this), this.options.bufferTimeout);
    this.sendAjaxRequest(offset,0,this.ajaxUpdate.bind(this,offset));
  },

  handleTimedOut: function() {
    //server did not respond in __ seconds... assume that there could have been
    //an error, and allow requests to be processed again...
    Rico.writeDebugMsg("Request Timed Out");
    this.liveGrid.showMsg("Request for data timed out!");
  },

  formQueryString: function(startPos,fetchSize) {
    if (typeof fetchSize!='number') fetchSize=this.totalRows;
    var queryString= 'id='+this.liveGrid.tableId+'&page_size='+fetchSize+'&offset='+startPos+this.sortParm;
    if (!this.foundRowCount) queryString+='&get_total=true';
    if (this.options.requestParameters)
      queryString += this._createQueryString(this.options.requestParameters);

    for (n=0; n<this.liveGrid.columns.length; n++)
      queryString += this.liveGrid.columns[n].getFilterQueryParm();
    return queryString;
  },

  sendAjaxRequest: function(startPos,fetchSize,onComplete) {
    this.ajaxOptions.parameters = this.formQueryString(startPos,fetchSize);
    this.ajaxOptions.onComplete = onComplete;
    this.requestCount++;
    Rico.writeDebugMsg('req '+this.requestCount+':'+this.ajaxOptions.parameters);
    new Ajax.Request(this.dataSource, this.ajaxOptions);
  },
  
  clearTimer: function() {
    if(typeof this.timeoutHandler != "number") return;
    window.clearTimeout(this.timeoutHandler);
    delete this.timeoutHandler;
  },

  ajaxUpdate: function(startPos,request) {
    this.clearTimer();
    this.processingRequest=false;
    if (request.status != 200) {
      Rico.writeDebugMsg("ajaxUpdate: received http error="+request.status);
      this.liveGrid.showMsg('Received HTTP error: '+request.status);
      return;
    }
    var response = request.responseXML.getElementsByTagName("ajax-response");
    if (response == null || response.length != 1) return;
    this.updateBuffer(response[0],startPos);
    this.CheckRowCount(response[0],startPos);
    if (this.options.TimeOut && this.timerMsg)
      this.restartSessionTimer();
    if (this.options.onAjaxUpdate)
      this.options.onAjaxUpdate();
    if (this.pendingRequest>=0) {
      var offset=this.pendingRequest;
      Rico.writeDebugMsg("ajaxUpdate: found pending request for offset="+offset);
      this.pendingRequest=-1;
      this.fetch(offset);
    }
  },

  CheckRowCount: function(ajaxResponse,offset) {
    //try {
      Rico.writeDebugMsg("CheckRowCount, size="+this.size+' rcv cnt type='+typeof(this.rowcntContent));
      if (this.rcvdRowCount==true) {
        Rico.writeDebugMsg("found row cnt: "+this.rowcntContent);
        var eofrow=parseInt(this.rowcntContent);
        if (!isNaN(eofrow) && eofrow!=this.totalRows) {
          Rico.writeDebugMsg("shortening totrows to "+eofrow);
          this.setTotalRows(eofrow);
          var newpos=Math.min(this.liveGrid.topOfLastPage(),offset);
          Rico.writeDebugMsg("requery, newpos="+newpos);
          //this.lastRowPos=-1;
          this.liveGrid.scrollToRow(newpos);
          if ( this.isInRange(newpos) ) {
            this.liveGrid.refreshContents(newpos);
          } else {
            this.fetch(newpos);
          }
          return;
        }
      } else {
        var lastbufrow=offset+this.rcvdRows;
        if (lastbufrow>this.totalRows) {
          var newcnt=lastbufrow;
          Rico.writeDebugMsg("extending totrows to "+newcnt);
          this.setTotalRows(newcnt);
        }
      }
      var newpos=this.liveGrid.pixeltorow(this.liveGrid.scrollDiv.scrollTop);
      Rico.writeDebugMsg("CheckRowCount, newpos="+newpos);
      this.liveGrid.refreshContents(newpos);
    //}
    //catch(err) {
    //  alert("Error in CheckRowCount:"+err.message);
    //}
  },

  updateBuffer: function(ajaxResponse, start) {
    Rico.writeDebugMsg("updateBuffer: "+start);
    var newRows = this.loadRows(ajaxResponse);
    if (newRows==null) return;
    Rico.writeDebugMsg("updateBuffer: # of rows="+newRows.length);
    if (this.rows.length == 0) { // initial load
      this.rows = newRows;
      this.size = this.rows.length;
      this.startPos = start;
      return;
    }
    if (start > this.startPos) { //appending
      if (this.startPos + this.rows.length < start) {
        this.rows =  newRows;
        this.startPos = start;//
      } else {
        this.rows = this.rows.concat( newRows.slice(0, newRows.length));
        if (this.rows.length > this.maxBufferSize) {
          var fullSize = this.rows.length;
          this.rows = this.rows.slice(this.rows.length - this.maxBufferSize, this.rows.length)
          this.startPos = this.startPos +  (fullSize - this.rows.length);
        }
      }
    } else { //prepending
      if (start + newRows.length < this.startPos) {
        this.rows =  newRows;
      } else {
        this.rows = newRows.slice(0, this.startPos).concat(this.rows);
        if (this.maxBufferSize && this.rows.length > this.maxBufferSize)
          this.rows = this.rows.slice(0, this.maxBufferSize)
      }
      this.startPos =  start;
    }
    this.size = this.rows.length;
  },

  loadRows: function(ajaxResponse) {
    Rico.writeDebugMsg("loadRows");
    var debugtags = ajaxResponse.getElementsByTagName('debug');
    for (var i=0; i<debugtags.length; i++)
      Rico.writeDebugMsg("loadRows, debug msg "+i+": "+RicoUtil.getContentAsString(debugtags[i],this.options.isEncoded));
    var error = ajaxResponse.getElementsByTagName('error');
    if (error.length > 0) {
      var msg=RicoUtil.getContentAsString(error[0],this.options.isEncoded);
      alert("Data provider returned an error:\n"+msg);
      Rico.writeDebugMsg("Data provider returned an error:\n"+msg);
      return null;
    }
    var rowsElement = ajaxResponse.getElementsByTagName('rows')[0];
    var rowcnttags = ajaxResponse.getElementsByTagName('rowcount');
    this.rcvdRowCount = false;
    if (rowcnttags && rowcnttags.length==1) {
      this.rowcntContent = RicoUtil.getContentAsString(rowcnttags[0],this.options.isEncoded);
      this.rcvdRowCount = true;
      this.foundRowCount = true;
      Rico.writeDebugMsg("loadRows, found RowCount="+this.rowcntContent);
    }
    this.updateUI = rowsElement.getAttribute("update_ui") == "true";
    this.rcvdOffset = rowsElement.getAttribute("offset");
    Rico.writeDebugMsg("loadRows, rcvdOffset="+this.rcvdOffset);
    return this.dom2jstable(rowsElement);
  },

  _createQueryString: function( theArgs ) {
    var queryString = ""
    if (!theArgs) return queryString;
    for ( var i=0; i < theArgs.length; i++ ) {
      var anArg = theArgs[i];
      queryString += "&";
      if ( anArg.name != undefined && anArg.value != undefined ) {
        queryString += anArg.name +  "=" + escape(anArg.value);
      } else {
        var ePos  = anArg.indexOf('=');
        var argName  = anArg.substring( 0, ePos );
        var argValue = anArg.substring( ePos + 1 );
        queryString += argName + "=" + escape(argValue);
      }
    }
    return queryString;
  }

};


// Data source is an SQL Database

Rico.Buffer.AjaxSQL = Class.create();

Rico.Buffer.AjaxSQL.prototype = {

  initialize: function(url,options,ajaxOptions) {
    Object.extend(this, new Rico.Buffer.AjaxXML());
    Object.extend(this, new Rico.Buffer.AjaxSQLMethods());
    this.dataSource=url;
    this.options.canFilter=true;
    this.options.largeBufferSize  = 7.0;   // 7 pages
    this.options.nearLimitFactor  = 1.0;   // 1 page
    Object.extend(this.options, options || {});
    Object.extend(this.ajaxOptions, ajaxOptions || {});
    this.sortParm='';
  }
}
  
Rico.Buffer.AjaxSQLMethods = Class.create();

Rico.Buffer.AjaxSQLMethods.prototype = {

  initialize: function() {
  },
  
  registerGrid: function(liveGrid) {
    this.liveGrid = liveGrid;
    this.sessionExpired=false;
    this.timerMsg=$(liveGrid.tableId+'_timer');
    if (this.options.TimeOut && this.timerMsg) {
      if (!this.timerMsg.title) this.timerMsg.title=RicoTranslate.getPhrase("minutes before your session expires")
      this.restartSessionTimer();
    }
  },
  
  setBufferSize: function(pageSize) {
    this.maxFetchSize = Math.max(50,parseInt(this.options.largeBufferSize * pageSize));
    this.nearLimit = parseInt(this.options.nearLimitFactor * pageSize);
    this.maxBufferSize = this.maxFetchSize * 3;
  },

  restartSessionTimer: function() {
    if (this.sessionExpired==true) return;
    this.timeRemaining=this.options.TimeOut+1;
    if (this.sessionTimer) clearTimeout(this.sessionTimer);
    this.updateSessionTimer();
  },
  
  updateSessionTimer: function() {
    if (--this.timeRemaining<=0) {
      this.displaySessionTimer(RicoTranslate.getPhrase("EXPIRED"));
      this.timerMsg.style.backgroundColor="red";
      this.sessionExpired=true;
    } else {
      this.displaySessionTimer(this.timeRemaining);
      this.sessionTimer=setTimeout(this.updateSessionTimer.bind(this),60000);
    }
  },
  
  displaySessionTimer: function(msg) {
    this.timerMsg.innerHTML='&nbsp;'+msg+'&nbsp;';
  },
  
  fetch: function(offset) {
    Rico.writeDebugMsg("AjaxSQL fetch, offset="+offset+' lastOffset='+this.lastOffset);
    if (this.processingRequest) {
      Rico.writeDebugMsg("AjaxSQL fetch: queue request");
      this.pendingRequest=offset;
      return;
    }
    var lastOffset = this.lastOffset;
    this.lastOffset = offset;
    var inRange=this.isInRange(offset);
    if (inRange) {
      Rico.writeDebugMsg("AjaxSQL fetch: in buffer");
      this.liveGrid.refreshContents(offset);
      if (offset > lastOffset) {
        if (offset+this.liveGrid.pageSize < this.endPos()-this.nearLimit) return;
        if (this.endPos()==this.totalRows && this.foundRowCount) return;
      } else if (offset < lastOffset) {
        if (offset > this.startPos+this.nearLimit) return;
        if (this.startPos==0) return;
      } else return;
    }
    if (offset >= this.totalRows && this.foundRowCount) return;
    
    this.processingRequest=true
    Rico.writeDebugMsg("AjaxSQL fetch, processing offset="+offset);
    var bufferStartPos = this.getFetchOffset(offset);
    var fetchSize = this.getFetchSize(bufferStartPos);
    var partialLoaded = false;

    if (!inRange) this.liveGrid.showMsg("Waiting for data...");
    this.timeoutHandler = setTimeout( this.handleTimedOut.bind(this), this.options.bufferTimeout);
    this.sendAjaxRequest(bufferStartPos,fetchSize,this.ajaxUpdate.bind(this,bufferStartPos));
  },

  getFetchSize: function(adjustedOffset) {
    var adjustedSize = 0;
    if (adjustedOffset >= this.startPos) { //appending
      var endFetchOffset = this.maxFetchSize + adjustedOffset;
      adjustedSize = endFetchOffset - adjustedOffset;
      if(adjustedOffset == 0 && adjustedSize < this.maxFetchSize)
        adjustedSize = this.maxFetchSize;
      Rico.writeDebugMsg("getFetchSize/append, adjustedSize="+adjustedSize+" adjustedOffset="+adjustedOffset+' endFetchOffset='+endFetchOffset);
    } else { //prepending
      adjustedSize = Math.min(this.startPos - adjustedOffset,this.maxFetchSize);
    }
    return adjustedSize;
  },

  getFetchOffset: function(offset) {
    var adjustedOffset = offset;
    if (offset > this.startPos)
      adjustedOffset = Math.max(offset, this.endPos());  //appending
    else if (offset + this.maxFetchSize >= this.startPos)
      adjustedOffset = Math.max(this.startPos - this.maxFetchSize, 0);  //prepending
    return adjustedOffset;
  },

  sortBuffer: function(colnum,sortdir,coltype) {
    this.sortParm='&s'+colnum+'='+sortdir;
    this.clear();
  },
  
  exportAllRows: function(populate,finish) {
    this.exportPopulate=populate;
    this.exportFinish=finish;
    this.liveGrid.showMsg("Waiting for data...");
    this.sendExportRequest(0);
  },
  
  // make ajax request for print window data
  sendExportRequest: function(offset) {
    this.timeoutHandler = setTimeout( this.exportTimedOut.bind(this), this.options.bufferTimeout);
    this.sendAjaxRequest(offset,200,this.exportAppend.bind(this,offset));
  },

  exportTimedOut: function() {
    Rico.writeDebugMsg("Print Request Timed Out");
    this.liveGrid.showMsg("Request for data timed out!");
    this.exportFinish();
  },

  exportAppend: function(startPos,request) {
    this.clearTimer();
    var response = request.responseXML.getElementsByTagName("ajax-response");
    if (response == null || response.length != 1) return;
    var rowsElement = response[0].getElementsByTagName('rows')[0];
    var rows=this.dom2jstable(rowsElement);
    this.exportPopulate(rows);
    if (rows.length==0)
      this.exportFinish();
    else
      this.sendExportRequest(startPos+rows.length);
  }

};

Rico.addPreloadMsg('exec: ricoLiveGridAjax.js');
