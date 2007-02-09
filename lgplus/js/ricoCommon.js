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

if (typeof Rico=='undefined')
  throw("Cannot find the Rico object");
if (typeof Prototype=='undefined')
  throw("Rico requires the Prototype JavaScript framework");
Rico.prototypeVersion = parseFloat(Prototype.Version.split(".")[0] + "." + Prototype.Version.split(".")[1]);
if (Rico.prototypeVersion < 1.3)
  throw("Rico requires Prototype JavaScript framework version 1.3 or greater");


Rico.ArrayExtensions = new Array();

if (Object.prototype.extend) {
   Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Object.prototype.extend;
}else{
  Object.prototype.extend = function(object) {
    return Object.extend.apply(this, [this, object]);
  }
  Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Object.prototype.extend;
}

if (Array.prototype.push) {
   Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Array.prototype.push;
}

if (!Array.prototype.remove) {
   Array.prototype.remove = function(dx) {
      if( isNaN(dx) || dx > this.length )
         return false;
      for( var i=0,n=0; i<this.length; i++ )
         if( i != dx )
            this[n++]=this[i];
      this.length-=1;
   };
  Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Array.prototype.remove;
}

if (!Array.prototype.removeItem) {
   Array.prototype.removeItem = function(item) {
      for ( var i = 0 ; i < this.length ; i++ )
         if ( this[i] == item ) {
            this.remove(i);
            break;
         }
   };
  Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Array.prototype.removeItem;
}

if (!Array.prototype.pushHTMLCollection) {
   Array.prototype.pushHTMLCollection = function(HTMLCol) {
      for ( var i = 0 ; i < HTMLCol.length ; i++ )
         this.push(HTMLCol.item(i))
   };
  Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Array.prototype.pushHTMLCollection;
}

if (!Array.prototype.indices) {
   Array.prototype.indices = function() {
      var indexArray = new Array();
      for ( index in this ) {
         var ignoreThis = false;
         for ( var i = 0 ; i < Rico.ArrayExtensions.length ; i++ ) {
            if ( this[index] == Rico.ArrayExtensions[i] ) {
               ignoreThis = true;
               break;
            }
         }
         if ( !ignoreThis )
            indexArray[ indexArray.length ] = index;
      }
      return indexArray;
   }
  Rico.ArrayExtensions[ Rico.ArrayExtensions.length ] = Array.prototype.indices;
}

// Create the loadXML method and xml getter for Mozilla
if ( window.DOMParser &&
	  window.XMLSerializer &&
	  window.Node && Node.prototype && Node.prototype.__defineGetter__ ) {

   if (!Document.prototype.loadXML) {
      Document.prototype.loadXML = function (s) {
         var doc2 = (new DOMParser()).parseFromString(s, "text/xml");
         while (this.hasChildNodes())
            this.removeChild(this.lastChild);

         for (var i = 0; i < doc2.childNodes.length; i++) {
            this.appendChild(this.importNode(doc2.childNodes[i], true));
         }
      };
	}

	Document.prototype.__defineGetter__( "xml",
	   function () {
		   return (new XMLSerializer()).serializeToString(this);
	   }
	 );
}

document.getElementsByTagAndClassName = function(tagName, className) {
  if ( tagName == null )
     tagName = '*';

  var children = document.getElementsByTagName(tagName) || document.all;
  var elements = new Array();

  if ( className == null )
    return children;

  for (var i = 0; i < children.length; i++) {
    var child = children[i];
    var classNames = child.className.split(' ');
    for (var j = 0; j < classNames.length; j++) {
      if (classNames[j] == className) {
        elements.push(child);
        break;
      }
    }
  }

  return elements;
}



//-------------------- ricoUtil.js
var RicoUtil = {

   getDirectChildrenByTag: function(e, tagName) {
      var kids = new Array();
      var allKids = e.childNodes;
      tagName=tagName.toLowerCase();
      for( var i = 0 ; i < allKids.length ; i++ )
         if ( allKids[i] && allKids[i].tagName && allKids[i].tagName.toLowerCase() == tagName )
            kids.push(allKids[i]);
      return kids;
   },

   createXmlDocument : function() {
      if (document.implementation && document.implementation.createDocument) {
         var doc = document.implementation.createDocument("", "", null);

         if (doc.readyState == null) {
            doc.readyState = 1;
            doc.addEventListener("load", function () {
               doc.readyState = 4;
               if (typeof doc.onreadystatechange == "function")
                  doc.onreadystatechange();
            }, false);
         }

         return doc;
      }

      if (window.ActiveXObject)
          return Try.these(
            function() { return new ActiveXObject('MSXML2.DomDocument')   },
            function() { return new ActiveXObject('Microsoft.DomDocument')},
            function() { return new ActiveXObject('MSXML.DomDocument')    },
            function() { return new ActiveXObject('MSXML3.DomDocument')   }
          ) || false;

      return null;
   },

   getInnerText: function(el) {
     if (typeof el == "string") return el;
     if (typeof el == "undefined") { return el };
     var cs = el.childNodes;
     var l = cs.length;
     if (el.innerText) return el.innerText;  //Not needed but it is faster
     var str = "";
     for (var i = 0; i < l; i++) {
       switch (cs[i].nodeType) {
         case 1: //ELEMENT_NODE
           str += (cs[i].tagName.toLowerCase()=='img') ? cs[i].src : ts_getInnerText(cs[i]);
           break;
         case 3: //TEXT_NODE
           str += cs[i].nodeValue;
           break;
       }
     }
     return str;
   },

   // For Konqueror 3.5, isEncoded must be true
   getContentAsString: function( parentNode, isEncoded ) {
      if (isEncoded) return this._getEncodedContent(parentNode);
      if (typeof parentNode.xml != 'undefined') return this._getContentAsStringIE(parentNode);
      return this._getContentAsStringMozilla(parentNode);
   },

   _getEncodedContent: function(parentNode) {
      if (parentNode.innerHTML) return parentNode.innerHTML;
      switch (parentNode.childNodes.length) {
        case 0:  return "";
        case 1:  return parentNode.firstChild.nodeValue;
        default: return parentNode.childNodes[1].nodeValue;
      }
   },

  _getContentAsStringIE: function(parentNode) {
     var contentStr = "";
     for ( var i = 0 ; i < parentNode.childNodes.length ; i++ ) {
         var n = parentNode.childNodes[i];
         if (n.nodeType == 4) {
             contentStr += n.nodeValue;
         }
         else {
           contentStr += n.xml;
       }
     }
     return contentStr;
  },

  _getContentAsStringMozilla: function(parentNode) {
     var xmlSerializer = new XMLSerializer();
     var contentStr = "";
     for ( var i = 0 ; i < parentNode.childNodes.length ; i++ ) {
          var n = parentNode.childNodes[i];
          if (n.nodeType == 4) { // CDATA node
              contentStr += n.nodeValue;
          }
          else {
            contentStr += xmlSerializer.serializeToString(n);
        }
     }
     return contentStr;
  },
  
  docElement: function() {
    return (document.compatMode && document.compatMode.indexOf("CSS")!=-1) ? document.documentElement : document.getElementsByTagName("body")[0];
  },

  windowHeight: function() {
    return window.innerHeight? innerHeight : this.docElement().clientHeight;
  },

  windowWidth: function() {
    return window.innerWidth? innerWidth : this.docElement().clientWidth;
  },

  docScrollLeft: function() {
     if ( window.pageXOffset )
        return window.pageXOffset;
     else if ( document.documentElement && document.documentElement.scrollLeft )
        return document.documentElement.scrollLeft;
     else if ( document.body )
        return document.body.scrollLeft;
     else
        return 0;
  },

  docScrollTop: function() {
     if ( window.pageYOffset )
        return window.pageYOffset;
     else if ( document.documentElement && document.documentElement.scrollTop )
        return document.documentElement.scrollTop;
     else if ( document.body )
        return document.body.scrollTop;
     else
        return 0;
  },

  nan2zero: function(n) {
    if (typeof(n)=='string') n=parseInt(n);
    return isNaN(n) || typeof(n)=='undefined' ? 0 : n;
  },

  eventKey: function(e) {
    if( typeof( e.keyCode ) == 'number'  ) {
      return e.keyCode; //DOM
    } else if( typeof( e.which ) == 'number' ) {
      return e.which;   //NS 4 compatible
    } else if( typeof( e.charCode ) == 'number'  ) {
      return e.charCode; //also NS 6+, Mozilla 0.9+
    }
    return -1;  //total failure, we have no way of obtaining the key code
  },

   // Return the previous sibling that has the specified tagName
   getPreviosSiblingByTagName: function(el,tagName) {
   	var sib=el.previousSibling;
   	while (sib) {
   		if ((sib.tagName==tagName) && (sib.style.display!='none')) return sib;
   		sib=sib.previousSibling;
   	}
   	return null;
   },
 
   // Return the parent HTML element that has the specified tagName
   // className is optional
   getParentByTagName: function(el,tagName,className) {
   	var par=el;
   	tagName=tagName.toLowerCase();
   	while (par) {
   		if (par.tagName && par.tagName.toLowerCase()==tagName)
        if (!className || par.className.indexOf(className)>=0) return par;
   		par=par.parentNode;
   	}
   	return null;
   },

  wrapChildren: function(el,cls,id,wrapperTag) {
    var tag=wrapperTag || 'div';
    var wrapper = document.createElement(tag);
    if (id) wrapper.id=id;
    if (cls) wrapper.className=cls;
    while (el.firstChild)
      wrapper.appendChild(el.firstChild);
    el.appendChild(wrapper);
    return wrapper;
  },
  
  // format a positive number
  // decPlaces is the number of digits to display after the decimal point
  // thouSep is the character to use as the thousands separator
  // decPoint is the character to use as the decimal point
  formatPosNumber: function(posnum,decPlaces,thouSep,decPoint) {
    var a=posnum.toFixed(decPlaces).split(/\./);
    if (thouSep) {
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(a[0]))
        a[0]=a[0].replace(rgx, '$1'+thouSep+'$2');
    }
    return a.join(decPoint);
  },

  //Post condition: if childNodes[n] is refChild, than childNodes[n+1] is newChild.
  DOMNode_insertAfter: function(newChild,refChild) {
    var parentx=refChild.parentNode;
    if(parentx.lastChild==refChild) { return parentx.appendChild(newChild);}
    else {return parentx.insertBefore(newChild,refChild.nextSibling);}
  },
   
  positionCtlOverIcon: function(ctl,icon) {
    var offsets=Position.page(icon);
    var correction=Rico.isIE ? 1 : 2;  // based on a 1px border
    var lpad=parseInt(icon.style.paddingLeft)

    ctl.style.left = (offsets[0]+lpad+correction)+'px';
    var scrTop=this.docScrollTop();
    var newTop=offsets[1] + correction + scrTop;
    //alert('newTop='+newTop+' o.y='+offsets[1]+' correction='+correction);
    var ctlht=ctl.offsetHeight;
    var iconht=icon.offsetHeight;
    if (newTop+iconht+ctlht < this.windowHeight()+scrTop)
      newTop+=iconht;  // display below icon
    else
      newTop=Math.max(newTop-ctlht,scrTop);  // display above icon
    ctl.style.top = newTop+'px';
  },

  createFormField: function(parent,elemTag,elemType,id,name) {
    if (typeof name!='string') name=id;
    if (Rico.isIE) {
      // IE cannot set NAME attribute on dynamically created elements
      var s=elemTag+' id="'+id+'"';
      if (elemType) s+=' type="'+elemType+'"';
      if (elemTag.match(/^(form|input|select|textarea|object|button|img)$/)) s+=' name="'+name+'"';
      var field=document.createElement('<'+s+' />');
    } else {
      var field=document.createElement(elemTag);
      if (elemType) field.type=elemType;
      field.id=id;
      if (typeof field.name=='string') field.name=name;
    }
    parent.appendChild(field);
    return field;
  }
  
};


// Translation helper object
var RicoTranslate = {
  phrases : new Array(),
  thouSep : ",",
  decPoint: ".",
  langCode: "en",
  re      : /^(\W*)\b(.*)\b(\W*)$/,
  dateFmt : "mm/dd/yyyy",
  timeFmt : "hh:mm:ss a/pm",
  monthNames: ['January','February','March','April','May','June',
               'July','August','September','October','November','December'],
  dayNames: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
  
  addPhrase: function(fromPhrase, toPhrase) {
    this.phrases[fromPhrase]=toPhrase;
  },
  
  // fromPhrase may contain multiple words/phrases separated by tabs
  // and each portion will be looked up separately.
  // Punctuation & spaces at the beginning or
  // ending of a phrase are ignored.
  getPhrase: function(fromPhrase) {
    var words=fromPhrase.split(/\t/);
    var transWord,translated = '';
    for (var i=0; i<words.length; i++) {
      if (this.re.exec(words[i])) {
        transWord=this.phrases[RegExp.$2];
        translated += (typeof transWord=='string') ? RegExp.$1+transWord+RegExp.$3 : words[i];
      } else {
        translated += words[i];
      }
    }
    return translated;
  }
}


// zero-fill
if (!Number.prototype.zf) {
  Number.prototype.zf = function(slen) { 
      var s=this.toString();
      while (s.length<slen) s='0'+s;
      return s;
  }
}

if (!Date.prototype.formatDate) {
  Date.prototype.formatDate = function(fmt) {
    var d=this;
    var datefmt=(typeof fmt=='string') ? datefmt=fmt : 'translateDate';
    switch (datefmt) {
      case 'locale':
      case 'localeDateTime':
        return d.toLocaleString();
      case 'localeDate':
        return d.toLocaleDateString();
      case 'translate':
      case 'translateDateTime':
        datefmt=RicoTranslate.dateFmt+' '+RicoTranslate.timeFmt;
        break;
      case 'translateDate':
        datefmt=RicoTranslate.dateFmt;
        break;
    }
    return datefmt.replace(/(yyyy|mmmm|mmm|mm|dddd|ddd|dd|hh|nn|ss|a\/p)/gi,
      function($1) {
        switch ($1.toLowerCase()) {
        case 'yyyy': return d.getFullYear();
        case 'mmmm': return RicoTranslate.monthNames[d.getMonth()];
        case 'mmm':  return RicoTranslate.monthNames[d.getMonth()].substr(0, 3);
        case 'mm':   return (d.getMonth() + 1).zf(2);
        case 'm':    return (d.getMonth() + 1);
        case 'dddd': return RicoTranslate.dayNames[d.getDay()];
        case 'ddd':  return RicoTranslate.dayNames[d.getDay()].substr(0, 3);
        case 'dd':   return d.getDate().zf(2);
        case 'd':    return d.getDate();
        case 'hh':   return ((h = d.getHours() % 12) ? h : 12).zf(2);
        case 'h':    return ((h = d.getHours() % 12) ? h : 12);
        case 'HH':   return d.getHours().zf(2);
        case 'H':    return d.getHours();
        case 'nn':   return d.getMinutes().zf(2);
        case 'ss':   return d.getSeconds().zf(2);
        case 'a/p':  return d.getHours() < 12 ? 'a' : 'p';
        }
      }
    );
  }
}

// based on info at http://delete.me.uk/2005/03/iso8601.html
if (!Date.prototype.setISO8601) {
  Date.prototype.setISO8601 = function (string) {
    if (!string) return;
    var d = string.match(/(\d\d\d\d)(?:-?(\d\d)(?:-?(\d\d)(?:[T ](\d\d)(?::?(\d\d)(?::?(\d\d)(?:\.(\d+))?)?)?(Z|(?:([-+])(\d\d)(?::?(\d\d))?)?)?)?)?)?/);
    var offset = 0;
    var date = new Date(d[1], 0, 1);
  
    if (d[2]) { date.setMonth(d[2] - 1); }
    if (d[3]) { date.setDate(d[3]); }
    if (d[4]) { date.setHours(d[4]); }
    if (d[5]) { date.setMinutes(d[5]); }
    if (d[6]) { date.setSeconds(d[6]); }
    if (d[7]) { date.setMilliseconds(Number("0." + d[7]) * 1000); }
    if (d[8]) {
        if (d[10] && d[11]) offset = (Number(d[10]) * 60) + Number(d[11]);
        offset *= ((d[9] == '-') ? 1 : -1);
        offset -= date.getTimezoneOffset();
    }
    var time = (Number(date) + (offset * 60 * 1000));
    this.setTime(Number(time));
  }
}

if (!Date.prototype.toISO8601String) {
  Date.prototype.toISO8601String = function (format, offset) {
    /* accepted values for the format [1-6]:
     1 Year:
       YYYY (eg 1997)
     2 Year and month:
       YYYY-MM (eg 1997-07)
     3 Complete date:
       YYYY-MM-DD (eg 1997-07-16)
     4 Complete date plus hours and minutes:
       YYYY-MM-DDThh:mmTZD (eg 1997-07-16T19:20+01:00)
     5 Complete date plus hours, minutes and seconds:
       YYYY-MM-DDThh:mm:ssTZD (eg 1997-07-16T19:20:30+01:00)
     6 Complete date plus hours, minutes, seconds and a decimal
       fraction of a second
       YYYY-MM-DDThh:mm:ss.sTZD (eg 1997-07-16T19:20:30.45+01:00)
    */
    if (!format) { var format = 6; }
    if (!offset) {
        var offset = 'Z';
        var date = this;
    } else {
        var d = offset.match(/([-+])([0-9]{2}):([0-9]{2})/);
        var offsetnum = (Number(d[2]) * 60) + Number(d[3]);
        offsetnum *= ((d[1] == '-') ? -1 : 1);
        var date = new Date(Number(Number(this) + (offsetnum * 60000)));
    }

    var zeropad = function (num) { return ((num < 10) ? '0' : '') + num; }

    var str = "";
    str += date.getUTCFullYear();
    if (format > 1) { str += "-" + zeropad(date.getUTCMonth() + 1); }
    if (format > 2) { str += "-" + zeropad(date.getUTCDate()); }
    if (format > 3) {
        str += "T" + zeropad(date.getUTCHours()) +
               ":" + zeropad(date.getUTCMinutes());
    }
    if (format > 5) {
        var secs = Number(date.getUTCSeconds() + "." +
                   ((date.getUTCMilliseconds() < 100) ? '0' : '') +
                   zeropad(date.getUTCMilliseconds()));
        str += ":" + zeropad(secs);
    } else if (format > 4) { str += ":" + zeropad(date.getUTCSeconds()); }

    if (format > 3) { str += offset; }
    return str;
  }
}

// based on: http://www.codeproject.com/jscript/dateformat.asp
if (!String.prototype.formatDate) {
  String.prototype.formatDate = function(fmt) {
    var s=this.replace(/-/g,'/');
    var d = new Date(s);
    return isNaN(d) ? this : d.formatDate(fmt);
  }
}

// Take a string that can be converted via parseFloat
// and format it according to the specs in assoc array 'fmt'.
// Result is wrapped in a span element with a class of: negNumber, zeroNumber, posNumber
// These classes can be set in CSS to display negative numbers in red, for example.
//
// fmt may contain:
//   multiplier - the original number is multiplied by this amount before formatting
//   decPlaces  - number of digits to the right of the decimal point
//   thouSep    - character to use as the thousands separator
//   prefix     - string added to the beginning of the result (e.g. a currency symbol)
//   suffix     - string added to the end of the result (e.g. % symbol)
//   negSign    - specifies format for negative numbers: L=leading minus, T=trailing minus, P=parens
if (!String.prototype.formatNumber) {
  String.prototype.formatNumber = function(fmt) {
    var n=parseFloat(this);
    if (isNaN(n)) return this;
    if (typeof fmt.multiplier=='number') n*=fmt.multiplier;
    var decPlaces=typeof fmt.decPlaces=='number' ? fmt.decPlaces : 0;
    var thouSep=typeof fmt.thouSep=='string' ? fmt.thouSep : RicoTranslate.thouSep;
    var decPoint=typeof fmt.decPoint=='string' ? fmt.decPoint : RicoTranslate.decPoint;
    var prefix=fmt.prefix || "";
    var suffix=fmt.suffix || "";
    var negSign=typeof fmt.negSign=='string' ? fmt.negSign : "L";
    negSign=negSign.toUpperCase();
    var s,cls;
    if (n<0.0) {
      s=RicoUtil.formatPosNumber(-n,decPlaces,thouSep,decPoint);
      if (negSign=="P") s="("+s+")";
      s=prefix+s;
      if (negSign=="L") s="-"+s;
      if (negSign=="T") s+="-";
      cls='negNumber';
    } else {
      cls=n==0.0 ? 'zeroNumber' : 'posNumber';
      s=prefix+RicoUtil.formatPosNumber(n,decPlaces,thouSep,decPoint);
    }
    return "<span class='"+cls+"'>"+s+suffix+"</span>";
  }
}

// Fix select control bleed-thru on floating divs in IE
// based on technique published by Joe King
// http://dotnetjunkies.com/WebLog/jking/archive/2003/10/30/2975.aspx
Rico.Shim = Class.create();

if (Rico.isIE) {
  Rico.Shim.prototype = {
  
    initialize: function() {
      this.ifr = document.createElement('iframe');
      this.ifr.style.position="absolute";
      this.ifr.style.display = "none";
      this.ifr.src="javascript:false;";
      var body = document.getElementsByTagName("body")[0];
      body.appendChild(this.ifr);
    },
  
    hide: function() {
      this.ifr.style.display = "none";
    },
    
    show: function(DivRef) {
      //alert("show shim:\nw="+DivRef.offsetWidth+"\nh="+DivRef.offsetHeight+'\ntop='+DivRef.style.top+"\nleft="+DivRef.style.left);
      this.ifr.style.width = DivRef.offsetWidth;
      this.ifr.style.height= DivRef.offsetHeight;
      this.ifr.style.top   = DivRef.style.top;
      this.ifr.style.left  = DivRef.style.left;
      this.ifr.style.zIndex= DivRef.currentStyle.zIndex - 1;
      //this.ifr.style.border = "2px solid green"; // for debugging
      this.ifr.style.display = "block";
    }
  }
} else {
  Rico.Shim.prototype = {
    initialize: function() {},
    hide: function() {},
    show: function() {}
  }
}


// Rico.Shadow is currently intended for positioned elements

Rico.Shadow = Class.create();

Rico.Shadow.prototype = {

  initialize: function(DivRef) {
    this.div = document.createElement('div');
    this.div.style.position="absolute";
    if (typeof this.div.style.filter=='undefined') {
      this.createShadow();
      this.offset=5;
    } else {
      this.div.style.backgroundColor='#888';
      this.div.style.filter='progid:DXImageTransform.Microsoft.Blur(makeShadow=1, shadowOpacity=0.3, pixelRadius=3)';
      this.offset=0; // MS blur filter already does offset
    }
    this.div.style.display = "none";
    DivRef.parentNode.appendChild(this.div);
    this.DivRef=DivRef;
    new Image().src = Rico.imgDir+"shadow.png";
    new Image().src = Rico.imgDir+"shadow_ur.png";
    new Image().src = Rico.imgDir+"shadow_ll.png";
  },

  // for non-IE browsers use alpha-transparent png images
  // based on: http://www.positioniseverything.net/articles/dropshadows.html
  createShadow: function() {
    var tab = document.createElement('table');
    tab.style.height='100%';
    tab.style.width='100%';
    tab.cellSpacing=0;

    var tr1=tab.insertRow(-1);
    tr1.style.height='8px';
    var td11=tr1.insertCell(-1);
    td11.style.width='8px';
    var td12=tr1.insertCell(-1);
    td12.style.background="transparent url("+Rico.imgDir+"shadow_ur.png"+") no-repeat right bottom"

    var tr2=tab.insertRow(-1);
    var td21=tr2.insertCell(-1);
    td21.style.background="transparent url("+Rico.imgDir+"shadow_ll.png"+") no-repeat right bottom"
    var td22=tr2.insertCell(-1);
    td22.style.background="transparent url("+Rico.imgDir+"shadow.png"+") no-repeat right bottom"

    this.div.appendChild(tab);
  },

  hide: function() {
    this.div.style.display = "none";
  },
  
  show: function() {
    this.div.style.width = this.DivRef.offsetWidth + 'px';
    this.div.style.height= this.DivRef.offsetHeight + 'px';
    this.div.style.top   = (parseInt(this.DivRef.style.top)+this.offset)+'px';
    this.div.style.left  = (parseInt(this.DivRef.style.left)+this.offset)+'px';
    this.div.style.zIndex= parseInt(Element.getStyle(this.DivRef,'z-index')) - 1;
    this.div.style.display = "block";
  }
}

Rico.Menu = Class.create();

Rico.Menu.prototype = {

  initialize: function(options) {
    this.options = {
      width        : "15em",
      hideOnEscape : true,
      hideOnClick  : true
    };
    Object.extend(this.options, options || {});
    this.hideFunc=null;
    this.highlightElem=null;
  },
  
  createDiv: function() {
    if (this.div) return;
    this.div = document.createElement('div');
    this.div.className = Rico.isSafari ? 'ricoMenuSafari' : 'ricoMenu';
    this.div.style.position="absolute";
    this.div.style.width=this.options.width;
    var body = document.getElementsByTagName("body")[0];
    if (!body) alert('no document body!');
    body.appendChild(this.div);
    this.width=this.div.offsetWidth
    this.shim=new Rico.Shim();
    this.shadow=new Rico.Shadow(this.div);
    this.hidemenu();
    this.itemCount=0;
    if (this.options.hideOnClick)
      Event.observe(document,"click", this.cancelmenu.bindAsEventListener(this), false);
    if (this.options.hideOnEscape)
      Event.observe(document,"keyup", this.checkKey.bindAsEventListener(this), false);
  },
  
  ignoreMenuClicks: function() {
    Event.observe(this.div,"click", this.ignoreClick.bindAsEventListener(this), false);
  },

  ignoreClick: function(e) {
    Event.stop(e);
    return false;
  },
  
  // event handler to process keyup events (hide menu on escape key)
  checkKey: function(e) {
    if (RicoUtil.eventKey(e)==27) this.cancelmenu(e);
    return true;
  },

  showmenu: function(e,hideFunc){
    Event.stop(e);
    this.hideFunc=hideFunc;
    if (this.div.childNodes.length==0) {
      this.cancelmenu();
      return false;
    }
    this.openmenu(e.clientX,e.clientY,0,0);
  },
  
  openmenu: function(x,y,clickItemWi,clickItemHt) {
    var margin=6; // account for shadow
    var newLeft=RicoUtil.docScrollLeft()+x;
    if (x+this.width+margin > RicoUtil.windowWidth()) newLeft-=this.width+clickItemWi;
    this.div.style.left=newLeft+"px";
    var newTop=RicoUtil.docScrollTop()+y;
    var contentHt=this.div.offsetHeight;
    if (y+contentHt+margin > RicoUtil.windowHeight())
      newTop=Math.max(newTop-contentHt+clickItemHt,0);
    this.div.style.top=newTop+"px";
    this.div.style.visibility ="visible";
    this.shim.show(this.div);
    this.shadow.show();
    return false;
  },

  clearMenu: function() {
    this.div.innerHTML="";
    this.defaultAction=null;
    this.itemCount=0;
  },

  addMenuHeading: function(hdg,translate) {
    var el=document.createElement('div')
    el.innerHTML =(translate==null || translate==true) ? RicoTranslate.getPhrase(hdg) : hdg;
    el.className='ricoMenuHeading';
    this.div.appendChild(el);
  },

  addMenuBreak: function() {
    var brk=document.createElement('div');
    brk.className="ricoMenuBreak";
    this.div.appendChild(brk);
  },

  addSubMenuItem: function(menutext, submenu, translate) {
    var a=this.addMenuItem(menutext,null,true,null,translate);
    a.className='ricoSubMenu';
    a.style.backgroundImage='url('+Rico.imgDir+'right.gif)';
    a.style.backgroundRepeat='no-repeat';
    a.style.backgroundPosition='right';
    a.onmouseover=this.showSubMenu.bind(this,a,submenu);
    a.onmouseout=this.subMenuOut.bindAsEventListener(this);
  },
  
  showSubMenu: function(a,submenu) {
    if (this.openSubMenu) this.hideSubMenu();
    this.openSubMenu=submenu;
    this.openMenuAnchor=a;
    var pos=Position.page(a);
    if (a.className=='ricoSubMenu') a.className='ricoSubMenuOpen';
    submenu.openmenu(pos[0]+a.offsetWidth, pos[1], a.offsetWidth-2, a.offsetHeight+2);
  },
  
  subMenuOut: function(e) {
    if (!this.openSubMenu) return;
    Event.stop(e);
    var elem=Event.element(e);
    var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
    try {
      while (reltg != null && reltg != this.openSubMenu.div)
        reltg=reltg.parentNode;
    } catch(err) {}
    if (reltg == this.openSubMenu.div) return;
    this.hideSubMenu();
  },
  
  hideSubMenu: function() {
    if (this.openMenuAnchor) {
      this.openMenuAnchor.className='ricoSubMenu';
      this.openMenuAnchor=null;
    }
    if (this.openSubMenu) {
      this.openSubMenu.hidemenu();
      this.openSubMenu=null;
    }
  },

  addMenuItem: function(menutext,action,enabled,title,translate,target) {
    this.itemCount++;
    if (translate==null) translate=true;
    var a = document.createElement(typeof action=='string' ? 'a' : 'div');
    if ( arguments.length < 3 || enabled ) {
      switch (typeof action) {
        case 'function': 
          a.onclick = action; 
          break;
        case 'string'  : 
          a.href = action; 
          if (target) a.target = target; 
          break
      }
      a.className = 'enabled';
      if (this.defaultAction==null) this.defaultAction=action;
    } else {
      a.disabled = true;
      a.className = 'disabled';
    }
    a.innerHTML = translate ? RicoTranslate.getPhrase(menutext) : menutext;
    if (typeof title=='string')
      a.title = translate ? RicoTranslate.getPhrase(title) : title;
    a=this.div.appendChild(a);
    Event.observe(a,"mouseover", this.mouseOver.bindAsEventListener(this), false);
    Event.observe(a,"mouseout", this.mouseOut.bindAsEventListener(this), false);
    return a;
  },
  
  mouseOver: function(e) {
    if (this.highlightElem && this.highlightElem.className=='enabled-hover') {
      // required for Safari
      this.highlightElem.className='enabled';
      this.highlightElem=null;
    }
    var elem=Event.element(e);
    if (this.openMenuAnchor && this.openMenuAnchor!=elem)
      this.hideSubMenu();
    if (elem.className=='enabled') {
      elem.className='enabled-hover';
      this.highlightElem=elem;
    }
  },

  mouseOut: function(e) {
    var elem=Event.element(e);
    if (elem.className=='enabled-hover') elem.className='enabled';
    if (this.highlightElem==elem) this.highlightElem=null;
  },

  isVisible: function() {
    return this.div && this.div.style.visibility!="hidden";
  },
  
  cancelmenu: function() {
    if (this.hideFunc) this.hideFunc();
    this.hideFunc=null;
    this.hidemenu();
  },

  hidemenu: function() {
    if (!this.div) return;
    this.shim.hide();
    this.shadow.hide();
    if (this.openSubMenu) this.openSubMenu.hidemenu();
    this.div.style.visibility="hidden";
    // make sure it stays out of the way and doesn't cause a scrollbar to appear
    this.div.style.top = '0px';
    this.div.style.left= '0px';
  }

};

Rico.addPreloadMsg('exec: ricoCommon.js');
