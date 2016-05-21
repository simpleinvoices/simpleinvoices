//jquery.accordian.js
$.accordian=function(items,first,options){var active=first;var running=0;var titles=options&&options.titles||'.title';var contents=options&&options.contents||'.content';var onClick=options&&options.onClick||function(){};var onShow=options&&options.onShow||function(){};var onHide=options&&options.onHide||function(){};var showSpeed=options&&options.showSpeed||'slow';var hideSpeed=options&&options.hideSpeed||'fast';$(items).not(active).children(contents).hide();$(items).not(active).each(onHide);$(active).each(onShow);$(items).children(titles).click(function(e){var p=$(contents,this.parentNode);$(this.parentNode).each(onClick);if(running||!p.is(":hidden"))return false;running=2;$(active).children(contents).not(':hidden').slideUp(hideSpeed,function(){--running});p.slideDown(showSpeed,function(){--running});$(active).each(onHide);active='#'+$(this.parentNode)[0].id;$(active).each(onShow);return false})};function simpleLog(message){$('<div>'+message+'</div>').appendTo('#log')}$(function(){if($.accordian){$.accordian('#list1 > div','#item11',{titles:'.title',contents:'.content',onClick:function(){simpleLog(this.id+' clicked')},onShow:function(){simpleLog(this.id+' shown');$(this).removeClass('off1').addClass('on1')},onHide:function(){simpleLog(this.id+' hidden');$(this).removeClass('on1').addClass('off1')},showSpeed:250,hideSpeed:250});$.accordian('#list2 > div','#item22',{titles:'.mytitle',contents:'.mycontent',onClick:function(){simpleLog(this.id+' clicked')},onShow:function(){simpleLog(this.id+' shown');$(this).removeClass('off').addClass('on')},onHide:function(){simpleLog(this.id+' hidden');$(this).removeClass('on').addClass('off')},showSpeed:550,hideSpeed:550})}});

//jquery.datePicker.js
//jQuery.datePicker=function(){if(window.console==undefined){window.console={log:function(){}}}var months=['January','February','March','April','May','June','July','August','September','October','November','December'];var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];var navLinks={p:'Prev',n:'Next',c:'Close',b:'Choose date'};var dateFormat='dmy';var dateSeparator="/";var _drawingMonth=false;var _firstDayOfWeek;var _firstDate;var _lastDate;var _selectedDate;var _openCal;var _zeroPad=function(num){var s='0'+num;return s.substring(s.length-2)};var _strToDate=function(dIn){switch(dateFormat){case'ymd':dParts=dIn.split(dateSeparator);return new Date(dParts[0],Number(dParts[1])-1,dParts[2]);case'dmy':dParts=dIn.split(dateSeparator);return new Date(dParts[2],Number(dParts[1])-1,Number(dParts[0]));case'dmmy':dParts=dIn.split(dateSeparator);for(var m=0;m<12;m++){if(dParts[1].toLowerCase()==months[m].substr(0,3).toLowerCase()){return new Date(Number(dParts[2]),m,Number(dParts[0]))}}return undefined;case'mdy':default:var parts=parts?parts:[2,1,0];dParts=dIn.split(dateSeparator);return new Date(dParts[2],Number(dParts[0])-1,Number(dParts[1]))}};var _dateToStr=function(d){var dY=d.getFullYear();var dM=_zeroPad(d.getMonth()+1);var dD=_zeroPad(d.getDate());switch(dateFormat){case'ymd':return dY+dateSeparator+dM+dateSeparator+dD;case'dmy':return dD+dateSeparator+dM+dateSeparator+dY;case'dmmy':return dD+dateSeparator+months[d.getMonth()].substr(0,3)+dateSeparator+dY;case'mdy':default:return dM+dateSeparator+dD+dateSeparator+dY}};var _getCalendarDiv=function(dIn){var today=new Date();if(dIn==undefined){d=new Date(today.getFullYear(),today.getMonth(),1)}else{d=dIn;d.setDate(1)}if((d.getMonth()<_firstDate.getMonth()&&d.getFullYear()==_firstDate.getFullYear())||d.getFullYear()<_firstDate.getFullYear()){d=new Date(_firstDate.getFullYear(),_firstDate.getMonth(),1)}else if((d.getMonth()>_lastDate.getMonth()&&d.getFullYear()==_lastDate.getFullYear())||d.getFullYear()>_lastDate.getFullYear()){d=new Date(_lastDate.getFullYear(),_lastDate.getMonth(),1)}var jCalDiv=jQuery("<div></div>").attr('class','popup-calendar');var firstMonth=true;var firstDate=_firstDate.getDate();var prevLinkDiv='';if(!(d.getMonth()==_firstDate.getMonth()&&d.getFullYear()==_firstDate.getFullYear())){firstMonth=false;var lastMonth=d.getMonth()==0?new Date(d.getFullYear()-1,11,1):new Date(d.getFullYear(),d.getMonth()-1,1);var prevLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.p).click(function(){jQuery.datePicker.changeMonth(lastMonth,this);return false});prevLinkDiv=jQuery("<div></div>").attr('class','link-prev').html('&lt;').append(prevLink)}var finalMonth=true;var lastDate=_lastDate.getDate();nextLinkDiv='';if(!(d.getMonth()==_lastDate.getMonth()&&d.getFullYear()==_lastDate.getFullYear())){finalMonth=false;var nextMonth=new Date(d.getFullYear(),d.getMonth()+1,1);var nextLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.n).click(function(){jQuery.datePicker.changeMonth(nextMonth,this);return false});nextLinkDiv=jQuery("<div></div>").attr('class','link-next').html('&gt;').prepend(nextLink)}var closeLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.c).click(function(){jQuery.datePicker.closeCalendar()});jCalDiv.append(jQuery("<div></div>").attr('class','link-close').append(closeLink),jQuery("<h3></h3>").html(months[d.getMonth()]+' '+d.getFullYear()));var headRow=jQuery("<tr></tr>");for(var i=_firstDayOfWeek;i<_firstDayOfWeek+7;i++){var weekday=i%7;var day=days[weekday];headRow.append(jQuery("<th></th>").attr({'scope':'col','abbr':day,'title':day,'class':(weekday==0||weekday==6?'weekend':'weekday')}).html(day.substr(0,1)))}var tBody=jQuery("<tbody></tbody>");var lastDay=(new Date(d.getFullYear(),d.getMonth()+1,0)).getDate();var curDay=_firstDayOfWeek-d.getDay();if(curDay>0)curDay-=7;var todayDate=(new Date()).getDate();var thisMonth=d.getMonth()==today.getMonth()&&d.getFullYear()==today.getFullYear();var w=0;while(w++<6){var thisRow=jQuery("<tr></tr>");for(var i=0;i<7;i++){var weekday=(_firstDayOfWeek+i)%7;var atts={'class':(weekday==0||weekday==6?'weekend ':'weekday ')};if(curDay<0||curDay>=lastDay){dayStr=' '}else if(firstMonth&&curDay<firstDate-1){dayStr=curDay+1;atts['class']+='inactive'}else if(finalMonth&&curDay>lastDate-1){dayStr=curDay+1;atts['class']+='inactive'}else{d.setDate(curDay+1);var dStr=_dateToStr(d);dayStr=jQuery("<a></a>").attr({'href':'javascript:;','rel':dStr}).html(curDay+1).click(function(e){jQuery.datePicker.selectDate(jQuery.attr(this,'rel'),this);return false})[0];if(_selectedDate&&_selectedDate==dStr){jQuery(dayStr).attr('class','selected')}}if(thisMonth&&curDay+1==todayDate){atts['class']+='today'}thisRow.append(jQuery("<td></td>").attr(atts).append(dayStr));curDay++}tBody.append(thisRow)}jCalDiv.append(jQuery("<table></table>").attr('cellspacing',2).append("<thead></thead>").find("thead").append(headRow).parent().append(tBody.children())).append(prevLinkDiv).append(nextLinkDiv);if(jQuery.browser.msie){var iframe=['<iframe class="bgiframe" tabindex="-1" ','style="display:block; position:absolute;','top: 0;','left:0;','z-index:-1; filter:Alpha(Opacity=\'0\');','width:3000px;','height:3000px"/>'].join('');jCalDiv.append(document.createElement(iframe))}jCalDiv.css({'display':'block'});return jCalDiv[0]};var _draw=function(c){jQuery('div.popup-calendar a',_openCal[0]).unbind();jQuery('div.popup-calendar',_openCal[0]).empty();jQuery('div.popup-calendar',_openCal[0]).remove();_openCal.append(c)};var _closeDatePicker=function(){jQuery('div.popup-calendar a',_openCal).unbind();jQuery('div.popup-calendar',_openCal).empty();jQuery('div.popup-calendar',_openCal).css({'display':'none'});jQuery(document).unbind('mousedown',_checkMouse);delete _openCal;_openCal=null};var _handleKeys=function(e){var key=e.keyCode?e.keyCode:(e.which?e.which:0);if(key==27){_closeDatePicker()}return false};var _checkMouse=function(e){if(!_drawingMonth){var target=jQuery.browser.msie?window.event.srcElement:e.target;console.log(jQuery(target));var cp=jQuery(target).findClosestParent('div.popup-calendar-wrapper');if(cp.get(0).className!='date-picker-holder'){_closeDatePicker()}}};return{getChooseDateStr:function(){return navLinks.b},show:function(){if(_openCal){_closeDatePicker()}this.blur();var input=jQuery('input',jQuery(this).findClosestParent('input')[0])[0];_firstDate=input._startDate;_lastDate=input._endDate;_firstDayOfWeek=input._firstDayOfWeek;_openCal=jQuery(this).parent().find('>div.popup-calendar-wrapper');var d=jQuery(input).val();if(d!=''){if(_dateToStr(_strToDate(d))==d){_selectedDate=d;_draw(_getCalendarDiv(_strToDate(d)))}else{_selectedDate=false;_draw(_getCalendarDiv())}}else{_selectedDate=false;_draw(_getCalendarDiv())}jQuery(document).bind('mousedown',_checkMouse)},changeMonth:function(d,e){_drawingMonth=true;_draw(_getCalendarDiv(d));_drawingMonth=false},selectDate:function(d,ele){selectedDate=d;var $theInput=jQuery('input',jQuery(ele).findClosestParent('input')[0]);$theInput.val(d);$theInput.trigger('change');_closeDatePicker(ele)},closeCalendar:function(){_closeDatePicker(this)},setInited:function(i){i._inited=true},isInited:function(i){return i._inited!=undefined},setDateFormat:function(format,separator){dateFormat=format.toLowerCase();dateSeparator=separator?separator:"/"},setLanguageStrings:function(aDays,aMonths,aNavLinks){days=aDays;months=aMonths;navLinks=aNavLinks},setDateWindow:function(i,w){if(w==undefined)w={};if(w.startDate==undefined){i._startDate=new Date()}else{i._startDate=_strToDate(w.startDate)}if(w.endDate==undefined){i._endDate=new Date();i._endDate.setFullYear(i._endDate.getFullYear()+5)}else{i._endDate=_strToDate(w.endDate)};i._firstDayOfWeek=w.firstDayOfWeek==undefined?0:w.firstDayOfWeek}}}();jQuery.fn.findClosestParent=function(s){var ele=this;while(true){if(jQuery(s,ele[0]).length>0){return(ele)}ele=ele.parent();if(ele[0].length==0){return false}}};jQuery.fn.datePicker=function(a){this.each(function(){if(this.nodeName.toLowerCase()!='input')return;jQuery.datePicker.setDateWindow(this,a);if(!jQuery.datePicker.isInited(this)){var chooseDate=jQuery.datePicker.getChooseDateStr();var calBut;if(a&&a.inputClick){calBut=jQuery(this).attr('title',chooseDate).addClass('date-picker')}else{calBut=jQuery("<a></a>").attr({'href':'javascript:;','class':'date-picker','title':chooseDate}).append("<span>"+chooseDate+"</span>")}jQuery(this).wrap('<div class="date-picker-holder"></div>').after(jQuery('<div></div>').attr('class','popup-calendar-wrapper').append(jQuery("<div></div>").attr({'class':'popup-calendar'})),calBut);calBut.bind('click',jQuery.datePicker.show);jQuery.datePicker.setInited(this)}});return this};
jQuery.datePicker=function() {
    if(window.console==undefined) {
        window.console={log:function(){}}
    }
    var months=['January','February','March','April','May','June','July','August','September','October','November','December'];
    var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    var navLinks={p:'Prev',n:'Next',c:'Close',b:'Choose date'};
    var dateFormat='dmy';
    var dateSeparator="/";
    var _drawingMonth=false;
    var _firstDayOfWeek;
    var _firstDate;
    var _lastDate;
    var _selectedDate;
    var _openCal;
    var _zeroPad=function(num) {
        var s='0'+num;
        return s.substring(s.length-2)
    };
    var _strToDate=function(dIn) {
        switch(dateFormat) {
            case'ymd':
                dParts=dIn.split(dateSeparator);
                return new Date(dParts[0],Number(dParts[1])-1,dParts[2]);
            case'dmy':
                dParts=dIn.split(dateSeparator);
                return new Date(dParts[2],Number(dParts[1])-1,Number(dParts[0]));
            case'dmmy':
                dParts=dIn.split(dateSeparator);
                for(var m=0;m<12;m++) {
                    if(dParts[1].toLowerCase()==months[m].substr(0,3).toLowerCase()) {
                        return new Date(Number(dParts[2]),m,Number(dParts[0]))
                    }
                }
                return undefined;
            case'mdy':
            default:
                var parts=parts?parts:[2,1,0];
                dParts=dIn.split(dateSeparator);
                return new Date(dParts[2],Number(dParts[0])-1,Number(dParts[1]))
        }
    };
    var _dateToStr=function(d) {
        var dY=d.getFullYear();
        var dM=_zeroPad(d.getMonth()+1);
        var dD=_zeroPad(d.getDate());
        switch(dateFormat) {
            case'ymd':
                return dY+dateSeparator+dM+dateSeparator+dD;
            case'dmy':
                return dD+dateSeparator+dM+dateSeparator+dY;
            case'dmmy':
                return dD+dateSeparator+months[d.getMonth()].substr(0,3)+dateSeparator+dY;
            case'mdy':
            default:
                return dM+dateSeparator+dD+dateSeparator+dY}
        };
        var _getCalendarDiv=function(dIn) {
            var today=new Date();
            if(dIn==undefined) {
                d=new Date(today.getFullYear(),today.getMonth(),1)
            } else {
                d=dIn;d.setDate(1)
            }
            if((d.getMonth()    <_firstDate.getMonth() &&
                d.getFullYear()==_firstDate.getFullYear()) ||
                d.getFullYear() <_firstDate.getFullYear()) {
                d=new Date(_firstDate.getFullYear(),_firstDate.getMonth(),1)
            } else if((d.getMonth()    >_lastDate.getMonth() &&
                       d.getFullYear()==_lastDate.getFullYear()) ||
                       d.getFullYear() >_lastDate.getFullYear()) {
                d=new Date(_lastDate.getFullYear(),_lastDate.getMonth(),1)
            }
            var jCalDiv=jQuery("<div></div>").attr('class','popup-calendar');
            var firstMonth=true;
            var firstDate=_firstDate.getDate();
            var prevLinkDiv='';
            if(!(d.getMonth()==_firstDate.getMonth()&&d.getFullYear()==_firstDate.getFullYear())) {
                firstMonth=false;
                var lastMonth=d.getMonth()==0?new Date(d.getFullYear()-1,11,1):new Date(d.getFullYear(),d.getMonth()-1,1);
                var prevLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.p).click(function(){
                    jQuery.datePicker.changeMonth(lastMonth,this);
                    return false}
                );
                prevLinkDiv=jQuery("<div></div>").attr('class','link-prev').html('&lt;').append(prevLink)
            }
            var finalMonth=true;
            var lastDate=_lastDate.getDate();
            nextLinkDiv='';
            if(!(d.getMonth()==_lastDate.getMonth()&&d.getFullYear()==_lastDate.getFullYear())){
                finalMonth=false;
                var nextMonth=new Date(d.getFullYear(),d.getMonth()+1,1);
                var nextLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.n).click(function() {
                    jQuery.datePicker.changeMonth(nextMonth,this);return false}
                );
                nextLinkDiv=jQuery("<div></div>").attr('class','link-next').html('&gt;').prepend(nextLink)
            }
            var closeLink=jQuery("<a></a>").attr('href','javascript:;').html(navLinks.c).click(function(){
                jQuery.datePicker.closeCalendar()
            }
        );
        jCalDiv.append(jQuery("<div></div>").attr('class','link-close').append(closeLink),
                       jQuery("<h3></h3>").html(months[d.getMonth()]+' '+d.getFullYear()));
        var headRow=jQuery("<tr></tr>");
        for(var i=_firstDayOfWeek;i<_firstDayOfWeek+7;i++) {
            var weekday=i%7;
            var day=days[weekday];
            headRow.append(jQuery("<th></th>").attr({'scope':'col','abbr':day,'title':day,'class':(weekday==0||weekday==6?'weekend':'weekday')}).html(day.substr(0,1)))
        }
        var tBody=jQuery("<tbody></tbody>");
        var lastDay=(new Date(d.getFullYear(),d.getMonth()+1,0)).getDate();
        var curDay=_firstDayOfWeek-d.getDay();
        if(curDay>0)curDay-=7;
        var todayDate=(new Date()).getDate();
        var thisMonth=d.getMonth()==today.getMonth()&&d.getFullYear()==today.getFullYear();
        var w=0;
        while(w++<6) {
            var thisRow=jQuery("<tr></tr>");
            for(var i=0;i<7;i++) {
                var weekday=(_firstDayOfWeek+i)%7;
                var atts={'class':(weekday==0||weekday==6?'weekend ':'weekday ')};
                if(curDay<0||curDay>=lastDay) {
                    dayStr=' '
                } else if(firstMonth&&curDay<firstDate-1) {
                    dayStr=curDay+1;
                    atts['class']+='inactive'
                } else if(finalMonth&&curDay>lastDate-1) {
                    dayStr=curDay+1;
                    atts['class']+='inactive'
                } else {
                    d.setDate(curDay+1);
                    var dStr=_dateToStr(d);
                    dayStr=jQuery("<a></a>").attr({'href':'javascript:;','rel':dStr}).html(curDay+1).click(function(e) {
                        jQuery.datePicker.selectDate(jQuery.attr(this,'rel'),this);
                        return false
                    }
                )[0];
                if(_selectedDate&&_selectedDate==dStr) {
                    jQuery(dayStr).attr('class','selected')
                }
            }
            if(thisMonth&&curDay+1==todayDate) {
                atts['class']+='today'}thisRow.append(jQuery("<td></td>").attr(atts).append(dayStr));
                curDay++
            }
            tBody.append(thisRow)
        }
        jCalDiv.append(jQuery("<table></table>").attr('cellspacing',2).append("<thead></thead>").find("thead").append(headRow).parent().append(tBody.children())).append(prevLinkDiv).append(nextLinkDiv);
        if(jQuery.browser.msie) {
            var iframe=['<iframe class="bgiframe" tabindex="-1" ','style="display:block; position:absolute;','top: 0;','left:0;','z-index:-1; filter:Alpha(Opacity=\'0\');','width:3000px;','height:3000px"/>'].join('');
            jCalDiv.append(document.createElement(iframe))
        }
        jCalDiv.css({'display':'block'});
        return jCalDiv[0]
    };
    var _draw=function(c) {
        jQuery('div.popup-calendar a',_openCal[0]).unbind();
        jQuery('div.popup-calendar',_openCal[0]).empty();
        jQuery('div.popup-calendar',_openCal[0]).remove();
        _openCal.append(c)};
        var _closeDatePicker=function() {
            jQuery('div.popup-calendar a',_openCal).unbind();
            jQuery('div.popup-calendar',_openCal).empty();
            jQuery('div.popup-calendar',_openCal).css({'display':'none'});
            jQuery(document).unbind('mousedown',_checkMouse);
            delete _openCal;
            _openCal=null};
            var _handleKeys=function(e) {
                var key=e.keyCode?e.keyCode:(e.which?e.which:0);
                if(key==27){_closeDatePicker()}
                return false
            };
            var _checkMouse=function(e) {
                if(!_drawingMonth) {
                    var target=jQuery.browser.msie?window.event.srcElement:e.target;
                    console.log(jQuery(target));
                    var cp=jQuery(target).findClosestParent('div.popup-calendar-wrapper');
                    if(cp.get(0).className!='date-picker-holder') {
                        _closeDatePicker()
                    }
                }
            };
            return {
                getChooseDateStr:function() {return navLinks.b},
                show:function() {
                    if(_openCal){_closeDatePicker()}
                    this.blur();
                    var input=jQuery('input',jQuery(this).findClosestParent('input')[0])[0];
                    _firstDate=input._startDate;
                    _lastDate=input._endDate;
                    _firstDayOfWeek=input._firstDayOfWeek;
                    _openCal=jQuery(this).parent().find('>div.popup-calendar-wrapper');
                    var d=jQuery(input).val();
                    if(d!=''){
                        if(_dateToStr(_strToDate(d))==d){
                            _selectedDate=d;_draw(_getCalendarDiv(_strToDate(d)))
                        } else {
                            _selectedDate=false;
                            _draw(_getCalendarDiv())
                        }
                    } else {
                        _selectedDate=false;
                        _draw(_getCalendarDiv())
                    }
                    jQuery(document).bind('mousedown',_checkMouse)},changeMonth:function(d,e){
                        _drawingMonth=true;
                        _draw(_getCalendarDiv(d));
                        _drawingMonth=false
                    },
                    selectDate:function(d,ele) {
                        selectedDate=d;
                        var $theInput=jQuery('input',jQuery(ele).findClosestParent('input')[0]);
                        $theInput.val(d);
                        $theInput.trigger('change');
                        _closeDatePicker(ele)
                    },
                    closeCalendar:function() {
                        _closeDatePicker(this)
                    },
                    setInited:function(i) {
                        i._inited=true
                    },
                    isInited:function(i){
                        return i._inited!=undefined
                    },
                    setDateFormat:function(format,separator) {
                        dateFormat=format.toLowerCase();
                        dateSeparator=separator?separator:"/"
                    },
                    setLanguageStrings:function(aDays,aMonths,aNavLinks) {
                        days=aDays;
                        months=aMonths;
                        navLinks=aNavLinks
                    },
                    setDateWindow:function(i,w) {
                        if(w==undefined)w={};
                        if(w.startDate==undefined) {
                            i._startDate=new Date()
                        } else {
                            i._startDate=_strToDate(w.startDate)
                        }
                        if(w.endDate==undefined) {
                            i._endDate=new Date();
                            i._endDate.setFullYear(i._endDate.getFullYear()+5)
                        } else {
                            i._endDate=_strToDate(w.endDate)
                        };
                        i._firstDayOfWeek=w.firstDayOfWeek==undefined?0:w.firstDayOfWeek
                    }
                }
            }();
            jQuery.fn.findClosestParent=function(s) {
                var ele=this;
                while(true) {
                    if(jQuery(s,ele[0]).length>0) {
                        return(ele)
                    }
                    ele=ele.parent();
                    if(ele[0].length==0) {
                        return false
                    }
                }
            };
            jQuery.fn.datePicker=function(a) {
                this.each(function() {
                    if(this.nodeName.toLowerCase()!='input')return;
                    jQuery.datePicker.setDateWindow(this,a);
                    if(!jQuery.datePicker.isInited(this)) {
                        var chooseDate=jQuery.datePicker.getChooseDateStr();
                        var calBut;
                        if(a&&a.inputClick) {
                            calBut=jQuery(this).attr('title',chooseDate).addClass('date-picker')
                        } else {
                            calBut=jQuery("<a></a>").attr({'href':'javascript:;','class':'date-picker','title':chooseDate}).append("<span>"+chooseDate+"</span>")
                        }
                        jQuery(this).wrap('<div class="date-picker-holder"></div>').after(jQuery('<div></div>').attr('class','popup-calendar-wrapper').append(jQuery("<div></div>").attr({'class':'popup-calendar'})),calBut);
                        calBut.bind('click',jQuery.datePicker.show);
                        jQuery.datePicker.setInited(this)
                    }
                }
            );
            return this
        };
