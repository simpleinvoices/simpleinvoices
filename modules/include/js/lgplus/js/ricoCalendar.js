//  By Matt Brown
//  June-October 2006
//  email: dowdybrown@yahoo.com
//  Implements a pop-up Gregorian calendar.
//  Dates of adoption of the Gregorian calendar vary by country - accurate as a US & British calendar from 14 Sept 1752 to present.
//  Mark special dates with calls to addHoliday()
//  Inspired by code originally written by Tan Ling Wee on 2 Dec 2001

//  Requires prototype.js and ricoCommon.js

Rico.CalendarControl = Class.create();

Rico.CalendarControl.prototype = {

  initialize: function(id,options) {
    this.id=id;
    var today=new Date();
    this.options = {
      startAt : 0,           // week starts with 0=sunday, 1=monday
      showWeekNumber : 0,    // show week number in first column?
      showToday : 1,         // show "Today is..." in footer?
      cursorColor: '#FDD',   // color used to highlight dates as the user moves their mouse
      repeatInterval : 100,  // when left/right arrow is pressed, repeat action every x milliseconds
      dateFmt : 'ISO8601',   // default is ISO-8601, 'rico'=use format stored in ricoTranslate object
      selectedDateBorder : "#666666",  // border to indicate currently selected date
      minDate : new Date(today.getFullYear()-50,0,1),  // default to +-50 yrs from current date
      maxDate : new Date(today.getFullYear()+50,11,31)
    }
    Object.extend(this.options, options || {});
    this.bPageLoaded=false;
    this.img=new Array();
    this.Holidays={};
    this.todayString=RicoTranslate.getPhrase("Today is ");
    this.weekString=RicoTranslate.getPhrase("Wk");
    if (this.options.dateFmt=='rico') this.options.dateFmt=RicoTranslate.dateFmt;
    this.dateParts=new Array();
    this.re=/^\s*(\w+)(\W)(\w+)(\W)(\w+)/i;
    if (this.re.exec(this.options.dateFmt)) {
      this.dateParts[RegExp.$1]=0;
      this.dateParts[RegExp.$3]=1;
      this.dateParts[RegExp.$5]=2;
    }
  },


  // y=0 implies a repeating holiday
  addHoliday : function(d, m, y, desc, bgColor, txtColor) {
    this.Holidays[this.holidayKey(y,m-1,d)]={desc:desc, txtColor:txtColor, bgColor:bgColor || '#DDF'};
  },
  
  holidayKey : function(y,m,d) {
    return 'h'+y.zf(4)+m.zf(2)+d.zf(2);
  },

  // stop event bubbling from the container so that it doesn't turn off the row highlight
  containerClick: function(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    } else {
      e.cancelBubble = true;
    }
    return true;
  },
  
  atLoad : function() {
    this.container=document.createElement("div");
    this.container.style.display="none"
    this.container.id=this.id;
    this.container.className='ricoCalContainer';

    this.maintab=document.createElement("table");
    this.maintab.cellSpacing=0;
    this.maintab.cellPadding=0;
    this.maintab.border=0;
    this.maintab.className='ricoCalTab';

    for (var i=0; i<7; i++) {
      var r=this.maintab.insertRow(-1);
      r.className='row'+i;
      for (var c=0; c<8; c++)
        r.insertCell(-1);
    }
    this.tbody=this.maintab.tBodies[0];
    var r=this.tbody.rows[0];
    r.className='ricoCalDayNames';
    if (this.options.showWeekNumber) {
      r.cells[0].innerHTML=this.weekString;
      for (var i=0; i<7; i++)
        this.tbody.rows[i].cells[0].className='ricoCalWeekNum';
    }
    this.styles=[];
    for (var i=0; i<7; i++) {
      var dow=(i+this.options.startAt) % 7;
      r.cells[i+1].innerHTML=RicoTranslate.dayNames[dow].substring(0,3);
      this.styles[i+1]='ricoCal'+dow;
    }
    
    // table header (navigation controls)
    this.thead=this.maintab.createTHead()
    var r=this.thead.insertRow(-1);
    var c=r.insertCell(-1);
    c.colSpan=8;
    var img=this.createNavArrow('decMonth','left');
    c.appendChild(document.createElement("a")).appendChild(img);
    this.titleMonth=document.createElement("a");
    c.appendChild(this.titleMonth);
    Event.observe(this.titleMonth,"click", this.popUpMonth.bindAsEventListener(this), false);
    var img=this.createNavArrow('incMonth','right');
    c.appendChild(document.createElement("a")).appendChild(img);
    var s=document.createElement("span");
    s.innerHTML='&nbsp;';
    s.style.paddingLeft='3em';
    c.appendChild(s);

    var img=this.createNavArrow('decYear','left');
    c.appendChild(document.createElement("a")).appendChild(img);
    this.titleYear=document.createElement("a");
    Event.observe(this.titleYear,"click", this.popUpYear.bindAsEventListener(this), false);
    c.appendChild(this.titleYear);
    var img=this.createNavArrow('incYear','right');
    c.appendChild(document.createElement("a")).appendChild(img);

    // table footer (today)
    if (this.options.showToday) {
      this.tfoot=this.maintab.createTFoot()
      var r=this.tfoot.insertRow(-1);
      this.todayCell=r.insertCell(-1);
      this.todayCell.colSpan=8;
      Event.observe(this.todayCell,"click", this.selectNow.bindAsEventListener(this), false);
    }
    

    this.container.appendChild(this.maintab);
    
    // close icon (upper right)
    var img=document.createElement("img");
    img.src=Rico.imgDir+'close.gif';
    img.onclick=this.close.bind(this);
    img.style.cursor='pointer';
    img.style.position='absolute';
    img.style.top='1px';   /* assumes a 1px border */
    img.style.right='1px';
    this.container.appendChild(img);
    
    // month selector
    this.monthSelect=document.createElement("table");
    this.monthSelect.className='ricoCalMenu';
    this.monthSelect.cellPadding=2;
    this.monthSelect.cellSpacing=0;
    this.monthSelect.border=0;
    for (var i=0; i<4; i++) {
      var r=this.monthSelect.insertRow(-1);
      for (var j=0; j<3; j++) {
        var c=r.insertCell(-1);
        var a=document.createElement("a");
        a.innerHTML=RicoTranslate.monthNames[i*3+j].substring(0,3);
        a.name=i*3+j;
        c.appendChild(a);
        Event.observe(a,"click", this.selectMonth.bindAsEventListener(this), false);
      }
    }
    this.monthSelect.style.display='none';
    this.container.appendChild(this.monthSelect);
    
    // fix anchors so they work in IE6
    var a=this.container.getElementsByTagName('a');
    for (var i=0; i<a.length; i++)
      a[i].href='#';
    
    Event.observe(this.tbody,"click", this.saveAndClose.bindAsEventListener(this), false);
    Event.observe(this.tbody,"mouseover", this.mouseOver.bindAsEventListener(this), false);
    Event.observe(this.tbody,"mouseout",  this.mouseOut.bindAsEventListener(this),  false);
    Event.observe(this.container,"click", this.containerClick.bindAsEventListener(this), false);
    document.body.appendChild(this.container);
    this.shim=new Rico.Shim();
    this.close()
    this.bPageLoaded=true
  },
  
  selectNow : function() {
    this.monthSelected=this.monthNow;
    this.yearSelected=this.yearNow;
    this.constructCalendar();
  },
  
  createNavArrow: function(funcname,gifname) {
    var img=document.createElement("img");
    img.src=Rico.imgDir+gifname+'.gif';
    img.name=funcname;
    Event.observe(img,"click", this[funcname].bindAsEventListener(this), false);
    Event.observe(img,"mousedown", this.mouseDown.bindAsEventListener(this), false);
    Event.observe(img,"mouseup", this.mouseUp.bindAsEventListener(this), false);
    Event.observe(img,"mouseout", this.mouseUp.bindAsEventListener(this), false);
    return img
  },

  mouseOver: function(e) {
    var el=Event.element(e);
    if (this.lastHighlight==el) return;
    this.unhighlight();
    var s=el.innerHTML.replace(/&nbsp;/g,'');
    if (s=='' || el.className=='ricoCalWeekNum') return;
    var day=parseInt(s);
    if (isNaN(day)) return;
    this.lastHighlight=el;
    this.tmpColor=el.style.backgroundColor;
    el.style.backgroundColor=this.options.cursorColor;
  },
  
  unhighlight: function() {
    if (!this.lastHighlight) return;
    this.lastHighlight.style.backgroundColor=this.tmpColor;
    this.lastHighlight=null;
  },
  
  mouseOut: function(e) {
    var el=Event.element(e);
    if (el==this.lastHighlight) this.unhighlight();
  },
  
  mouseDown: function(e) {
    var el=Event.element(e);
    this.repeatFunc=this[el.name].bind(this);
    this.timeoutID=setTimeout(this.repeatStart.bind(this),500);
  },
  
  mouseUp: function(e) {
    clearTimeout(this.timeoutID);
    clearInterval(this.intervalID)
  },
  
  repeatStart : function() {
    clearInterval(this.intervalID);
    this.intervalID=setInterval(this.repeatFunc,this.options.repeatInterval);
  },
  
  // is yr/mo within minDate/MaxDate?
  isValidMonth : function(yr,mo) {
    if (yr < this.options.minDate.getFullYear()) return false;
    if (yr == this.options.minDate.getFullYear() && mo < this.options.minDate.getMonth()) return false;
    if (yr > this.options.maxDate.getFullYear()) return false;
    if (yr == this.options.maxDate.getFullYear() && mo > this.options.maxDate.getMonth()) return false;
    return true;
  },

  incMonth : function() {
    var newMonth=this.monthSelected+1;
    var newYear=this.yearSelected;
    if (newMonth>11) {
      newMonth=0;
      newYear++;
    }
    if (!this.isValidMonth(newYear,newMonth)) return;
    this.monthSelected=newMonth;
    this.yearSelected=newYear;
    this.constructCalendar()
  },

  decMonth : function() {
    var newMonth=this.monthSelected-1;
    var newYear=this.yearSelected;
    if (newMonth<0) {
      newMonth=11;
      newYear--;
    }
    if (!this.isValidMonth(newYear,newMonth)) return;
    this.monthSelected=newMonth;
    this.yearSelected=newYear;
    this.constructCalendar()
  },
  
  selectMonth : function(e) {
    var el=Event.element(e);
    this.monthSelected=parseInt(el.name);
    this.constructCalendar();
    Event.stop(e);
  },

  popUpMonth : function() {
    this.monthSelect.style.display=this.monthSelect.style.display=='none' ? 'block' : 'none';
  },

  popDownMonth : function() {
    this.monthSelect.style.display='none';
  },

  /*** Year Pulldown ***/

  popUpYear : function() {
    var newYear=prompt(RicoTranslate.getPhrase("Year ("+this.options.minDate.getFullYear()+"-"+this.options.maxDate.getFullYear()+")"),this.yearSelected);
    if (newYear==null) return;
    newYear=parseInt(newYear);
    if (isNaN(newYear) || newYear<this.options.minDate.getFullYear() || newYear>this.options.maxDate.getFullYear()) {
      alert(RicoTranslate.getPhrase("Invalid year"));
    } else {
      this.yearSelected=newYear;
      this.constructCalendar();
    }
  },
  
  incYear : function() {
    if (this.yearSelected>=this.options.minDate.getFullYear()) return;
    this.yearSelected++;
    this.constructCalendar();
  },

  decYear : function() {
    if (this.yearSelected<=this.options.minDate.getFullYear()) return;
    this.yearSelected--;
    this.constructCalendar();
  },

  // tried a number of different week number functions posted on the net
  // this is the only one that produced consistent results when comparing week numbers for December and the following January
  WeekNbr : function(year,month,day) {
    var when = new Date(year,month,day);
    var newYear = new Date(year,0,1);
    var offset = 7 + 1 - newYear.getDay();
    if (offset == 8) offset = 1;
    var daynum = ((Date.UTC(year,when.getMonth(),when.getDate(),0,0,0) - Date.UTC(year,0,1,0,0,0)) /1000/60/60/24) + 1;
    var weeknum = Math.floor((daynum-offset+7)/7);
    if (weeknum == 0) {
        year--;
        var prevNewYear = new Date(year,0,1);
        var prevOffset = 7 + 1 - prevNewYear.getDay();
        if (prevOffset == 2 || prevOffset == 8) weeknum = 53; else weeknum = 52;
    }
    return weeknum;
  },

  constructCalendar : function() {
    var aNumDays = Array (31,0,31,30,31,30,31,31,30,31,30,31)
    var startDate = new Date (this.yearSelected,this.monthSelected,1)
    var endDate,numDaysInMonth

    if (typeof this.monthSelected!='number' || this.monthSelected>=12 || this.monthSelected<0) {
      alert('ERROR in calendar: monthSelected='+this.monthSelected);
      return;
    }
    var today = new Date();
    this.dateNow  = today.getDate();
    this.monthNow = today.getMonth();
    this.yearNow  = today.getFullYear();

    if (this.monthSelected==1) {
      endDate = new Date (this.yearSelected,this.monthSelected+1,1);
      endDate = new Date (endDate - (24*60*60*1000));
      numDaysInMonth = endDate.getDate()
    } else {
      numDaysInMonth = aNumDays[this.monthSelected];
    }
    var dayPointer = startDate.getDay() - this.options.startAt
    if (dayPointer<0) dayPointer+=7;
    this.popDownMonth();

    this.bgcolor=Element.getStyle(this.tbody,'background-color');
    this.bgcolor=this.bgcolor.replace(/\"/g,'');
    if (this.options.showWeekNumber) {
      for (var i=1; i<7; i++)
        this.tbody.rows[i].cells[0].innerHTML='&nbsp;';
    }
    for ( var i=1; i<=dayPointer; i++ )
      this.resetCell(this.tbody.rows[1].cells[i]);

    for ( var datePointer=1,r=1; datePointer<=numDaysInMonth; datePointer++,dayPointer++ ) {
      var colnum=dayPointer % 7 + 1;
      if (this.options.showWeekNumber==1 && colnum==1)
        this.tbody.rows[r].cells[0].innerHTML=this.WeekNbr(this.yearSelected,this.monthSelected,datePointer);
      var dateClass=this.styles[colnum];
      if ((datePointer==this.dateNow)&&(this.monthSelected==this.monthNow)&&(this.yearSelected==this.yearNow))
        dateClass='ricoCalToday';
      var c=this.tbody.rows[r].cells[colnum];
      c.innerHTML="&nbsp;" + datePointer + "&nbsp;";
      c.className=dateClass;
      var bordercolor=(datePointer==this.odateSelected) && (this.monthSelected==this.omonthSelected) && (this.yearSelected==this.oyearSelected) ? this.options.selectedDateBorder : this.bgcolor;
      c.style.border='1px solid '+bordercolor;
      var h=this.Holidays[this.holidayKey(this.yearSelected,this.monthSelected,datePointer)];
      if (!h)  h=this.Holidays[this.holidayKey(0,this.monthSelected,datePointer)];
      c.style.color=h ? h.txtColor : '';
      c.style.backgroundColor=h ? h.bgColor : '';
      c.title=h ? h.desc : '';
      if (colnum==7) r++;
    }
    while (dayPointer<42) {
      var colnum=dayPointer % 7 + 1;
      this.resetCell(this.tbody.rows[r].cells[colnum]);
      dayPointer++;
      if (colnum==7) r++;
    }

    this.titleMonth.innerHTML = RicoTranslate.monthNames[this.monthSelected].substring(0,3);
    this.titleYear.innerHTML = this.yearSelected;
    if (this.options.showToday)
      this.todayCell.innerHTML=this.todayString+'<span>'+this.dateNow + " " + RicoTranslate.monthNames[this.monthNow].substring(0,3) + " " + this.yearNow+'</span>';
    this.monthSelect.style.top=this.thead.offsetHeight+'px';
    this.monthSelect.style.left=this.titleMonth.offsetLeft+'px';
  },
  
  resetCell: function(c) {
    c.innerHTML="&nbsp;";
    c.className='ricoCalEmpty';
    c.style.border='1px solid '+this.bgcolor;
    c.style.color='';
    c.style.backgroundColor='';
    c.title='';
  },
  
  close : function() {
    this.shim.hide();
    this.container.style.display="none"
  },

  saveAndClose : function(e) {
    Event.stop(e);
    var el=Event.element(e);
    var s=el.innerHTML.replace(/&nbsp;/g,'');
    if (s=='' || el.className=='ricoCalWeekNum') return;
    var day=parseInt(s);
    if (isNaN(day)) return;
    var d=new Date(this.yearSelected,this.monthSelected,day);
    var dateStr=d.formatDate(this.options.dateFmt=='ISO8601' ? 'yyyy-mm-dd' : this.options.dateFmt);
    if (this.returnValue) this.returnValue(dateStr);
    this.close();
  },

  open : function(curval) {
    if (!this.bPageLoaded) return;
    if (typeof curval=='object') {
      this.dateSelected  = curval.getDate();
      this.monthSelected = curval.getMonth();
      this.yearSelected  = curval.getFullYear();
    } else if (this.options.dateFmt=='ISO8601') {
      var d=new Date;
      d.setISO8601(curval);
      this.dateSelected  = d.getDate();
      this.monthSelected = d.getMonth();
      this.yearSelected  = d.getFullYear();
    } else if (this.re.exec(curval)) {
      var aDate=new Array(RegExp.$1,RegExp.$3,RegExp.$5);
      this.dateSelected  = parseInt(aDate[this.dateParts['dd']], 10);
      this.monthSelected = parseInt(aDate[this.dateParts['mm']], 10) - 1;
      this.yearSelected  = parseInt(aDate[this.dateParts['yyyy']], 10);
    } else {
      if (curval) alert('ERROR: invalid date passed to calendar ('+curval+')');
      this.dateSelected  = this.dateNow
      this.monthSelected = this.monthNow
      this.yearSelected  = this.yearNow
    }
    this.odateSelected=this.dateSelected
    this.omonthSelected=this.monthSelected
    this.oyearSelected=this.yearSelected
    this.container.style.display="block";
    this.constructCalendar();
  }
}

Rico.addPreloadMsg('exec: ricoCalendar.js');
