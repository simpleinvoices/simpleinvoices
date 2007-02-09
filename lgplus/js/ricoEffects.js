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


if(typeof Rico=='undefined')
  throw("ricoEffects requires the Rico JavaScript framework");

//-------------------- ricoAccordion.js
Rico.Accordion = Class.create();

Rico.Accordion.prototype = {

   initialize: function(container, options) {
      this.container            = $(container);
      if (!this.container) return;
      this.lastExpandedTab      = null;
      this.accordionTabs        = new Array();
      this.setOptions(options);
      this._attachBehaviors();
      if (this.accordionTabs.length==0) return;

      if (this.options.tabLocation == 'inline')
         this.container.style.borderBottom = '1px solid ' + this.options.borderColor;

      // validate onloadShowTab
       if (this.options.onLoadShowTab >= this.accordionTabs.length)
        this.options.onLoadShowTab = 0;

      // set the initial visual state...
      for ( var i=0 ; i < this.accordionTabs.length ; i++ )
      {
        if (i != this.options.onLoadShowTab){
         this.accordionTabs[i].collapse();
         this.accordionTabs[i].content.style.display = 'none';
        }
      }
      this.lastExpandedTab = this.accordionTabs[this.options.onLoadShowTab];
      if (this.options.panelHeight == 'auto'){
          var tabToCheck = (this.options.onloadShowTab === 0)? 1 : 0;
          var titleBarSize = parseInt(Element.getStyle(this.accordionTabs[tabToCheck].titleBar, 'height'));
          if (isNaN(titleBarSize))
            titleBarSize = this.accordionTabs[tabToCheck].titleBar.offsetHeight;

          var totalTitleBarSize = this.accordionTabs.length * titleBarSize;
          var parentHeight = parseInt(Element.getStyle(this.container.parentNode, 'height'));
          if (isNaN(parentHeight))
            parentHeight = this.container.parentNode.offsetHeight;

          this.options.panelHeight = parentHeight - totalTitleBarSize-2;
      }

      this.lastExpandedTab.content.style.height = this.options.panelHeight + "px";
      this.lastExpandedTab.showExpanded();
      this.lastExpandedTab.titleBar.style.fontWeight = this.options.expandedFontWeight;

   },

   setOptions: function(options) {
      this.options = {
         expandedBg          : '#63699c',
         hoverBg             : '#63699c',
         collapsedBg         : '#6b79a5',
         expandedTextColor   : '#ffffff',
         expandedFontWeight  : 'bold',
         hoverTextColor      : '#ffffff',
         collapsedTextColor  : '#ced7ef',
         collapsedFontWeight : 'normal',
         hoverTextColor      : '#ffffff',
         borderColor         : '#1f669b',
         panelHeight         : 200,
         onHideTab           : null,
         onShowTab           : null,
         onLoadShowTab       : 0,
         tabLocation         : 'inline',
         tabSpacing          : '1'
      }
      Object.extend(this.options, options || {});
   },

   showTabByIndex: function( anIndex, animate ) {
      var doAnimate = arguments.length == 1 ? true : animate;
      this.showTab( this.accordionTabs[anIndex], doAnimate );
   },

   showTab: function( accordionTab, animate ) {
     if ( this.lastExpandedTab == accordionTab )
        return;

      var doAnimate = (this.options.tabLocation == 'inline') && (arguments.length == 1 ? true : animate);

      if ( this.options.onHideTab )
         this.options.onHideTab(this.lastExpandedTab);

      this.lastExpandedTab.showCollapsed();
      var accordion = this;
      var lastExpandedTab = this.lastExpandedTab;

      this.lastExpandedTab.content.style.height = (this.options.panelHeight - 1) + 'px';
      accordionTab.content.style.display = '';

      accordionTab.titleBar.style.fontWeight = this.options.expandedFontWeight;

      if ( doAnimate ) {
         new Rico.Effect.AccordionSize( this.lastExpandedTab.content,
                                   accordionTab.content,
                                   1,
                                   this.options.panelHeight,
                                   100, 10,
                                   { complete: function() {accordion.showTabDone(lastExpandedTab)} } );
         this.lastExpandedTab = accordionTab;
      }
      else {
         this.lastExpandedTab.content.style.height = "1px";
         accordionTab.content.style.height = this.options.panelHeight + "px";
         this.lastExpandedTab = accordionTab;
         this.showTabDone(lastExpandedTab);
      }
   },

   showTabDone: function(collapsedTab) {
      collapsedTab.content.style.display = 'none';
      this.lastExpandedTab.showExpanded();
      if ( this.options.onShowTab )
         this.options.onShowTab(this.lastExpandedTab);
   },

   _attachBehaviors: function() {
      var panels = RicoUtil.getDirectChildrenByTag(this.container, 'div');
      var viewport = null;
      var tabs = null;
      if (this.options.tabLocation != 'inline') {
         viewport = document.createElement('div');
         this.container.insertBefore(viewport, this.container.firstChild);
         tabs = document.createElement('div');
         tabs.style.overflow = "auto";
         tabs.style.display = "block";
         if (this.options.tabLocation == 'top') {
            this.container.insertBefore(tabs, this.container.firstChild);
         } else if (this.options.tabLocation == 'bottom') {
            this.container.appendChild(tabs);
         }
         this.container.style.overflow='hidden';
         viewport.style.clear = "both";  // required by IE
         viewport.style.border = "1px solid " + this.options.borderColor;
      }
      for ( var i=0; i < panels.length; i++ ) {

         var tabChildren = RicoUtil.getDirectChildrenByTag(panels[i],'div');
         if ( tabChildren.length != 2 ) {
            alert('Warning: panel '+i+' has '+tabChildren.length+' children (expected 2');
            continue; // unexpected
         }

         var tabTitleBar   = tabChildren[0];
         var tabContentBox = tabChildren[1];
         if (this.options.tabLocation != 'inline') {
            tabContentBox.style.clear = 'left';
            tabContentBox.style.overflow = 'hidden';
            var padl=Element.getStyle(tabContentBox, 'padding-left');
            var padr=Element.getStyle(tabContentBox, 'padding-right')
            // -------------------------------------------
            // Matt Brown, June 2006
            // switched from clientWidth to offsetWidth
            // because clientWidth was returning 0 in IE6 standards mode
            // -------------------------------------------
            var newWi=this.container.offsetWidth - RicoUtil.nan2zero(parseInt(padl)) - RicoUtil.nan2zero(parseInt(padr)) - 2;
            //alert('_attachBehaviors panel #'+i+'\noffsetWidth='+this.container.offsetWidth+'\npadl='+padl+' padr='+padr+' newWi='+newWi);
            tabContentBox.style.width = newWi + 'px';
            viewport.appendChild(tabContentBox);
            tabTitleBar.style.paddingLeft = '0px';
            tabTitleBar.style.paddingRight = '0px';
            if (this.options.tabLocation == 'top')
              tabTitleBar.style.marginTop = '-5px';
            if (this.options.tabLocation == 'bottom')
              tabTitleBar.style.marginBottom = '-5px';
            var tabWrapper = document.createElement('div');
            tabWrapper.appendChild(tabTitleBar);
            if (Rico.isIE)
              tabWrapper.style.styleFloat = 'left';
            else
              tabWrapper.style.cssFloat = 'left';
            tabWrapper.style.display = 'inline';
            tabWrapper.style.textAlign = 'center';
            tabWrapper.style.whiteSpace = 'nowrap';
            if (i > 0) tabWrapper.style.marginLeft = this.options.tabSpacing + 'px';
            tabWrapper.style.width =
                (Math.floor(this.container.offsetWidth / panels.length) -
                ((i > 0) ? this.options.tabSpacing : 0)) + 'px';
            tabs.appendChild(tabWrapper);
            Rico.Corner.round(tabWrapper, {corners:this.options.tabLocation,color:'transparent'});
            this.accordionTabs.push( new Rico.Accordion.Tab(this,tabWrapper,tabContentBox) );
         }
         else this.accordionTabs.push( new Rico.Accordion.Tab(this,tabTitleBar,tabContentBox) );
      }
   }

};

Rico.Accordion.Tab = Class.create();

Rico.Accordion.Tab.prototype = {

   initialize: function(accordion, titleBar, content) {
      this.accordion = accordion;
      this.titleBar  = titleBar;
      this.content   = content;
      this._attachBehaviors();
   },

   collapse: function() {
      this.showCollapsed();
      this.content.style.height = "1px";
   },

   showCollapsed: function() {
      this.expanded = false;
      this.titleBar.style.backgroundColor = this.accordion.options.collapsedBg;
      this.titleBar.style.color           = this.accordion.options.collapsedTextColor;
      this.titleBar.style.fontWeight      = this.accordion.options.collapsedFontWeight;
      this.content.style.overflow = "hidden";
   },

   showExpanded: function() {
      this.expanded = true;
      this.titleBar.style.backgroundColor = this.accordion.options.expandedBg;
      this.titleBar.style.color           = this.accordion.options.expandedTextColor;
      this.content.style.overflow         = "auto";
   },

   titleBarClicked: function(e) {
      if ( this.accordion.lastExpandedTab == this )
         return;
      this.accordion.showTab(this);
   },

   hover: function(e) {
		this.titleBar.style.backgroundColor = this.accordion.options.hoverBg;
		this.titleBar.style.color           = this.accordion.options.hoverTextColor;
   },

   unhover: function(e) {
      if ( this.expanded ) {
         this.titleBar.style.backgroundColor = this.accordion.options.expandedBg;
         this.titleBar.style.color           = this.accordion.options.expandedTextColor;
      }
      else {
         this.titleBar.style.backgroundColor = this.accordion.options.collapsedBg;
         this.titleBar.style.color           = this.accordion.options.collapsedTextColor;
      }
   },

   _attachBehaviors: function() {
      if (this.accordion.options.tabLocation == 'inline')
         this.content.style.border = "1px solid " + this.accordion.options.borderColor;
      this.content.style.borderTopWidth    = "0px";
      this.content.style.borderBottomWidth = "0px";
      this.content.style.margin            = "0px";

      Event.observe(this.titleBar , "click",this.titleBarClicked.bindAsEventListener(this));
      Event.observe(this.titleBar , "mouseover",this.hover.bindAsEventListener(this));
      Event.observe(this.titleBar , "mouseout",this.unhover.bindAsEventListener(this));
   }

};



//-------------------- ricoColor.js
Rico.Color = Class.create();

Rico.Color.prototype = {

   initialize: function(red, green, blue) {
      this.rgb = { r: red, g : green, b : blue };
   },

   setRed: function(r) {
      this.rgb.r = r;
   },

   setGreen: function(g) {
      this.rgb.g = g;
   },

   setBlue: function(b) {
      this.rgb.b = b;
   },

   setHue: function(h) {

      // get an HSB model, and set the new hue...
      var hsb = this.asHSB();
      hsb.h = h;

      // convert back to RGB...
      this.rgb = Rico.Color.HSBtoRGB(hsb.h, hsb.s, hsb.b);
   },

   setSaturation: function(s) {
      // get an HSB model, and set the new hue...
      var hsb = this.asHSB();
      hsb.s = s;

      // convert back to RGB and set values...
      this.rgb = Rico.Color.HSBtoRGB(hsb.h, hsb.s, hsb.b);
   },

   setBrightness: function(b) {
      // get an HSB model, and set the new hue...
      var hsb = this.asHSB();
      hsb.b = b;

      // convert back to RGB and set values...
      this.rgb = Rico.Color.HSBtoRGB( hsb.h, hsb.s, hsb.b );
   },

   darken: function(percent) {
      var hsb  = this.asHSB();
      this.rgb = Rico.Color.HSBtoRGB(hsb.h, hsb.s, Math.max(hsb.b - percent,0));
   },

   brighten: function(percent) {
      var hsb  = this.asHSB();
      this.rgb = Rico.Color.HSBtoRGB(hsb.h, hsb.s, Math.min(hsb.b + percent,1));
   },

   blend: function(other) {
      this.rgb.r = Math.floor((this.rgb.r + other.rgb.r)/2);
      this.rgb.g = Math.floor((this.rgb.g + other.rgb.g)/2);
      this.rgb.b = Math.floor((this.rgb.b + other.rgb.b)/2);
   },

   isBright: function() {
      var hsb = this.asHSB();
      return this.asHSB().b > 0.5;
   },

   isDark: function() {
      return ! this.isBright();
   },

   asRGB: function() {
      return "rgb(" + this.rgb.r + "," + this.rgb.g + "," + this.rgb.b + ")";
   },

   asHex: function() {
      return "#" + this.rgb.r.toColorPart() + this.rgb.g.toColorPart() + this.rgb.b.toColorPart();
   },

   asHSB: function() {
      return Rico.Color.RGBtoHSB(this.rgb.r, this.rgb.g, this.rgb.b);
   },

   toString: function() {
      return this.asHex();
   }

};

Rico.Color.createFromHex = function(hexCode) {
  if(hexCode.length==4) {
    var shortHexCode = hexCode;
    var hexCode = '#';
    for(var i=1;i<4;i++) hexCode += (shortHexCode.charAt(i) +
shortHexCode.charAt(i));
  }
   if ( hexCode.indexOf('#') == 0 )
      hexCode = hexCode.substring(1);
   var red   = hexCode.substring(0,2);
   var green = hexCode.substring(2,4);
   var blue  = hexCode.substring(4,6);
   return new Rico.Color( parseInt(red,16), parseInt(green,16), parseInt(blue,16) );
}

/**
 * Factory method for creating a color from the background of
 * an HTML element.
 */
Rico.Color.createColorFromBackground = function(elem) {

   var actualColor = Element.getStyle($(elem), "background-color");

   if ( actualColor == "transparent" && elem.parentNode )
      return Rico.Color.createColorFromBackground(elem.parentNode);

   if ( actualColor == null )
      return new Rico.Color(255,255,255);

   if ( actualColor.indexOf("rgb(") == 0 ) {
      var colors = actualColor.substring(4, actualColor.length - 1 );
      var colorArray = colors.split(",");
      return new Rico.Color( parseInt( colorArray[0] ),
                            parseInt( colorArray[1] ),
                            parseInt( colorArray[2] )  );

   }
   else if ( actualColor.indexOf("#") == 0 ) {
      return Rico.Color.createFromHex(actualColor);
   }
   else
      return new Rico.Color(255,255,255);
}

Rico.Color.HSBtoRGB = function(hue, saturation, brightness) {

   var red   = 0;
	var green = 0;
	var blue  = 0;

   if (saturation == 0) {
      red = parseInt(brightness * 255.0 + 0.5);
	   green = red;
	   blue = red;
	}
	else {
      var h = (hue - Math.floor(hue)) * 6.0;
      var f = h - Math.floor(h);
      var p = brightness * (1.0 - saturation);
      var q = brightness * (1.0 - saturation * f);
      var t = brightness * (1.0 - (saturation * (1.0 - f)));

      switch (parseInt(h)) {
         case 0:
            red   = (brightness * 255.0 + 0.5);
            green = (t * 255.0 + 0.5);
            blue  = (p * 255.0 + 0.5);
            break;
         case 1:
            red   = (q * 255.0 + 0.5);
            green = (brightness * 255.0 + 0.5);
            blue  = (p * 255.0 + 0.5);
            break;
         case 2:
            red   = (p * 255.0 + 0.5);
            green = (brightness * 255.0 + 0.5);
            blue  = (t * 255.0 + 0.5);
            break;
         case 3:
            red   = (p * 255.0 + 0.5);
            green = (q * 255.0 + 0.5);
            blue  = (brightness * 255.0 + 0.5);
            break;
         case 4:
            red   = (t * 255.0 + 0.5);
            green = (p * 255.0 + 0.5);
            blue  = (brightness * 255.0 + 0.5);
            break;
          case 5:
            red   = (brightness * 255.0 + 0.5);
            green = (p * 255.0 + 0.5);
            blue  = (q * 255.0 + 0.5);
            break;
	    }
	}

   return { r : parseInt(red), g : parseInt(green) , b : parseInt(blue) };
}

Rico.Color.RGBtoHSB = function(r, g, b) {

   var hue;
   var saturation;
   var brightness;

   var cmax = (r > g) ? r : g;
   if (b > cmax)
      cmax = b;

   var cmin = (r < g) ? r : g;
   if (b < cmin)
      cmin = b;

   brightness = cmax / 255.0;
   if (cmax != 0)
      saturation = (cmax - cmin)/cmax;
   else
      saturation = 0;

   if (saturation == 0)
      hue = 0;
   else {
      var redc   = (cmax - r)/(cmax - cmin);
    	var greenc = (cmax - g)/(cmax - cmin);
    	var bluec  = (cmax - b)/(cmax - cmin);

    	if (r == cmax)
    	   hue = bluec - greenc;
    	else if (g == cmax)
    	   hue = 2.0 + redc - bluec;
      else
    	   hue = 4.0 + greenc - redc;

    	hue = hue / 6.0;
    	if (hue < 0)
    	   hue = hue + 1.0;
   }

   return { h : hue, s : saturation, b : brightness };
}


//-------------------- ricoCorner.js
Rico.Corner = {

   round: function(e, options) {
      var e = $(e);
      this._setOptions(options);

      var color = this.options.color;
      if ( this.options.color == "fromElement" )
         color = this._background(e);

      var bgColor = this.options.bgColor;
      if ( this.options.bgColor == "fromParent" )
         bgColor = this._background(e.offsetParent);

      this._roundCornersImpl(e, color, bgColor);
   },

   _roundCornersImpl: function(e, color, bgColor) {
      if(this.options.border)
         this._renderBorder(e,bgColor);
      if(this._isTopRounded())
         this._roundTopCorners(e,color,bgColor);
      if(this._isBottomRounded())
         this._roundBottomCorners(e,color,bgColor);
   },

   _renderBorder: function(el,bgColor) {
      var borderValue = "1px solid " + this._borderColor(bgColor);
      var borderL = "border-left: "  + borderValue;
      var borderR = "border-right: " + borderValue;
      var style   = "style='" + borderL + ";" + borderR +  "'";
      el.innerHTML = "<div " + style + ">" + el.innerHTML + "</div>"
   },

   _roundTopCorners: function(el, color, bgColor) {
      var corner = this._createCorner(bgColor);
      for(var i=0 ; i < this.options.numSlices ; i++ )
         corner.appendChild(this._createCornerSlice(color,bgColor,i,"top"));
      el.style.paddingTop = '0px';
      el.insertBefore(corner,el.firstChild);
   },

   _roundBottomCorners: function(el, color, bgColor) {
      var corner = this._createCorner(bgColor);
      for(var i=(this.options.numSlices-1) ; i >= 0 ; i-- )
         corner.appendChild(this._createCornerSlice(color,bgColor,i,"bottom"));
      el.style.paddingBottom = 0;
      el.appendChild(corner);
   },

   _createCorner: function(bgColor) {
      var corner = document.createElement("div");
      corner.style.backgroundColor = (this._isTransparent() ? "transparent" : bgColor);
      return corner;
   },

   _createCornerSlice: function(color,bgColor, n, position) {
      var slice = document.createElement("span");

      var inStyle = slice.style;
      inStyle.backgroundColor = color;
      inStyle.display  = "block";
      inStyle.height   = "1px";
      inStyle.overflow = "hidden";
      inStyle.fontSize = "1px";

      var borderColor = this._borderColor(color,bgColor);
      if ( this.options.border && n == 0 ) {
         inStyle.borderTopStyle    = "solid";
         inStyle.borderTopWidth    = "1px";
         inStyle.borderLeftWidth   = "0px";
         inStyle.borderRightWidth  = "0px";
         inStyle.borderBottomWidth = "0px";
         inStyle.height            = "0px"; // assumes css compliant box model
         inStyle.borderColor       = borderColor;
      }
      else if(borderColor) {
         inStyle.borderColor = borderColor;
         inStyle.borderStyle = "solid";
         inStyle.borderWidth = "0px 1px";
      }

      if ( !this.options.compact && (n == (this.options.numSlices-1)) )
         inStyle.height = "2px";

      this._setMargin(slice, n, position);
      this._setBorder(slice, n, position);
      return slice;
   },

   _setOptions: function(options) {
      this.options = {
         corners : "all",
         color   : "fromElement",
         bgColor : "fromParent",
         blend   : true,
         border  : false,
         compact : false
      }
      Object.extend(this.options, options || {});

      this.options.numSlices = this.options.compact ? 2 : 4;
      if ( this._isTransparent() )
         this.options.blend = false;
   },

   _whichSideTop: function() {
      if ( this._hasString(this.options.corners, "all", "top") )
         return "";

      if ( this.options.corners.indexOf("tl") >= 0 && this.options.corners.indexOf("tr") >= 0 )
         return "";

      if (this.options.corners.indexOf("tl") >= 0)
         return "left";
      else if (this.options.corners.indexOf("tr") >= 0)
          return "right";
      return "";
   },

   _whichSideBottom: function() {
      if ( this._hasString(this.options.corners, "all", "bottom") )
         return "";

      if ( this.options.corners.indexOf("bl")>=0 && this.options.corners.indexOf("br")>=0 )
         return "";

      if(this.options.corners.indexOf("bl") >=0)
         return "left";
      else if(this.options.corners.indexOf("br")>=0)
         return "right";
      return "";
   },

   _borderColor : function(color,bgColor) {
      if ( color == "transparent" )
         return bgColor;
      else if ( this.options.border )
         return this.options.border;
      else if ( this.options.blend )
         return this._blend( bgColor, color );
      else
         return "";
   },


   _setMargin: function(el, n, corners) {
      var marginSize = this._marginSize(n);
      var whichSide = corners == "top" ? this._whichSideTop() : this._whichSideBottom();

      if ( whichSide == "left" ) {
         el.style.marginLeft = marginSize + "px"; el.style.marginRight = "0px";
      }
      else if ( whichSide == "right" ) {
         el.style.marginRight = marginSize + "px"; el.style.marginLeft  = "0px";
      }
      else {
         el.style.marginLeft = marginSize + "px"; el.style.marginRight = marginSize + "px";
      }
   },

   _setBorder: function(el,n,corners) {
      var borderSize = this._borderSize(n);
      var whichSide = corners == "top" ? this._whichSideTop() : this._whichSideBottom();
      if ( whichSide == "left" ) {
         el.style.borderLeftWidth = borderSize + "px"; el.style.borderRightWidth = "0px";
      }
      else if ( whichSide == "right" ) {
         el.style.borderRightWidth = borderSize + "px"; el.style.borderLeftWidth  = "0px";
      }
      else {
         el.style.borderLeftWidth = borderSize + "px"; el.style.borderRightWidth = borderSize + "px";
      }
      if (this.options.border != false)
        el.style.borderLeftWidth = borderSize + "px"; el.style.borderRightWidth = borderSize + "px";
   },

   _marginSize: function(n) {
      if ( this._isTransparent() )
         return 0;

      var marginSizes          = [ 5, 3, 2, 1 ];
      var blendedMarginSizes   = [ 3, 2, 1, 0 ];
      var compactMarginSizes   = [ 2, 1 ];
      var smBlendedMarginSizes = [ 1, 0 ];

      if ( this.options.compact && this.options.blend )
         return smBlendedMarginSizes[n];
      else if ( this.options.compact )
         return compactMarginSizes[n];
      else if ( this.options.blend )
         return blendedMarginSizes[n];
      else
         return marginSizes[n];
   },

   _borderSize: function(n) {
      var transparentBorderSizes = [ 5, 3, 2, 1 ];
      var blendedBorderSizes     = [ 2, 1, 1, 1 ];
      var compactBorderSizes     = [ 1, 0 ];
      var actualBorderSizes      = [ 0, 2, 0, 0 ];

      if ( this.options.compact && (this.options.blend || this._isTransparent()) )
         return 1;
      else if ( this.options.compact )
         return compactBorderSizes[n];
      else if ( this.options.blend )
         return blendedBorderSizes[n];
      else if ( this.options.border )
         return actualBorderSizes[n];
      else if ( this._isTransparent() )
         return transparentBorderSizes[n];
      return 0;
   },

   _hasString: function(str) { for(var i=1 ; i<arguments.length ; i++) if (str.indexOf(arguments[i]) >= 0) return true; return false; },
   _blend: function(c1, c2) { var cc1 = Rico.Color.createFromHex(c1); cc1.blend(Rico.Color.createFromHex(c2)); return cc1; },
   _background: function(el) { try { return Rico.Color.createColorFromBackground(el).asHex(); } catch(err) { return "#ffffff"; } },
   _isTransparent: function() { return this.options.color == "transparent"; },
   _isTopRounded: function() { return this._hasString(this.options.corners, "all", "top", "tl", "tr"); },
   _isBottomRounded: function() { return this._hasString(this.options.corners, "all", "bottom", "bl", "br"); },
   _hasSingleTextChild: function(el) { return el.childNodes.length == 1 && el.childNodes[0].nodeType == 3; }
}


//-------------------- ricoDragAndDrop.js
Rico.DragAndDrop = Class.create();

Rico.DragAndDrop.prototype = {

   initialize: function() {
      this.dropZones                = new Array();
      this.draggables               = new Array();
      this.currentDragObjects       = new Array();
      this.dragElement              = null;
      this.lastSelectedDraggable    = null;
      this.currentDragObjectVisible = false;
      this.interestedInMotionEvents = false;
      this._mouseDown = this._mouseDownHandler.bindAsEventListener(this);
      this._mouseMove = this._mouseMoveHandler.bindAsEventListener(this);
      this._mouseUp = this._mouseUpHandler.bindAsEventListener(this);
   },

   registerDropZone: function(aDropZone) {
      this.dropZones[ this.dropZones.length ] = aDropZone;
   },

   deregisterDropZone: function(aDropZone) {
      var newDropZones = new Array();
      var j = 0;
      for ( var i = 0 ; i < this.dropZones.length ; i++ ) {
         if ( this.dropZones[i] != aDropZone )
            newDropZones[j++] = this.dropZones[i];
      }

      this.dropZones = newDropZones;
   },

   clearDropZones: function() {
      this.dropZones = new Array();
   },

   registerDraggable: function( aDraggable ) {
      this.draggables[ this.draggables.length ] = aDraggable;
      this._addMouseDownHandler( aDraggable );
   },

   clearSelection: function() {
      for ( var i = 0 ; i < this.currentDragObjects.length ; i++ )
         this.currentDragObjects[i].deselect();
      this.currentDragObjects = new Array();
      this.lastSelectedDraggable = null;
   },

   hasSelection: function() {
      return this.currentDragObjects.length > 0;
   },

   setStartDragFromElement: function( e, mouseDownElement ) {
      this.origPos = Position.page(mouseDownElement);
      this.startx = e.screenX - this.origPos[0]
      this.starty = e.screenY - this.origPos[1]
      //this.startComponentX = e.layerX ? e.layerX : e.offsetX;
      //this.startComponentY = e.layerY ? e.layerY : e.offsetY;
      //this.adjustedForDraggableSize = false;

      this.interestedInMotionEvents = this.hasSelection();
      this._terminateEvent(e);
   },

   updateSelection: function( draggable, extendSelection ) {
      if ( ! extendSelection )
         this.clearSelection();

      if ( draggable.isSelected() ) {
         this.currentDragObjects.removeItem(draggable);
         draggable.deselect();
         if ( draggable == this.lastSelectedDraggable )
            this.lastSelectedDraggable = null;
      }
      else {
         this.currentDragObjects[ this.currentDragObjects.length ] = draggable;
         draggable.select();
         this.lastSelectedDraggable = draggable;
      }
   },

   _mouseDownHandler: function(e) {
      if ( arguments.length == 0 )
         e = event;

      // if not button 1 ignore it...
      var nsEvent = e.which != undefined;
      if ( (nsEvent && e.which != 1) || (!nsEvent && e.button != 1))
         return;

      var eventTarget      = e.target ? e.target : e.srcElement;
      var draggableObject  = eventTarget.draggable;

      var candidate = eventTarget;
      while (draggableObject == null && candidate.parentNode) {
         candidate = candidate.parentNode;
         draggableObject = candidate.draggable;
      }

      if ( draggableObject == null )
         return;

      this.updateSelection( draggableObject, e.ctrlKey );

      // clear the drop zones postion cache...
      if ( this.hasSelection() )
         for ( var i = 0 ; i < this.dropZones.length ; i++ )
            this.dropZones[i].clearPositionCache();

      this.setStartDragFromElement( e, draggableObject.getMouseDownHTMLElement() );
   },


   _mouseMoveHandler: function(e) {
      var nsEvent = e.which != undefined;
      if ( !this.interestedInMotionEvents ) {
         //this._terminateEvent(e);
         return;
      }

      if ( ! this.hasSelection() )
         return;

      if ( ! this.currentDragObjectVisible )
         this._startDrag(e);

      if ( !this.activatedDropZones )
         this._activateRegisteredDropZones();

      //if ( !this.adjustedForDraggableSize )
      //   this._adjustForDraggableSize(e);

      this._updateDraggableLocation(e);
      this._updateDropZonesHover(e);

      this._terminateEvent(e);
   },

   _makeDraggableObjectVisible: function(e)
   {
      if ( !this.hasSelection() )
         return;

      var dragElement;
      if ( this.currentDragObjects.length > 1 )
         dragElement = this.currentDragObjects[0].getMultiObjectDragGUI(this.currentDragObjects);
      else
         dragElement = this.currentDragObjects[0].getSingleObjectDragGUI();

      // go ahead and absolute position it...
      if ( Element.getStyle(dragElement, "position")  != "absolute" )
         dragElement.style.position = "absolute";

      // need to parent him into the document...
      if ( dragElement.parentNode == null || dragElement.parentNode.nodeType == 11 )
         document.body.appendChild(dragElement);

      this.dragElement = dragElement;
      this._updateDraggableLocation(e);

      this.currentDragObjectVisible = true;
   },

   /**
   _adjustForDraggableSize: function(e) {
      var dragElementWidth  = this.dragElement.offsetWidth;
      var dragElementHeight = this.dragElement.offsetHeight;
      if ( this.startComponentX > dragElementWidth )
         this.startx -= this.startComponentX - dragElementWidth + 2;
      if ( e.offsetY ) {
         if ( this.startComponentY > dragElementHeight )
            this.starty -= this.startComponentY - dragElementHeight + 2;
      }
      this.adjustedForDraggableSize = true;
   },
   **/

   _leftOffset: function(e) {
	   return e.offsetX ? document.body.scrollLeft : 0
	},

   _topOffset: function(e) {
	   return e.offsetY ? document.body.scrollTop:0
	},


   _updateDraggableLocation: function(e) {
      var dragObjectStyle = this.dragElement.style;
      dragObjectStyle.left = (e.screenX + this._leftOffset(e) - this.startx) + "px"
      dragObjectStyle.top  = (e.screenY + this._topOffset(e) - this.starty) + "px";
   },

   _updateDropZonesHover: function(e) {
      var n = this.dropZones.length;
      for ( var i = 0 ; i < n ; i++ ) {
         if ( ! this._mousePointInDropZone( e, this.dropZones[i] ) )
            this.dropZones[i].hideHover();
      }

      for ( var i = 0 ; i < n ; i++ ) {
         if ( this._mousePointInDropZone( e, this.dropZones[i] ) ) {
            if ( this.dropZones[i].canAccept(this.currentDragObjects) )
               this.dropZones[i].showHover();
         }
      }
   },

   _startDrag: function(e) {
      for ( var i = 0 ; i < this.currentDragObjects.length ; i++ )
         this.currentDragObjects[i].startDrag();

      this._makeDraggableObjectVisible(e);
   },

   _mouseUpHandler: function(e) {
      if ( ! this.hasSelection() )
         return;

      var nsEvent = e.which != undefined;
      if ( (nsEvent && e.which != 1) || (!nsEvent && e.button != 1))
         return;

      this.interestedInMotionEvents = false;

      if ( this.dragElement == null ) {
         this._terminateEvent(e);
         return;
      }

      if ( this._placeDraggableInDropZone(e) )
         this._completeDropOperation(e);
      else {
         this._terminateEvent(e);
         new Rico.Effect.Position( this.dragElement,
                              this.origPos[0],
                              this.origPos[1],
                              200,
                              20,
                              { complete : this._doCancelDragProcessing.bind(this) } );
      }

     Event.stopObserving(document.body, "mousemove", this._mouseMove);
     Event.stopObserving(document.body, "mouseup",  this._mouseUp);
   },

   _retTrue: function () {
      return true;
   },

   _completeDropOperation: function(e) {
      if ( this.dragElement != this.currentDragObjects[0].getMouseDownHTMLElement() ) {
         if ( this.dragElement.parentNode != null )
            this.dragElement.parentNode.removeChild(this.dragElement);
      }

      this._deactivateRegisteredDropZones();
      this._endDrag();
      this.clearSelection();
      this.dragElement = null;
      this.currentDragObjectVisible = false;
      this._terminateEvent(e);
   },

   _doCancelDragProcessing: function() {
      this._cancelDrag();

        if ( this.dragElement != this.currentDragObjects[0].getMouseDownHTMLElement() && this.dragElement)
           if ( this.dragElement.parentNode != null )
              this.dragElement.parentNode.removeChild(this.dragElement);


      this._deactivateRegisteredDropZones();
      this.dragElement = null;
      this.currentDragObjectVisible = false;
   },

   _placeDraggableInDropZone: function(e) {
      var foundDropZone = false;
      var n = this.dropZones.length;
      for ( var i = 0 ; i < n ; i++ ) {
         if ( this._mousePointInDropZone( e, this.dropZones[i] ) ) {
            if ( this.dropZones[i].canAccept(this.currentDragObjects) ) {
               this.dropZones[i].hideHover();
               this.dropZones[i].accept(this.currentDragObjects);
               foundDropZone = true;
               break;
            }
         }
      }

      return foundDropZone;
   },

   _cancelDrag: function() {
      for ( var i = 0 ; i < this.currentDragObjects.length ; i++ )
         this.currentDragObjects[i].cancelDrag();
   },

   _endDrag: function() {
      for ( var i = 0 ; i < this.currentDragObjects.length ; i++ )
         this.currentDragObjects[i].endDrag();
   },

   _mousePointInDropZone: function( e, dropZone ) {

      var absoluteRect = dropZone.getAbsoluteRect();

      return e.clientX  > absoluteRect.left + this._leftOffset(e) &&
             e.clientX  < absoluteRect.right + this._leftOffset(e) &&
             e.clientY  > absoluteRect.top + this._topOffset(e)   &&
             e.clientY  < absoluteRect.bottom + this._topOffset(e);
   },

   _addMouseDownHandler: function( aDraggable )
   {
       htmlElement  = aDraggable.getMouseDownHTMLElement();
      if ( htmlElement  != null ) {
         htmlElement.draggable = aDraggable;
         Event.observe(htmlElement , "mousedown", this._onmousedown.bindAsEventListener(this));
         Event.observe(htmlElement, "mousedown", this._mouseDown);
      }
   },

   _activateRegisteredDropZones: function() {
      var n = this.dropZones.length;
      for ( var i = 0 ; i < n ; i++ ) {
         var dropZone = this.dropZones[i];
         if ( dropZone.canAccept(this.currentDragObjects) )
            dropZone.activate();
      }

      this.activatedDropZones = true;
   },

   _deactivateRegisteredDropZones: function() {
      var n = this.dropZones.length;
      for ( var i = 0 ; i < n ; i++ )
         this.dropZones[i].deactivate();
      this.activatedDropZones = false;
   },

   _onmousedown: function () {
     Event.observe(document.body, "mousemove", this._mouseMove);
     Event.observe(document.body, "mouseup",  this._mouseUp);
   },

   _terminateEvent: function(e) {
      Event.stop(e);
   },


   initializeEventHandlers: function() {
      Event.observe(document , "mouseup",this._mouseUpHandler.bindAsEventListener(this));
      Event.observe(document , "mousemove",this._mouseMoveHandler.bindAsEventListener(this));
   }
	}

	var dndMgr = new Rico.DragAndDrop();
	dndMgr.initializeEventHandlers();


//-------------------- ricoDraggable.js
Rico.Draggable = Class.create();

Rico.Draggable.prototype = {

   initialize: function( type, htmlElement ) {
      this.type          = type;
      this.htmlElement   = $(htmlElement);
      this.selected      = false;
   },

   /**
    *   Returns the HTML element that should have a mouse down event
    *   added to it in order to initiate a drag operation
    *
    **/
   getMouseDownHTMLElement: function() {
      return this.htmlElement;
   },

   select: function() {
      this.selected = true;

      if ( this.showingSelected )
         return;

      var htmlElement = this.getMouseDownHTMLElement();

      var color = Rico.Color.createColorFromBackground(htmlElement);
      color.isBright() ? color.darken(0.033) : color.brighten(0.033);

      this.saveBackground = Element.getStyle(htmlElement, "background-color");
      htmlElement.style.backgroundColor = color.asHex();
      this.showingSelected = true;
   },

   deselect: function() {
      this.selected = false;
      if ( !this.showingSelected )
         return;

      var htmlElement = this.getMouseDownHTMLElement();

      htmlElement.style.backgroundColor = this.saveBackground;
      this.showingSelected = false;
   },

   isSelected: function() {
      return this.selected;
   },

   startDrag: function() {
   },

   cancelDrag: function() {
   },

   endDrag: function() {
   },

   getSingleObjectDragGUI: function() {
      return this.htmlElement;
   },

   getMultiObjectDragGUI: function( draggables ) {
      return this.htmlElement;
   },

   getDroppedGUI: function() {
      return this.htmlElement;
   },

   toString: function() {
      return this.type + ":" + this.htmlElement + ":";
   }

}


//-------------------- ricoDropzone.js
Rico.Dropzone = Class.create();

Rico.Dropzone.prototype = {

   initialize: function( htmlElement ) {
      this.htmlElement  = $(htmlElement);
      this.absoluteRect = null;
   },

   getHTMLElement: function() {
      return this.htmlElement;
   },

   clearPositionCache: function() {
      this.absoluteRect = null;
   },

   getAbsoluteRect: function() {
      if ( this.absoluteRect == null ) {
         var htmlElement = this.getHTMLElement();
         var pos = Position.page(htmlElement);

         this.absoluteRect = {
            top:    pos[1],
            left:   pos[0],
            bottom: pos[1] + htmlElement.offsetHeight,
            right:  pos[0] + htmlElement.offsetWidth
         };
      }
      return this.absoluteRect;
   },

   activate: function() {
      var htmlElement = this.getHTMLElement();
      if (htmlElement == null  || this.showingActive)
         return;

      this.showingActive = true;
      this.saveBackgroundColor = htmlElement.style.backgroundColor;

      var fallbackColor = "#ffea84";
      var currentColor = Rico.Color.createColorFromBackground(htmlElement);
      if ( currentColor == null )
         htmlElement.style.backgroundColor = fallbackColor;
      else {
         currentColor.isBright() ? currentColor.darken(0.2) : currentColor.brighten(0.2);
         htmlElement.style.backgroundColor = currentColor.asHex();
      }
   },

   deactivate: function() {
      var htmlElement = this.getHTMLElement();
      if (htmlElement == null || !this.showingActive)
         return;

      htmlElement.style.backgroundColor = this.saveBackgroundColor;
      this.showingActive = false;
      this.saveBackgroundColor = null;
   },

   showHover: function() {
      var htmlElement = this.getHTMLElement();
      if ( htmlElement == null || this.showingHover )
         return;

      this.saveBorderWidth = htmlElement.style.borderWidth;
      this.saveBorderStyle = htmlElement.style.borderStyle;
      this.saveBorderColor = htmlElement.style.borderColor;

      this.showingHover = true;
      htmlElement.style.borderWidth = "1px";
      htmlElement.style.borderStyle = "solid";
      //htmlElement.style.borderColor = "#ff9900";
      htmlElement.style.borderColor = "#ffff00";
   },

   hideHover: function() {
      var htmlElement = this.getHTMLElement();
      if ( htmlElement == null || !this.showingHover )
         return;

      htmlElement.style.borderWidth = this.saveBorderWidth;
      htmlElement.style.borderStyle = this.saveBorderStyle;
      htmlElement.style.borderColor = this.saveBorderColor;
      this.showingHover = false;
   },

   canAccept: function(draggableObjects) {
      return true;
   },

   accept: function(draggableObjects) {
      var htmlElement = this.getHTMLElement();
      if ( htmlElement == null )
         return;

      n = draggableObjects.length;
      for ( var i = 0 ; i < n ; i++ )
      {
         var theGUI = draggableObjects[i].getDroppedGUI();
         if ( Element.getStyle( theGUI, "position" ) == "absolute" )
         {
            theGUI.style.position = "static";
            theGUI.style.top = "";
            theGUI.style.top = "";
         }
         htmlElement.appendChild(theGUI);
      }
   }
}


//-------------------- ricoEffects.js

Rico.Effect = {};

Rico.Effect.SizeAndPosition = Class.create();
Rico.Effect.SizeAndPosition.prototype = {

   initialize: function(element, x, y, w, h, duration, steps, options) {
      this.element = $(element);
      this.x = x;
      this.y = y;
      this.w = w;
      this.h = h;
      this.duration = duration;
      this.steps    = steps;
      this.options  = arguments[7] || {};

      this.sizeAndPosition();
   },

   sizeAndPosition: function() {
      if (this.isFinished()) {
         if(this.options.complete) this.options.complete(this);
         return;
      }

      if (this.timer)
         clearTimeout(this.timer);

      var stepDuration = Math.round(this.duration/this.steps) ;

      // Get original values: x,y = top left corner;  w,h = width height
      var currentX = this.element.offsetLeft;
      var currentY = this.element.offsetTop;
      var currentW = this.element.offsetWidth;
      var currentH = this.element.offsetHeight;

      // If values not set, or zero, we do not modify them, and take original as final as well
      this.x = (this.x) ? this.x : currentX;
      this.y = (this.y) ? this.y : currentY;
      this.w = (this.w) ? this.w : currentW;
      this.h = (this.h) ? this.h : currentH;

      // how much do we need to modify our values for each step?
      var difX = this.steps >  0 ? (this.x - currentX)/this.steps : 0;
      var difY = this.steps >  0 ? (this.y - currentY)/this.steps : 0;
      var difW = this.steps >  0 ? (this.w - currentW)/this.steps : 0;
      var difH = this.steps >  0 ? (this.h - currentH)/this.steps : 0;

      this.moveBy(difX, difY);
      this.resizeBy(difW, difH);

      this.duration -= stepDuration;
      this.steps--;

      this.timer = setTimeout(this.sizeAndPosition.bind(this), stepDuration);
   },

   isFinished: function() {
      return this.steps <= 0;
   },

   moveBy: function( difX, difY ) {
      var currentLeft = this.element.offsetLeft;
      var currentTop  = this.element.offsetTop;
      var intDifX     = parseInt(difX);
      var intDifY     = parseInt(difY);

      var style = this.element.style;
      if ( intDifX != 0 )
         style.left = (currentLeft + intDifX) + "px";
      if ( intDifY != 0 )
         style.top  = (currentTop + intDifY) + "px";
   },

   resizeBy: function( difW, difH ) {
      var currentWidth  = this.element.offsetWidth;
      var currentHeight = this.element.offsetHeight;
      var intDifW       = parseInt(difW);
      var intDifH       = parseInt(difH);

      var style = this.element.style;
      if ( intDifW != 0 )
         style.width   = (currentWidth  + intDifW) + "px";
      if ( intDifH != 0 )
         style.height  = (currentHeight + intDifH) + "px";
   }
}

Rico.Effect.Size = Class.create();
Rico.Effect.Size.prototype = {

   initialize: function(element, w, h, duration, steps, options) {
      new Rico.Effect.SizeAndPosition(element, null, null, w, h, duration, steps, options);
  }
}

Rico.Effect.Position = Class.create();
Rico.Effect.Position.prototype = {

   initialize: function(element, x, y, duration, steps, options) {
      new Rico.Effect.SizeAndPosition(element, x, y, null, null, duration, steps, options);
  }
}

Rico.Effect.Round = Class.create();
Rico.Effect.Round.prototype = {

   initialize: function(tagName, className, options) {
      var elements = document.getElementsByTagAndClassName(tagName,className);
      for ( var i = 0 ; i < elements.length ; i++ )
         Rico.Corner.round( elements[i], options );
   }
};

Rico.Effect.FadeTo = Class.create();
Rico.Effect.FadeTo.prototype = {

   initialize: function( element, opacity, duration, steps, options) {
      this.element  = $(element);
      this.opacity  = opacity;
      this.duration = duration;
      this.steps    = steps;
      this.options  = arguments[4] || {};
      this.fadeTo();
   },

   fadeTo: function() {
      if (this.isFinished()) {
         if(this.options.complete) this.options.complete(this);
         return;
      }

      if (this.timer)
         clearTimeout(this.timer);

      var stepDuration = Math.round(this.duration/this.steps) ;
      var currentOpacity = this.getElementOpacity();
      var delta = this.steps > 0 ? (this.opacity - currentOpacity)/this.steps : 0;

      this.changeOpacityBy(delta);
      this.duration -= stepDuration;
      this.steps--;

      this.timer = setTimeout(this.fadeTo.bind(this), stepDuration);
   },

   changeOpacityBy: function(v) {
      var currentOpacity = this.getElementOpacity();
      var newOpacity = Math.max(0, Math.min(currentOpacity+v, 1));
      this.element.ricoOpacity = newOpacity;

      this.element.style.filter = "alpha(opacity:"+Math.round(newOpacity*100)+")";
      this.element.style.opacity = newOpacity; /*//*/;
   },

   isFinished: function() {
      return this.steps <= 0;
   },

   getElementOpacity: function() {
      if ( this.element.ricoOpacity == undefined ) {
         var opacity = Element.getStyle(this.element, 'opacity');
         this.element.ricoOpacity = opacity != undefined ? opacity : 1.0;
      }
      return parseFloat(this.element.ricoOpacity);
   }
}

Rico.Effect.AccordionSize = Class.create();

Rico.Effect.AccordionSize.prototype = {

   initialize: function(e1, e2, start, end, duration, steps, options) {
      this.e1       = $(e1);
      this.e2       = $(e2);
      this.start    = start;
      this.end      = end;
      this.duration = duration;
      this.steps    = steps;
      this.options  = arguments[6] || {};

      this.accordionSize();
   },

   accordionSize: function() {

      if (this.isFinished()) {
         // just in case there are round errors or such...
         this.e1.style.height = this.start + "px";
         this.e2.style.height = this.end + "px";

         if(this.options.complete)
            this.options.complete(this);
         return;
      }

      if (this.timer)
         clearTimeout(this.timer);

      var stepDuration = Math.round(this.duration/this.steps) ;

      var diff = this.steps > 0 ? (parseInt(this.e1.offsetHeight) - this.start)/this.steps : 0;
      this.resizeBy(diff);

      this.duration -= stepDuration;
      this.steps--;

      this.timer = setTimeout(this.accordionSize.bind(this), stepDuration);
   },

   isFinished: function() {
      return this.steps <= 0;
   },

   resizeBy: function(diff) {
      var h1Height = this.e1.offsetHeight;
      var h2Height = this.e2.offsetHeight;
      var intDiff = parseInt(diff);
      if ( diff != 0 ) {
         this.e1.style.height = (h1Height - intDiff) + "px";
         this.e2.style.height = (h2Height + intDiff) + "px";
      }
   }

};

Rico.addPreloadMsg('exec: ricoEffects.js');
