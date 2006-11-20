/*
 * Date picker plugin for jQuery
 * http://kelvinluck.com/assets/jquery/datePicker
 *
 * Copyright (c) 2006 Kelvin Luck (kelvnluck.com)
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * $LastChangedDate: 2006-08-16 21:24:59 +0100 (Wed, 16 Aug 2006) $
 * $Rev: 16 $
 */
 
$.datePicker = function()
{
	// so that firebug console.log statements don't break IE
	if (window.console == undefined) { window.console = {log:function(){}}; }
	
	var months = ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var navLinks = {p:'Prev', n:'Next', c:'Close'};
	var dateFormat = 'dd/mm/yyyy';
	var _firstDate;
	var _lastDate;
	var _firstDates = {};
	var _lastDates = {};
	var _inited = {};
	
	var _selectedDate;
	var _openCal;
	
	var _zeroPad = function(num) { 
		var s = '0'+num;
		return s.substring(s.length-2) 
		//return ('0'+num).substring(-2); // doesn't work on IE :(
	};
	var _strToDate = function(dIn)
	{
		switch (dateFormat.toLowerCase()) {
			case 'yyyy-mm-dd':
				dParts = dIn.split('-');
				return new Date(dParts[0], Number(dParts[1])-1, dParts[2]);
			case 'dd/mm/yyyy':
				var parts = [2, 1, 0];
			case 'mm/dd/yyyy':
			default:
				var parts = parts ? parts : [1, 2, 0];
				dParts = dIn.split('/');
				return new Date(dParts[parts[0]], Number(dParts[parts[1]])-1, Number(dParts[parts[2]]));
		}
	};
	var _dateToStr = function(d)
	{
		var dY = d.getFullYear();
		var dM = _zeroPad(d.getMonth()+1);
		var dD = _zeroPad(d.getDate());
		switch (dateFormat.toLowerCase()) {
			case 'yyyy-mm-dd':
				return dY + '-' + dM + '-' + dD;
			case 'dd/mm/yyyy':
				return dD + '/' + dM + '/' + dY;
			case 'mm/dd/yyyy':
			default:
				return dM + '/' + dD + '/' + dY;
		}
	};
	
	var _getCalendarDiv = function(dIn)
	{
		var today = new Date();
		if (dIn == undefined) {
			// start from this month.
			d = new Date(today.getFullYear(), today.getMonth(), 1);
		} else {
			// start from the passed in date
			d = dIn;
			d.setDate(1);
		}
		// check that date is within allowed limits:		
		if (d < _firstDate) {
			d = new Date(_firstDate.getFullYear(), _firstDate.getMonth(), _firstDate.getDate());;
		} else if (d > _lastDate) {
			d = new Date(_lastDate.getFullYear(), _lastDate.getMonth(), _lastDate.getDate());;
		}
		
		var calDiv = $.DIV({className:'popup-calendar'}, '');
		var jCalDiv = $(calDiv);
		var firstMonth = true;
		var firstDate = _firstDate.getDate();
		
		// create prev and next links
		var prevLinkDiv = '';
		if (!(d.getMonth() == _firstDate.getMonth() && d.getFullYear() == _firstDate.getFullYear())) { 
			// not in first display month so show a previous link
			firstMonth = false;
			var lastMonth = new Date(d.getFullYear(), d.getMonth()-1, 1);
			var prevLink = $.A({href:'javascript:;'}, navLinks.p);
			$(prevLink).click(function()
			{
				$.datePicker.changeMonth(lastMonth, this);
				return false;
			});
			prevLinkDiv = $.DIV({className:'link-prev'}, '<', prevLink);
		}
		
		var finalMonth = true;
		var lastDate = _lastDate.getDate();
		nextLinkDiv = '';
		if (!(d.getMonth() == _lastDate.getMonth() && d.getFullYear() == _lastDate.getFullYear())) { 
			// in the last month - no next link
			finalMonth = false;
			var nextMonth = new Date(d.getFullYear(), d.getMonth()+1, 1);
			var nextLink = $.A({href:'javascript:;'}, navLinks.n);
			$(nextLink).click(function() 
			{
				$.datePicker.changeMonth(nextMonth, this);
				return false;
			});
			nextLinkDiv = $.DIV({className:'link-next'}, nextLink, '>');
		}
		
		var closeLink = $.A({href:'javascript:;'}, navLinks.c);
		$(closeLink).click(function()
		{
			$.datePicker.closeCalendar();
		});
		
		jCalDiv.append(
			$.DIV({className:'link-close'}, closeLink),
			$.H3({}, months[d.getMonth()], ' ', d.getFullYear())
		);
		
		var headRow = $.TR({});
		for (var i=0; i<7; i++) {
			var day = days[i];
			headRow.appendChild(
				$.TH({scope:'col', abbr:day, title:day}, day.substr(0, 1))
			);
		}
		
		var tBody = $.TBODY();
		
		var lastDay = (new Date(d.getFullYear(), d.getMonth()+1, 0)).getDate();
		var curDay = -d.getDay();
		
		var todayDate = (new Date()).getDate();
		var thisMonth = d.getMonth() == today.getMonth() && d.getFullYear() == today.getFullYear();
		
		var w = 0;
		while (w++<6) {
			var thisRow = $.TR({});
			for (var i=0; i<7; i++) {
				var atts = {};
				
				if (curDay < 0 || curDay >= lastDay) {
					dayStr = ' ';
				} else if (firstMonth && curDay < firstDate-1) {
					dayStr = curDay+1;
					atts.className = 'inactive';
				} else if (finalMonth && curDay > lastDate-1) {
					dayStr = curDay+1;
					atts.className = 'inactive';
				} else {
					d.setDate(curDay+1);
					var dStr = _dateToStr(d);
					dayStr = $.A({href:'#', rel:dStr}, curDay+1);
					$(dayStr).click(function(e)
					{
						$.datePicker.selectDate($.attr(this, 'rel'), this);
						return false;
					});
					if (_selectedDate && _selectedDate==dStr) {
						$(dayStr).addClass('selected');
					}
				}
				
				if (thisMonth && curDay+1 == todayDate) {
					atts.className = 'today';
				}
				thisRow.appendChild($.TD(atts, dayStr));
				curDay++;
			}
			tBody.appendChild(thisRow);
		}
		
		jCalDiv.append(
			$.TABLE({cellspacing:2}, $.THEAD({}, headRow), tBody),
			prevLinkDiv,
			nextLinkDiv
		);

		if ($.browser.msie) { 
			
			// we put a styled iframe behind the calendar so HTML SELECT elements don't show through
			jCalDiv.append(document.createElement('iframe'));
			
			// for some reason position: absolute doesn't work as you would expect in IE when the div has 
			// been dynamically attached so hack around it here :(
			jCalDiv.css({'left':'-16px'});
		}
		jCalDiv.css({'display':'block'});
		return calDiv;
	};
	var _draw = function(c)
	{
		$('div.popup-calendar', _openCal).remove();
		_openCal.append(c);
	};
	var _closeDatePicker = function()
	{
		$('div.popup-calendar *', _openCal).remove();
		$('div.popup-calendar', _openCal).css({'display':'none'});
		
		if ($.browser.msie) {
			_openCal.unbind('keypress', _handleKeys);
		} else {
			$(window).unbind('keypress', _handleKeys);
		}
		$(document).unbind('mousedown', _checkMouse);
		_openCal = null;
	};
	var _handleKeys = function(e)
	{
		var key = e.keyCode ? e.keyCode : (e.which ? e.which: 0);
		//console.log('KEY!! ' + key);
		if (key == 27) {
			_closeDatePicker();
		}
		return false;
	};
	var _checkMouse = function(e)
	{
		var target = $.browser.msie ? window.event.srcElement : e.target;
		var cp = $(target).findClosestParent('div.popup-calendar');
		if (cp.get(0).className != 'date-picker-holder') {
			_closeDatePicker();
		}
	};
	
	return {
		show: function()
		{
			if (_openCal) {
				_closeDatePicker();
			}
			this.blur();
			_firstDate = _firstDates[this.id];
			_lastDate = _lastDates[this.id];
			_openCal = $(this).findClosestParent('div.popup-calendar');
			var d = $('input', $(this).findClosestParent('input')).val();
			if (d != '') {
				if (_dateToStr(_strToDate(d)) == d) {
					_selectedDate = d;
					_draw(_getCalendarDiv(_strToDate(d)));
				} else {
					// invalid date in the input field - just default to this month
					_selectedDate = false;
					_draw(_getCalendarDiv());
				}
			} else {
				_selectedDate = false;
				_draw(_getCalendarDiv());
			}
			if ($.browser == "msie") {
				_openCal.bind('keypress', _handleKeys);
			} else {
				$(window).bind('keypress', _handleKeys);
			}
			$(document).bind('mousedown', _checkMouse);
		},
		changeMonth: function(d, e)
		{
			_draw(_getCalendarDiv(d));
		},
		selectDate: function(d, ele)
		{
			selectedDate = d;
			$('input', $(ele).findClosestParent('input')).val(d);
			_closeDatePicker(ele);
		},
		closeCalendar: function()
		{
			_closeDatePicker(this);
		},
		setInited: function(i)
		{
			_inited[i] = true;
		},
		isInited: function(i)
		{
			return _inited[i] != undefined;
		},
		setDateFormat: function(format)
		{
			// set's the format that selected dates are returned in.
			// options are 'dd/mm/yyyy' (european), 'mm/dd/yyyy' (americian) and 'yyyy-mm-dd' (unicode)
			dateFormat = format;
		},
		/**
		* Function: setLanguageStrings
		*
		* Allows you to localise the calendar by passing in relevant text for the english strings in the plugin.
		*
		* Arguments:
		* days		-	Array, e.g. ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
		* months	-	Array, e.g. ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		* navLinks	-	Object, e.g. {p:'Prev', n:'Next', c:'Close'}
		**/
		setLanguageStrings: function(aDays, aMonths, aNavLinks)
		{
			days = aDays;
			months = aMonths;
			navLinks = aNavLinks;
		},
		/**
		* Function: setDateWindow
		*
		* Used internally to set the start and end dates for a given date select
		*
		* Arguments:
		* i			-	The id of the INPUT element this date window is for
		* w			-	The date window - an object containing startDate and endDate properties
		*				each in the current format as set in a call to setDateFormat (or in the
		*				default format dd/mm/yyyy if setDateFormat hasn't been called).
		*				e.g. {startDate:'01/03/2006', endDate:'11/04/2006}
		**/
		setDateWindow: function(i, w)
		{
			if (w == undefined) w = {};
			_firstDates[i] = w.startDate == undefined ? new Date() : _strToDate(w.startDate);
			if (w.endDate == undefined) {
				_lastDates[i] = new Date();
				_lastDates[i].setFullYear(_lastDates[i].getFullYear()+5);
			} else {
				_lastDates[i] = _strToDate(w.endDate);
			};
		}
	};
}();
$.fn.findClosestParent = function(s)
{
	var ele = this;
	while (true) {
		if ($(s, ele).length > 0) {
			return (ele);
		}
		ele = ele.parent();
		if(ele[0].length == 0) {
			return false;
		}
	}
};
$.fn.datePicker = function(a)
{
	this.each(function() {
		if(this.nodeName.toLowerCase() != 'input') return;
		var butId = '__dp_' + this.id;
		$.datePicker.setDateWindow(butId, a);
		if (!$.datePicker.isInited(butId)) {
			var calBut = $.A({href:'javascript:;', className:'date-picker', title:'Choose date', id:butId}, $.SPAN({}, 'Choose date'));
			$(calBut).click($.datePicker.show);
			$(this).wrap(
				'<div class="date-picker-holder"></div>'
			).before(
				$.DIV({className:'popup-calendar'})
			).after(
				calBut
			);
			$.datePicker.setInited(butId)
		}
	});
	
};
/*
<!-- Generated calendar HTML looks like this - style with CSS -->
<div class="popup-calendar">
	<div class="link-close"><a href="#">Close</a></div>
	<h3>July 2006</h3>
	<table cellspacing="2">
		<thead>
			<tr>
				<th scope="col" abbr="Monday" title="Monday">M</th>
				<th scope="col" abbr="Tuesday" title="Tuesday">T</th>
				<th scope="col" abbr="Wednesday" title="Wednesday">W</th>
				<th scope="col" abbr="Thursday" title="Thursday">T</th>
				<th scope="col" abbr="Friday" title="Friday">F</th>
				<th scope="col" abbr="Saturday" title="Saturday">S</th>
				<th scope="col" abbr="Sunday" title="Sunday">S</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="inactive">1</td>
				<td class="inactive">2</td>
				<td class="inactive">3</td>
				<td class="inactive">4</td>
			</tr>
			<tr>
				<td class="inactive">5</td>
				<td class="inactive">6</td>
				<td class="inactive">7</td>
				<td class="today"><a href="#">8</a></td>
				<td><a href="#">9</a></td>
				<td><a href="#">10</a></td>
				<td><a href="#">11</a></td>
			</tr>
			<tr>
				<td><a href="#">12</a></td>
				<td><a href="#">13</a></td>
				<td><a href="#">14</a></td>
				<td><a href="#">15</a></td>
				<td><a href="#">16</a></td>
				<td><a href="#">17</a></td>
				<td><a href="#" class="selected">18</a></td>
			</tr>
			<tr>
				<td><a href="#">19</a></td>
				<td><a href="#">20</a></td>
				<td><a href="#">21</a></td>
				<td><a href="#">22</a></td>
				<td><a href="#">23</a></td>
				<td><a href="#">24</a></td>
				<td><a href="#">25</a></td>
			</tr>
			<tr>
				<td><a href="#">26</a></td>
				<td><a href="#">27</a></td>
				<td><a href="#">28</a></td>
				<td><a href="#">29</a></td>
				<td><a href="#">30</a></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
	</table>
	<div class="link-prev"><a href="#">Prev</a></div>
	<div class="link-next"><a href="#">Next</a></div>
</div>
*/