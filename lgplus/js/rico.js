/**
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
  **/


// This module does NOT depend on prototype.js

var Rico = {
  Version: '1.2',
  loadRequested: 1,
  loadComplete: 2,
  init : function() {
    this.preloadMsgs='';
    var elements = document.getElementsByTagName('script');
    this.baseHref= location.protocol + "//" + location.host;
    this.loadedFiles={};
    this.loadQueue=[];    
    this.windowIsLoaded=false;
    this.onLoadCallbacks=[];
    for (var i=0; i<elements.length; i++) {
      if (!elements[i].src) continue;
      var src = elements[i].src;
      var slashIdx = src.lastIndexOf('/');
      var path = src.substring(0, slashIdx+1);
      var filename = src.substring(slashIdx+1);
      this.loadedFiles[filename]=this.loadComplete;
      if (filename == 'rico.js') {
        this.jsDir = path;
        this.cssDir= path.replace(/js\/$/,'css/');
        this.imgDir= path.replace(/js\/$/,'images/');
        this.htmDir= path.replace(/js\/$/,'xhtml/');
        this.xslDir= path.replace(/js\/$/,'xsl/');
      }
    }
    if (typeof Prototype=='undefined')
      this.include('prototype.js');
    this.include('ricoCommon.js');
    var func=function() { Rico.windowLoaded(); };
    if (window.addEventListener)
      window.addEventListener('load', func, false);
    else if (window.attachEvent)
      window.attachEvent('onload', func);
    this.onLoad(function() { 'Pre-load messages:\n'+Rico.writeDebugMsg(Rico.preloadMsgs); });
  },
  
  moduleDependencies : {
    Accordion  : ['ricoEffects.js'],
    Color      : ['ricoEffects.js'],
    Corner     : ['ricoEffects.js'],
    DragAndDrop: ['ricoEffects.js'],
    Effect     : ['ricoEffects.js'],
    Calendar   : ['ricoCalendar.js', 'ricoCalendar.css'],
    Tree       : ['ricoTree.js', 'ricoTree.css'],
    SimpleGrid : ['ricoCommon.js', 'ricoSimpleGrid.js', 'ricoGrid.css'],
    LiveGrid   : ['ricoSimpleGrid.js', 'ricoLiveGrid.js', 'ricoGrid.css'],
    CustomMenu : ['ricoMenu.css'],
    LiveGridMenu : ['ricoLiveGridMenu.js', 'ricoMenu.css'],
    LiveGridAjax : ['ricoSimpleGrid.js', 'ricoLiveGrid.js', 'ricoLiveGridAjax.js', 'ricoGrid.css'],
    LiveGridForms: ['ricoSimpleGrid.js', 'ricoLiveGrid.js', 'ricoLiveGridAjax.js', 'ricoLiveGridMenu.js', 'ricoMenu.css', 'ricoLiveGridForms.js', 'ricoEffects.js', 'ricoGrid.css', 'ricoLiveGridForms.css']
  },
  
  // not reliable when used with XSLT
  loadModule : function(name) {
    var dep=this.moduleDependencies[name];
    if (!dep) return;
    for (var i=0; i<dep.length; i++)
      this.include(dep[i]);
  },
  
  // not reliable when used with XSLT
  include : function(filename) {
    if (this.loadedFiles[filename]) return;
    this.addPreloadMsg('include: '+filename);
    var ext = filename.substr(filename.lastIndexOf('.')+1);
    switch (ext.toLowerCase()) {
      case 'js':
        this.loadQueue.push(filename);
        this.loadedFiles[filename]=this.loadRequested;
        this.checkLoadQueue();
        return;
      case 'css':
        var el = document.createElement('link');
        el.type = 'text/css';
        el.rel = 'stylesheet'
        el.href = this.cssDir+filename;
        this.loadedFiles[filename]=this.loadComplete;
        document.getElementsByTagName('head')[0].appendChild(el);
        return;
    }
  },
  
  checkLoadQueue: function() {
    if (this.loadQueue.length==0) return;
    if (this.inProcess) return;  // seems to only be required by IE, but applied to all browsers just to be safe
    this.addScriptToDOM(this.loadQueue.shift());
  },
  
  addScriptToDOM: function(filename) {
    this.addPreloadMsg('addScriptToDOM: '+filename);
    var el = document.createElement('script');
    el.type = 'text/javascript';
    el.src = this.jsDir+filename;
    this.loadedFiles[filename]=this.loadRequested;
    el.onload = el.onreadystatechange = function() {
      if (el.readyState && el.readyState != 'loaded' && el.readyState != 'complete') return;
      el.onreadystatechange = el.onload = null;
      Rico.includeLoaded(filename);
    };
    this.inProcess=filename;
    document.getElementsByTagName('head')[0].appendChild(el);
  },
  
  // called after a script file has finished loading
  includeLoaded: function(filename) {
    this.addPreloadMsg('loaded: '+filename);
    if (filename!=this.inProcess) {
      alert('An error occurred while loading javascript files:\nExpected: '+this.inProcess+'\nFound: '+filename);
    } else {
      this.inProcess=null;
      this.loadedFiles[filename]=this.loadComplete;
      this.checkLoadQueue();
      this.checkIfComplete();
    }
  },

  // called by the document onload event
  windowLoaded: function() {
    this.windowIsLoaded=true;
    this.checkIfComplete();
  },
  
  checkIfComplete: function() {
    var waitingFor=this.windowIsLoaded ? '' : 'window';
    for(var filename in  this.loadedFiles) {
      if (this.loadedFiles[filename]==this.loadRequested)
        waitingFor+=' '+filename;
    }
    this.addPreloadMsg('waitingFor: '+waitingFor);
    if (waitingFor.length==0) {
      this.addPreloadMsg('Processing callbacks');
      while (this.onLoadCallbacks.length > 0) {
        var callback=this.onLoadCallbacks.pop();
        if (callback) callback();
      }
    }
  },
  
  onLoad: function(callback) {
    this.onLoadCallbacks.push(callback);
    this.checkIfComplete();
  },

  isKonqueror : navigator.userAgent.toLowerCase().indexOf("konqueror") >= 0,
  isSafari    : navigator.userAgent.toLowerCase().indexOf("safari") >= 0,
  isOpera     : (typeof(window.opera)=='object') && (window.opera!=null),
  isIE        : (typeof(document.all)=='object') && (!window.opera),

  // logging funtions
   
  startTime : new Date(),

  timeStamp: function() {
    var stamp = new Date();
    return (stamp.getTime()-this.startTime.getTime())+": ";
  },
  
  setDebugArea: function(id, forceit) {
    if (!this.debugArea || forceit) {
      var newarea=document.getElementById(id);
      if (!newarea) return;
      this.debugArea=newarea;
      newarea.value='';
    }
  },

  addPreloadMsg: function(msg) {
    this.preloadMsgs+=Rico.timeStamp()+msg+"\n";
  },

  writeDebugMsg: function(msg, resetFlag) {
    if (this.debugArea) {
      if (resetFlag) this.debugArea.value='';
      this.debugArea.value+=this.timeStamp()+msg+"\n";
    }
  }

}

Rico.init();
