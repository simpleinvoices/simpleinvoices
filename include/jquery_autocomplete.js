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
		var url = options.url + "?q=" + q;
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
