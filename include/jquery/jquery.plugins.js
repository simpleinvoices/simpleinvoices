//jquery.accordian.js
$.accordian=function(items,first,options){var active=first;var running=0;var titles=options&&options.titles||'.title';var contents=options&&options.contents||'.content';var onClick=options&&options.onClick||function(){};var onShow=options&&options.onShow||function(){};var onHide=options&&options.onHide||function(){};var showSpeed=options&&options.showSpeed||'slow';var hideSpeed=options&&options.hideSpeed||'fast';$(items).not(active).children(contents).hide();$(items).not(active).each(onHide);$(active).each(onShow);$(items).children(titles).click(function(e){var p=$(contents,this.parentNode);$(this.parentNode).each(onClick);if(running||!p.is(":hidden"))return false;running=2;$(active).children(contents).not(':hidden').slideUp(hideSpeed,function(){--running});p.slideDown(showSpeed,function(){--running});$(active).each(onHide);active='#'+$(this.parentNode)[0].id;$(active).each(onShow);return false})};function simpleLog(message){$('<div>'+message+'</div>').appendTo('#log')}$(function(){if($.accordian){$.accordian('#list1 > div','#item11',{titles:'.title',contents:'.content',onClick:function(){simpleLog(this.id+' clicked')},onShow:function(){simpleLog(this.id+' shown');$(this).removeClass('off1').addClass('on1')},onHide:function(){simpleLog(this.id+' hidden');$(this).removeClass('on1').addClass('off1')},showSpeed:250,hideSpeed:250});$.accordian('#list2 > div','#item22',{titles:'.mytitle',contents:'.mycontent',onClick:function(){simpleLog(this.id+' clicked')},onShow:function(){simpleLog(this.id+' shown');$(this).removeClass('off').addClass('on')},onHide:function(){simpleLog(this.id+' hidden');$(this).removeClass('on').addClass('off')},showSpeed:550,hideSpeed:550})}});

//jquery.datePicker.js
jQuery.datePicker=function(){if(window.console==undefined){window.console={log:function(){}}}var months=['January','February','March','April','May','June','July','August','September','October','November','December'];var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];var navLinks={p:'Prev',n:'Next',c:'Close',b:'Choose date'};var dateFormat='dmy';var dateSeparator="/";var _drawingMonth=false;var _firstDayOfWeek;var _firstDate;var _lastDate;var _selectedDate;var _openCal;var _zeroPad=function(num){var s='0'+num;return s.substring(s.length-2)};var _strToDate=function(dIn){switch(dateFormat){case'ymd':dParts=dIn.split(dateSeparator);return new Date(dParts[0],Number(dParts[1])-1,dParts[2]);case'dmy':dParts=dIn.split(dateSeparator);return new Date(dParts[2],Number(dParts[1])-1,Number(dParts[0]));case'dmmy':dParts=dIn.split(dateSeparator);for(var m=0;m<12;m++){if(dParts[1].toLowerCase()==months[m].substr(0,3).toLowerCase()){return new Date(Number(dParts[2]),m,Number(dParts[0]))}}return undefined;case'mdy':default:var parts=parts?parts:[2,1,0];dParts=dIn.split(dateSeparator);return new Date(dParts[2],Number(dParts[0])-1,Number(dParts[1]))}};var _dateToStr=function(d){var dY=d.getFullYear();var dM=_zeroPad(d.getMonth()+1);var dD=_zeroPad(d.getDate());switch(dateFormat){case'ymd':return dY+dateSeparator+dM+dateSeparator+dD;case'dmy':return dD+dateSeparator+dM+dateSeparator+dY;case'dmmy':return dD+dateSeparator+months[d.getMonth()].substr(0,3)+dateSeparator+dY;case'mdy':default:return dM+dateSeparator+dD+dateSeparator+dY}};var _getCalendarDiv=function(dIn){var today=new Date();if(dIn==undefined){d=new Date(today.getFullYear(),today.getMonth(),1)}else{d=dIn;d.setDate(1)}if((d.getMonth()<_firstDate.getMonth()&&d.getFullYear()==_firstDate.getFullYear())||d.getFullYear()<_firstDate.getFullYear()){d=new Date(_firstDate.getFullYear(),_firstDate.getMonth(),1)}else if((d.getMonth()>_lastDate.getMonth()&&d.getFullYear()==_lastDate.getFullYear())||d.getFullYear()>_lastDate.getFullYear()){d=new Date(_lastDate.getFullYear(),_lastDate.getMonth(),1)}var jCalDiv=jQuery("<div></div>").attr('class','popup-calendar');var firstMonth=true;var firstDate=_firstDate.getDate();var prevLinkDiv='';if(!(d.getMonth()==_firstDate.getMonth()&&d.getFullYear()==_firstDate.getFullYear())){firstMonth=false;var lastMonth=d.getMonth()==0?new Date(d.getFullYear()-1,11,1):new Date(d.getFullYear(),d.getMonth()-1,1);var prevLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.p).click(function(){jQuery.datePicker.changeMonth(lastMonth,this);return false});prevLinkDiv=jQuery("<div></div>").attr('class','link-prev').html('&lt;').append(prevLink)}var finalMonth=true;var lastDate=_lastDate.getDate();nextLinkDiv='';if(!(d.getMonth()==_lastDate.getMonth()&&d.getFullYear()==_lastDate.getFullYear())){finalMonth=false;var nextMonth=new Date(d.getFullYear(),d.getMonth()+1,1);var nextLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.n).click(function(){jQuery.datePicker.changeMonth(nextMonth,this);return false});nextLinkDiv=jQuery("<div></div>").attr('class','link-next').html('&gt;').prepend(nextLink)}var closeLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.c).click(function(){jQuery.datePicker.closeCalendar()});jCalDiv.append(jQuery("<div></div>").attr('class','link-close').append(closeLink),jQuery("<h3></h3>").html(months[d.getMonth()]+' '+d.getFullYear()));var headRow=jQuery("<tr></tr>");for(var i=_firstDayOfWeek;i<_firstDayOfWeek+7;i++){var weekday=i%7;var day=days[weekday];headRow.append(jQuery("<th></th>").attr({'scope':'col','abbr':day,'title':day,'class':(weekday==0||weekday==6?'weekend':'weekday')}).html(day.substr(0,1)))}var tBody=jQuery("<tbody></tbody>");var lastDay=(new Date(d.getFullYear(),d.getMonth()+1,0)).getDate();var curDay=_firstDayOfWeek-d.getDay();if(curDay>0)curDay-=7;var todayDate=(new Date()).getDate();var thisMonth=d.getMonth()==today.getMonth()&&d.getFullYear()==today.getFullYear();var w=0;while(w++<6){var thisRow=jQuery("<tr></tr>");for(var i=0;i<7;i++){var weekday=(_firstDayOfWeek+i)%7;var atts={'class':(weekday==0||weekday==6?'weekend ':'weekday ')};if(curDay<0||curDay>=lastDay){dayStr=' '}else if(firstMonth&&curDay<firstDate-1){dayStr=curDay+1;atts['class']+='inactive'}else if(finalMonth&&curDay>lastDate-1){dayStr=curDay+1;atts['class']+='inactive'}else{d.setDate(curDay+1);var dStr=_dateToStr(d);dayStr=jQuery("<a></a>").attr({'href':'javascript:;','rel':dStr}).html(curDay+1).click(function(e){jQuery.datePicker.selectDate(jQuery.attr(this,'rel'),this);return false})[0];if(_selectedDate&&_selectedDate==dStr){jQuery(dayStr).attr('class','selected')}}if(thisMonth&&curDay+1==todayDate){atts['class']+='today'}thisRow.append(jQuery("<td></td>").attr(atts).append(dayStr));curDay++}tBody.append(thisRow)}jCalDiv.append(jQuery("<table></table>").attr('cellspacing',2).append("<thead></thead>").find("thead").append(headRow).parent().append(tBody.children())).append(prevLinkDiv).append(nextLinkDiv);if(jQuery.browser.msie){var iframe=['<iframe class="bgiframe" tabindex="-1" ','style="display:block; position:absolute;','top: 0;','left:0;','z-index:-1; filter:Alpha(Opacity=\'0\');','width:3000px;','height:3000px"/>'].join('');jCalDiv.append(document.createElement(iframe))}jCalDiv.css({'display':'block'});return jCalDiv[0]};var _draw=function(c){jQuery('div.popup-calendar a',_openCal[0]).unbind();jQuery('div.popup-calendar',_openCal[0]).empty();jQuery('div.popup-calendar',_openCal[0]).remove();_openCal.append(c)};var _closeDatePicker=function(){jQuery('div.popup-calendar a',_openCal).unbind();jQuery('div.popup-calendar',_openCal).empty();jQuery('div.popup-calendar',_openCal).css({'display':'none'});jQuery(document).unbind('mousedown',_checkMouse);delete _openCal;_openCal=null};var _handleKeys=function(e){var key=e.keyCode?e.keyCode:(e.which?e.which:0);if(key==27){_closeDatePicker()}return false};var _checkMouse=function(e){if(!_drawingMonth){var target=jQuery.browser.msie?window.event.srcElement:e.target;console.log(jQuery(target));var cp=jQuery(target).findClosestParent('div.popup-calendar-wrapper');if(cp.get(0).className!='date-picker-holder'){_closeDatePicker()}}};return{getChooseDateStr:function(){return navLinks.b},show:function(){if(_openCal){_closeDatePicker()}this.blur();var input=jQuery('input',jQuery(this).findClosestParent('input')[0])[0];_firstDate=input._startDate;_lastDate=input._endDate;_firstDayOfWeek=input._firstDayOfWeek;_openCal=jQuery(this).parent().find('>div.popup-calendar-wrapper');var d=jQuery(input).val();if(d!=''){if(_dateToStr(_strToDate(d))==d){_selectedDate=d;_draw(_getCalendarDiv(_strToDate(d)))}else{_selectedDate=false;_draw(_getCalendarDiv())}}else{_selectedDate=false;_draw(_getCalendarDiv())}jQuery(document).bind('mousedown',_checkMouse)},changeMonth:function(d,e){_drawingMonth=true;_draw(_getCalendarDiv(d));_drawingMonth=false},selectDate:function(d,ele){selectedDate=d;var $theInput=jQuery('input',jQuery(ele).findClosestParent('input')[0]);$theInput.val(d);$theInput.trigger('change');_closeDatePicker(ele)},closeCalendar:function(){_closeDatePicker(this)},setInited:function(i){i._inited=true},isInited:function(i){return i._inited!=undefined},setDateFormat:function(format,separator){dateFormat=format.toLowerCase();dateSeparator=separator?separator:"/"},setLanguageStrings:function(aDays,aMonths,aNavLinks){days=aDays;months=aMonths;navLinks=aNavLinks},setDateWindow:function(i,w){if(w==undefined)w={};if(w.startDate==undefined){i._startDate=new Date()}else{i._startDate=_strToDate(w.startDate)}if(w.endDate==undefined){i._endDate=new Date();i._endDate.setFullYear(i._endDate.getFullYear()+5)}else{i._endDate=_strToDate(w.endDate)};i._firstDayOfWeek=w.firstDayOfWeek==undefined?0:w.firstDayOfWeek}}}();jQuery.fn.findClosestParent=function(s){var ele=this;while(true){if(jQuery(s,ele[0]).length>0){return(ele)}ele=ele.parent();if(ele[0].length==0){return false}}};jQuery.fn.datePicker=function(a){this.each(function(){if(this.nodeName.toLowerCase()!='input')return;jQuery.datePicker.setDateWindow(this,a);if(!jQuery.datePicker.isInited(this)){var chooseDate=jQuery.datePicker.getChooseDateStr();var calBut;if(a&&a.inputClick){calBut=jQuery(this).attr('title',chooseDate).addClass('date-picker')}else{calBut=jQuery("<a></a>").attr({'href':'javascript:;','class':'date-picker','title':chooseDate}).append("<span>"+chooseDate+"</span>")}jQuery(this).wrap('<div class="date-picker-holder"></div>').after(jQuery('<div></div>').attr('class','popup-calendar-wrapper').append(jQuery("<div></div>").attr({'class':'popup-calendar'})),calBut);calBut.bind('click',jQuery.datePicker.show);jQuery.datePicker.setInited(this)}});return this};

//jquery.dimensions.js
(function($){$.dimensions={version:'@VERSION'};$.each(['Height','Width'],function(i,name){$.fn['inner'+name]=function(){if(!this[0])return;var torl=name=='Height'?'Top':'Left',borr=name=='Height'?'Bottom':'Right';return this[name.toLowerCase()]()+num(this,'padding'+torl)+num(this,'padding'+borr)};$.fn['outer'+name]=function(options){if(!this[0])return;var torl=name=='Height'?'Top':'Left',borr=name=='Height'?'Bottom':'Right';options=$.extend({margin:false},options||{});return this[name.toLowerCase()]()+num(this,'border'+torl+'Width')+num(this,'border'+borr+'Width')+num(this,'padding'+torl)+num(this,'padding'+borr)+(options.margin?(num(this,'margin'+torl)+num(this,'margin'+borr)):0)}});$.each(['Left','Top'],function(i,name){$.fn['scroll'+name]=function(val){if(!this[0])return;return val!=undefined?this.each(function(){this==window||this==document?window.scrollTo(name=='Left'?val:$(window)['scrollLeft'](),name=='Top'?val:$(window)['scrollTop']()):this['scroll'+name]=val}):this[0]==window||this[0]==document?self[(name=='Left'?'pageXOffset':'pageYOffset')]||$.boxModel&&document.documentElement['scroll'+name]||document.body['scroll'+name]:this[0]['scroll'+name]}});$.fn.extend({position:function(){var left=0,top=0,elem=this[0],offset,parentOffset,offsetParent,results;if(elem){offsetParent=this.offsetParent();offset=this.offset();parentOffset=offsetParent.offset();offset.top-=num(elem,'marginTop');offset.left-=num(elem,'marginLeft');parentOffset.top+=num(offsetParent,'borderTopWidth');parentOffset.left+=num(offsetParent,'borderLeftWidth');results={top:offset.top-parentOffset.top,left:offset.left-parentOffset.left}}return results},offsetParent:function(){var offsetParent=this[0].offsetParent;while(offsetParent&&(!/^body|html$/i.test(offsetParent.tagName)&&$.css(offsetParent,'position')=='static'))offsetParent=offsetParent.offsetParent;return $(offsetParent)}});var num=function(el,prop){return parseInt($.css(el.jquery?el[0]:el,prop))||0}})(jQuery);

//jquery.history.js
jQuery.history=new function(){var _hash=location.hash;var _intervalId=null;var _historyIframe;var _backStack,_forwardStack,_addHistory;var _observeHistory;if(jQuery.browser.msie){$(function(){_historyIframe=$('<iframe style="display: none;"></iframe>').appendTo(document.body)[0];var iframeDoc=_historyIframe.contentWindow.document;iframeDoc.open();iframeDoc.close();iframeDoc.location.hash=_hash});_observeHistory=function(){if(_historyIframe){var iframeDoc=_historyIframe.contentWindow.document;var iframeHash=iframeDoc.location.hash;if(iframeHash!=_hash){location.hash=_hash=iframeHash;jQuery('a[@href$="'+_hash+'"]').click()}}}}else if(jQuery.browser.mozilla||jQuery.browser.opera){_observeHistory=function(){if(location.hash){if(_hash!=location.hash){_hash=location.hash;jQuery('a[@href$="'+_hash+'"]').click()}}else{_hash='';var output=jQuery('.remote-output');if(output.children().size()>0)output.empty()}}}else if(jQuery.browser.safari){$(function(){_backStack=[];_backStack.length=history.length;_forwardStack=[]});var isFirst=false;_addHistory=function(hash){_backStack.push(hash);_forwardStack.length=0;isFirst=false};_observeHistory=function(){var historyDelta=history.length-_backStack.length;if(historyDelta){isFirst=false;if(historyDelta<0){for(var i=0;i<Math.abs(historyDelta);i++)_forwardStack.unshift(_backStack.pop())}else{for(var i=0;i<historyDelta;i++)_backStack.push(_forwardStack.shift())}var cachedHash=_backStack[_backStack.length-1];jQuery('a[@href$="'+cachedHash+'"]').click();_hash=location.hash}else if(_backStack[_backStack.length-1]==undefined&&!isFirst){if(document.URL.indexOf('#')>=0){jQuery('a[@href$="'+'#'+document.URL.split('#')[1]+'"]').click()}else{var output=jQuery('.remote-output');if(output.children().size()>0)output.empty()}isFirst=true}}}this.setHash=function(hash,e){_hash=hash;if(this.iframe()){this.iframe().open();this.iframe().close();this.iframe().location.hash=hash}if(typeof _addHistory=='function'&&e.clientX)_addHistory(_hash)};this.iframe=function(){var iframeDoc=_historyIframe&&_historyIframe.contentWindow.document||null;return iframeDoc};this.observe=function(){if(location.hash&&typeof _addHistory=='undefined')jQuery('a.remote[@href$="'+location.hash+'"]').click();if(_observeHistory&&_intervalId==null)_intervalId=setInterval(_observeHistory,250)}};jQuery.fn.history=function(){return this.each(function(){jQuery(this).click(function(e){jQuery.history.setHash(this.hash,e)})})};jQuery.fn.remote=function(output){var target=jQuery(output).size()&&jQuery(output)||jQuery('<div></div>').appendTo('body');target.addClass('remote-output');return this.each(function(i){var remote=this.href;var hash='remote-'+ ++i;jQuery(this).href('#'+hash).click(function(e){target.load(remote,function(){jQuery.history.setHash('#'+hash,e)})})})};$.log=function(s){var LOG_OUTPUT_ID='log-output';var LOG_OUTPUT_STYLE='position: fixed; _position: absolute; top: 0; right: 0; overflow: hidden; border: 1px solid; width: 300px; height: 200px; background: #fff; color: red; opacity: .95;';var logOutput=$('#'+LOG_OUTPUT_ID)[0]||$('<div style="'+LOG_OUTPUT_STYLE+'" id="'+LOG_OUTPUT_ID+'"></div>').prependTo('body')[0];$(logOutput).prepend('<code>'+s+'</code><br />')};

//jquery.dom_creator.js
$.defineTag=function(tag){$[tag.toUpperCase()]=function(){return $._createNode(tag,arguments)}};(function(){var tags=['a','br','button','canvas','div','fieldset','form','h1','h2','h3','hr','img','input','label','legend','li','ol','optgroup','option','p','pre','select','span','strong','table','tbody','td','textarea','tfoot','th','thead','tr','tt','ul'];for(var i=tags.length-1;i>=0;i--){$.defineTag(tags[i])}})();$.NBSP='\u00a0';$._createNode=function(tag,args){var fix={'class':'className','Class':'className'};var e;try{if(typeof(args[0])=='string'){var newArgs=[{}];for(i=0;i<args.length;i++)newArgs.push(args[i]);args=newArgs}var attrs=args[0]||{};e=document.createElement(tag);for(var attr in attrs){var a=fix[attr]||attr;e[a]=attrs[attr]}for(var i=1;i<args.length;i++){var arg=args[i];if(arg==null)continue;if(arg.constructor!=Array)append(arg);else for(var j=0;j<arg.length;j++)append(arg[j])}}catch(ex){alert('Cannot create <'+tag+'> element:\n'+args.toSource()+'\n'+args);e=null}function append(arg){if(arg==null)return;var c=arg.constructor;switch(typeof arg){case'number':arg=''+arg;case'string':arg=document.createTextNode(arg)}e.appendChild(arg)}return e};$.TEXT=function(s){return document.createTextNode(s)};

//jquery.tabs.js
//
/*
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('3.1o.m=5(8,2){4(C 8==\'1Q\')2=8;2=3.Q({8:(8&&C 8==\'1X\'&&8>0)?--8:0,S:l,R:l,V:l,O:l,K:\'1Y\',1b:l,1d:l,1n:25,g:l,y:\'m-1L\',x:\'m-A\',F:\'W\'},2||{});6 N=5(){15(0,0)};U 7.q(5(){4(X.9){3(\'>D:H(0)>G>a\',7).q(5(i){4(7.9==X.9){2.8=i;4(3.p.J)u(N,1C);N();4(3.p.1s)u(N,24)}})}4(2.1n){6 13=3(\'>\'+2.F,7);6 w=[];13.q(5(i){w.22(7.1w);4(2.8!=i)3(7).v(2.x)});w.20(5(a,b){U b-a});13.q(5(){3(7).L({1z:w[0]+\'1j\'});4(3.p.J&&C 1A==\'5\')3(7).L({r:w[0]+\'1j\'})})}o{3(\'>\'+2.F,7).1D(\':H(\'+2.8+\')\').v(2.x)}3(\'>D>G:H(\'+2.8+\')\',7).v(2.y);6 T=7;6 m=3(\'>D>G>a\',7);4(3.n){m.n();3.n.1E()}m.Y(5(e){4(!3(7.1g).12(\'.\'+2.y)){6 d=3(7.9);4(3.p.J){6 17=7.9.1a(\'#\',\'\');d.1e(\'\');u(5(){d.1e(17)},0)}4(d.1G()>0){6 1f=7;6 t=3(\'>\'+2.F+\':1H\',T);6 g;4(2.g&&C 2.g==\'5\')g=5(){2.g.1K(d[0],[d[0],t[0]])};6 j={},k={};6 z,s;4(2.R||2.S){4(2.R){j[\'r\']=\'P\';k[\'r\']=\'A\'}4(2.S){j[\'B\']=\'P\';k[\'B\']=\'A\'}z=s=2.K}o{4(2.V){j=3.Q(j,2.V);z=2.1b||2.K}o{j[\'B\']=\'P\';z=1}4(2.O){k=3.Q(k,2.O);s=2.1d||2.K}o{k[\'B\']=\'A\';s=1O}}t.1i(k,s,5(){3(1f.1g).v(2.y).1P().1h(2.y);d.1h(2.x).1i(j,z,5(){4(3.p.J){t[0].19.1S=\'\';t.v(2.x).L({1c:\'\',r:\'1k\'})}d.L({r:\'1k\'});4(g)g()})})}o{1W(\'21 12 1p 1q T.\')}}6 16=11.1t||c.M&&c.M.1l||c.Z.1l||0;6 18=11.1Z||c.M&&c.M.14||c.Z.14||0;u(5(){11.15(16,18)},0)})})};3.1o.1I=5(E){U 7.q(5(){6 i=E&&E>0&&E-1||0;6 I=3(\'>D>G>a\',7).H(i);6 9=I[0].9;4(3(9).12(\':1T\')){4(3.p.1U){6 f=c.1V(\'23\');f.1r=\'<W><1u 1x="1m" 1B="h" /></W>\';f.1J=9;f.19.1c=\'1N\';c.Z.1R(f);u(5(){f.1m();I.Y();4(3.n)3.n.1v(9,{1y:1F});f.1M()},10)}o{X.9=9.1a(\'#\',\'\')}4(!3.n)I.Y()}})};',62,130,'||settings|jQuery|if|function|var|this|initial|hash|||document|tabToShow|||callback|||showAnim|hideAnim|null|tabs|history|else|browser|each|height|hideSpeed|tabToHide|setTimeout|addClass|heights|hideClass|selectedClass|showSpeed|hide|opacity|typeof|ul|tabIndex|tabStruct|li|eq|tabToTrigger|msie|fxSpeed|css|documentElement|_unFocus|fxHide|show|extend|fxSlide|fxFade|container|return|fxShow|div|location|click|body||window|is|divs|scrollTop|scrollTo|scrollX|tabToShowId|scrollY|style|replace|fxShowSpeed|display|fxHideSpeed|id|clicked|parentNode|removeClass|animate|px|auto|scrollLeft|submit|fxAutoheight|fn|no|such|innerHTML|opera|pageXOffset|input|setHash|offsetHeight|type|clientX|minHeight|XMLHttpRequest|value|150|not|observe|42|size|visible|triggerTab|action|apply|selected|remove|none|50|siblings|object|appendChild|filter|hidden|safari|createElement|alert|number|normal|pageYOffset|sort|There|push|form|100|false'.split('|'),0,{}))
*/
//jquery.autocomplete.js
$.autocomplete = function(wrapper, options) {

	var me = this;
	$(wrapper).removeClass(options.removeClass);
	var results = document.createElement("div");
	$(results).hide();
	results.style.zIndex = 20;
	wrapper.appendChild(results);
	$(results).attr("class", options.resultsClass);
	var input = $("input", wrapper)[0];
	input.autocompleter = this;
	input.lastSelected = $(input).val();

	var timeout = null;
	var prev = "";
	var active = -1;
	var cache = {};
	var cover = null;

/*@cc_on
   cover = document.createElement("iframe");
   cover.style.src = "javascript:document.write('');";
   cover.style.display = "none";
   cover.style.zIndex = 10;
   cover.style.position = "absolute";
   cover.setAttribute("scrolling", "no");
   cover.setAttribute("frameborder", "0");
   cover.style.width = "100%";
   wrapper.appendChild(cover);
@*/

	$(input)
	.keydown(function(e) {
		switch(e.keyCode) {
			case 38: // up
				e.preventDefault();
				moveSelect(-1);
				break;
			case 40: // down
				e.preventDefault();
				moveSelect(1);
				break;
	//		case 9:  // tab
			case 13: // return
				if (selectCurrent()) {
					e.preventDefault();
				}
				break;
			default:
				active = -1;
				if (timeout) clearTimeout(timeout);
				timeout = setTimeout(onChange, options.delay);
				break;
		}
	})
	.blur(function() {
		hideResults();
	});

	hideResultsNow();

	function onChange() {
		var v = $(input).val();
		if (v == prev) return;
		prev = v;
		if (v.length >= options.minChars) {
			$(input).addClass(options.loadingClass);
			requestData(v);
		} else {
			$(input).removeClass(options.loadingClass);
			$(results).hide();
		}
	};

	function moveSelect(step) {

		var lis = $("li", results);
		if (!lis) return;

		active += step;

		if (active < 0) {
			active = 0;
		} else if (active >= lis.size()) {
			active = lis.size() - 1;
		}

		lis.removeClass("over");
		$(lis.get(active)).addClass("over");

	};

	function selectCurrent() {
		var li = $("li.over", results)[0];
		if (!li) {
			var $li = $("li", results);
			if (options.selectOnly) {
				if ($li.length == 1) li = $li[0];
			} else if (options.selectFirst) {
				li = $li[0];
			}
		}
		if (li) {
			selectItem(li);
			return true;
		} else {
			return false;
		}
	};

	function selectItem(li) {
		if (!li) {
			li = document.createElement("li");
			li.extra = [];
			li.selectValue = "";
		}
		var v = $.trim(li.selectValue ? li.selectValue : li.innerHTML);
		input.lastSelected = v;
		prev = v;
		$(results).html("");
		$(input).val(v);
		hideResultsNow();
		if (options.onItemSelect) setTimeout(function() { options.onItemSelect(li) }, 1);
	};

	function hideResults() {
		if (timeout) clearTimeout(timeout);
		timeout = setTimeout(hideResultsNow, 200);
	};

	function hideResultsNow() {
		if (timeout) clearTimeout(timeout);
		$(input).removeClass(options.loadingClass);
		if ($(results).is(":visible")) {
			$(results).hide();
		}
		if (cover) $(cover).hide();
		if (options.mustMatch) {
			var v = $(input).val();
			if (v != input.lastSelected) {
				selectItem(null);
			}
		}
	};

	function receiveData(q, data) {
		if (data) {
			$(input).removeClass(options.loadingClass);
			results.innerHTML = "";
			results.appendChild(dataToDom(data));
			$(results).show();
			if (cover) {
				cover.style.top     = results.offsetTop;
				cover.style.left    = results.offsetLeft;
				cover.style.width   = results.clientWidth + 2;
				cover.style.height  = results.clientHeight + 2;
				$(cover).show();
			}
		} else {
			hideResultsNow();
		}
	};

	function parseData(data) {
		if (!data) return null;
		var parsed = [];
		var rows = data.split(options.lineSeparator);
		for (var i=0; i < rows.length; i++) {
			var row = $.trim(rows[i]);
			if (row) {
				parsed[parsed.length] = row.split(options.cellSeparator);
			}
		}
		return parsed;
	};

	function dataToDom(data) {
		var ul = document.createElement("ul");
		for (var i=0; i < data.length; i++) {
			var row = data[i];
			if (!row) continue;
			var li = document.createElement("li");
			if (options.formatItem) {
				li.innerHTML = options.formatItem(row);
				li.selectValue = row[0];
			} else {
				li.innerHTML = row[0];
			}
			var extra = null;
			if (row.length > 1) {
				extra = [];
				for (var j=1; j < row.length; j++) {
					extra[extra.length] = row[j];
				}
			}
			li.extra = extra;
			ul.appendChild(li);
			$(li).hover(
				function() { $(this).addClass("over"); },
				function() { $(this).removeClass("over"); }
			).click(function(e) { e.preventDefault(); e.stopPropagation(); selectItem(this) });
		}
		return ul;
	};

	function requestData(q) {
		if (!options.matchCase) q = q.toLowerCase();
		var data = options.cacheLength ? loadFromCache(q) : null;
		if (data) {
			receiveData(q, data);
		} else {
			$.get(makeUrl(q), function(data) {
				data = parseData(data)
				addToCache(q, data);
				receiveData(q, data);
			});
		}
	};

	function makeUrl(q) {
		var url = options.url + "&q=" + q;
		for (var i in options.extraParams) {
			url += "&" + i + "=" + options.extraParams[i];
		}
		return url;
	};

	function loadFromCache(q) {
		if (!q) return null;
		if (cache[q]) return cache[q];
		if (options.matchSubset) {
			for (var i = q.length - 1; i >= options.minChars; i--) {
				var qs = q.substr(0, i);
				var c = cache[qs];
				if (c) {
					var csub = [];
					for (var j = 0; j < c.length; j++) {
						var x = c[j];
						var x0 = x[0];
						if (matchSubset(x0, q)) {
							csub[csub.length] = x;
						}
					}
					return csub;
				}
			}
		}
		return null;
	};

	function matchSubset(s, sub) {
		if (!options.matchCase) s = s.toLowerCase();
		var i = s.indexOf(sub);
		if (i == -1) return false;
		return i == 0 || options.matchContains;
	};

	this.flushCache = function() {
		cache = {};
	};

	this.setExtraParams = function(p) {
		options.extraParams = p;
	};

	function addToCache(q, data) {
		if (!data || !q || !options.cacheLength) return;
		if (!cache.length || cache.length > options.cacheLength) {
			cache = {};
			cache.length = 1; // we know we're adding something
		} else if (!cache[q]) {
			cache.length++;
		}
		cache[q] = data;
	};
}

$.fn.autocomplete = function(url, options) {
	// Make sure options exists
	options = options || {};
	// Set url as option
	options.url = url;
	// Set removeClass as option
	options.removeClass = "ac___remove___this___class";
	// Set default values for required options
	options.wrapperClass = options.wrapperClass || "ac_wrapper";
	options.resultsClass = options.resultsClass || "ac_results";
	options.lineSeparator = options.lineSeparator || "\n";
	options.cellSeparator = options.cellSeparator || "|";
	options.minChars = options.minChars || 1;
	options.delay = options.delay || 400;
	options.matchCase = options.matchCase || 0;
	options.matchSubset = options.matchSubset || 1;
	options.matchContains = options.matchContains || 0;
	options.cacheLength = options.cacheLength || 1;
	options.mustMatch = options.mustMatch || 0;
	options.extraParams = options.extraParams || {};
	options.loadingClass = options.loadingClass || "ac_loading";
	options.selectFirst = options.selectFirst || false;
	options.selectOnly = options.selectOnly || false;
	// Wrap our input elements with a DIV
	this.wrap("<div class='" + options.wrapperClass + " " + options.removeClass + "'></div>");
	// Find all the newly created DIVs and create an autocompleter for each of them
	$("div." + options.removeClass).each(function() { new $.autocomplete(this, options); });
	// Don't break the chain
	return this;
}

