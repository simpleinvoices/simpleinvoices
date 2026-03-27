/**
 * Simple Invoices - Tabler.io data grid (vanilla JS, no jQuery)
 * Drop-in replacement for flexigrid using existing XML endpoints.
 * Uses GET params (page, rp, sortname, sortorder, query, qtype) and XML format:
 * <rows><page>1</page><total>N</total><row id=""><cell><![CDATA[...]]></cell>...</row></rows>
 *
 * Usage: siTablerGrid('#manageGrid', { url: '...', colModel: [...], ... });
 * Or:    siTablerGrid(document.getElementById('manageGrid'), { ... });
 */
(function (global) {
	'use strict';

	/** Labels from window.SI_GRID_LANG (set in header.blade.php); English fallbacks if unset. */
	function siGridStr(key, fallback) {
		var g = (typeof window !== 'undefined' && window.SI_GRID_LANG) ? window.SI_GRID_LANG : {};
		var v = g[key];
		return (v !== undefined && v !== null && String(v) !== '') ? v : fallback;
	}

	var defaults = {
		url: '',
		method: 'GET',
		dataType: 'xml',
		colModel: [],
		searchitems: [],
		sortname: '',
		sortorder: 'asc',
		usepager: true,
		rp: 10,
		page: 1,
		total: 0,
		pagestat: 'Displaying {from} to {to} of {total} items',
		procmsg: 'Processing, please wait ...',
		nomsg: 'No items',
		pagemsg: 'Page',
		ofmsg: 'of',
		useRp: true,
		rpOptions: [10, 20, 50, 100],
		params: null,
		onSuccess: null,
		statusLabels: { enabled: 'Enabled', disabled: 'Disabled' },
		useCard: true,
		toolbarSelector: null,
		showReloadButton: false
	};

	function parseXml(xml) {
		if (typeof xml === 'string') {
			var parser = new DOMParser();
			return parser.parseFromString(xml, 'text/xml');
		}
		return xml;
	}

	function getTextContent(node) {
		if (!node || !node.textContent) return '';
		return node.textContent;
	}

	function escapeHtml(text) {
		if (text == null) return '';
		var div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	function deepExtend(target, src) {
		var key;
		for (key in src) {
			if (src.hasOwnProperty(key)) {
				if (typeof src[key] === 'object' && src[key] !== null && !Array.isArray(src[key])) {
					if (typeof target[key] !== 'object' || target[key] === null) target[key] = {};
					deepExtend(target[key], src[key]);
				} else {
					target[key] = src[key];
				}
			}
		}
		return target;
	}

	function SiTablerGrid(container, options) {
		this.container = typeof container === 'string' ? document.querySelector(container) : container;
		if (!this.container) return;
		this.opts = deepExtend(deepExtend({}, defaults), options || {});
		this.page = this.opts.page;
		this.total = this.opts.total || 0;
		this.pages = 1;
		this.loading = false;
		this.query = '';
		this.qtype = this.opts.searchitems && this.opts.searchitems.length
			? (this.opts.searchitems.filter(function (s) { return s.isdefault; })[0] || this.opts.searchitems[0]).name
			: '';
		this.wrap = null;
		this.table = null;
		this.thead = null;
		this.tbody = null;
		this.toolbar = null;
		this.pagerStat = null;
		this.pagerInput = null;
		this.pagerRpSelect = null;
		this.pagerPagesContainer = null;
		this.init();
	}

	SiTablerGrid.prototype.init = function () {
		var self = this;
		var o = this.opts;
		this.container.classList.add('si-tabler-grid-container');
		var toolbarTarget = o.toolbarSelector ? (typeof o.toolbarSelector === 'string' ? document.querySelector(o.toolbarSelector) : o.toolbarSelector) : null;
		this.container.style.display = '';
		this.container.innerHTML = '';

		var useCard = o.useCard !== false;
		this.wrap = useCard ? document.createElement('div') : null;
		if (this.wrap) this.wrap.className = 'card';

		this.toolbar = document.createElement('div');
		this.toolbar.className = useCard ? 'card-header d-flex flex-wrap gap-2 align-items-center' : (toolbarTarget ? 'd-flex flex-wrap gap-2 align-items-center' : 'd-flex flex-wrap gap-2 align-items-center py-2 border-bottom');
		if (o.searchitems && o.searchitems.length > 0) {
			var searchWrap = document.createElement('div');
			searchWrap.className = 'd-flex flex-wrap gap-2 align-items-center ms-auto';
			var sel = document.createElement('select');
			sel.name = 'qtype';
			sel.className = 'form-select form-select-sm';
			sel.style.width = 'auto';
			o.searchitems.forEach(function (item) {
				var opt = document.createElement('option');
				opt.value = item.name;
				opt.textContent = item.display;
				if (item.isdefault) opt.selected = true;
				sel.appendChild(opt);
			});
			var input = document.createElement('input');
			input.type = 'text';
			input.name = 'q';
			input.className = 'form-control form-control-sm';
			input.placeholder = siGridStr('search_placeholder', 'Search');
			input.style.width = '10em';
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'btn btn-sm btn-primary';
			btn.textContent = siGridStr('search', 'Search');
			btn.addEventListener('click', function () {
				self.query = input.value;
				self.qtype = sel.value;
				self.page = 1;
				self.load();
			});
			searchWrap.appendChild(sel);
			searchWrap.appendChild(input);
			searchWrap.appendChild(btn);
			this.toolbar.appendChild(searchWrap);
		}
		if (o.showReloadButton === true) {
			var reload = document.createElement('button');
			reload.type = 'button';
			reload.className = 'btn btn-sm btn-outline-secondary';
			reload.title = siGridStr('reload', 'Reload');
			reload.innerHTML = '<i class="ti ti-refresh"></i>';
			reload.addEventListener('click', function () { self.load(); });
			this.toolbar.appendChild(reload);
		}
		if (this.wrap) {
			this.wrap.appendChild(this.toolbar);
		} else if (toolbarTarget) {
			toolbarTarget.appendChild(this.toolbar);
		} else {
			this.container.appendChild(this.toolbar);
		}

		var resp = document.createElement('div');
		resp.className = 'table-responsive';
		this.table = document.createElement('table');
		this.table.className = 'table table-vcenter table-hover card-table';
		this.thead = document.createElement('thead');
		var tr = document.createElement('tr');
		o.colModel.forEach(function (col, idx) {
			var th = document.createElement('th');
			th.setAttribute('data-name', col.name);
			th.setAttribute('data-idx', String(idx));
			if (col.className) {
				col.className.trim().split(/\s+/).forEach(function (cls) { th.classList.add(cls); });
			}
			var align = col.align || 'left';
			th.classList.add('text-' + (align === 'center' ? 'center' : align === 'right' ? 'end' : 'start'));
			if (col.sortable !== false) {
				th.style.cursor = 'pointer';
				th.title = siGridStr('sort_by', 'Sort by {column}').replace(/\{column\}/g, col.display);
				th.addEventListener('click', function () {
					if (self.loading) return;
					if (o.sortname === col.name) {
						o.sortorder = o.sortorder === 'asc' ? 'desc' : 'asc';
					} else {
						o.sortname = col.name;
						o.sortorder = 'asc';
					}
					self.page = 1;
					self.load();
					self.updateHeaderSort();
				});
			}
			if ('displayMobile' in col) {
				th.innerHTML = '<span class="d-none d-sm-inline">' + escapeHtml(col.display) + '</span><span class="d-sm-none">' + (col.displayMobile || '') + '</span>';
			} else {
				th.appendChild(document.createTextNode(col.display));
			}
			tr.appendChild(th);
		});
		this.thead.appendChild(tr);
		this.table.appendChild(this.thead);
		this.tbody = document.createElement('tbody');
		this.table.appendChild(this.tbody);
		resp.appendChild(this.table);
		if (this.wrap) this.wrap.appendChild(resp); else this.container.appendChild(resp);

		if (o.usepager) {
			var pager = document.createElement('div');
			pager.className = 'card-footer d-flex flex-wrap align-items-center gap-3';
			var left = document.createElement('div');
			left.className = 'd-flex align-items-center gap-2';
			if (o.useRp && o.rpOptions && o.rpOptions.length > 0) {
				this.pagerRpSelect = document.createElement('select');
				this.pagerRpSelect.className = 'form-select form-select-sm';
				this.pagerRpSelect.style.width = 'auto';
				var minRp = Math.min.apply(null, o.rpOptions);
				var rpVal = Math.max(minRp, parseInt(o.rp, 10) || minRp);
				if (rpVal !== o.rp) o.rp = rpVal;
				var opts = o.rpOptions.slice();
				if (opts.indexOf(rpVal) < 0 && rpVal >= minRp) opts.push(rpVal);
				opts.sort(function (a, b) { return a - b; });
				opts.forEach(function (n) {
					var opt = document.createElement('option');
					opt.value = String(n);
					opt.textContent = String(n);
					if (n === rpVal) opt.selected = true;
					self.pagerRpSelect.appendChild(opt);
				});
				this.pagerRpSelect.addEventListener('change', function () {
					self.opts.rp = parseInt(self.pagerRpSelect.value, 10);
					self.page = 1;
					self.load();
				});
				left.appendChild(this.pagerRpSelect);
				var recordsLbl = document.createElement('span');
				recordsLbl.className = 'text-secondary';
				recordsLbl.textContent = siGridStr('records', 'records');
				left.appendChild(recordsLbl);
			}
			pager.appendChild(left);
			var right = document.createElement('div');
			right.className = 'd-flex align-items-center gap-3 ms-auto';
			this.pagerStat = document.createElement('div');
			this.pagerStat.className = 'text-secondary small';
			right.appendChild(this.pagerStat);
			this.pagerPagesContainer = document.createElement('div');
			this.pagerPagesContainer.className = 'd-flex align-items-center gap-1';
			right.appendChild(this.pagerPagesContainer);
			pager.appendChild(right);
			if (this.wrap) {
				this.wrap.appendChild(pager);
			} else {
				// When useCard is false (e.g. invoices manage), append pager to card (grandparent)
				// so footer sits below card-table and is visible. Append synchronously so load() and
				// updatePager() run in the same flow and pagination shows correctly on first view.
				var pagerParent = this.container.parentNode && this.container.parentNode.parentNode
					? this.container.parentNode.parentNode
					: this.container.parentNode;
				if (pagerParent) pagerParent.appendChild(pager); else this.container.appendChild(pager);
			}
		}

		if (this.wrap) this.container.appendChild(this.wrap);
		this.updateHeaderSort();
		this.updatePager();
		this.load();
	};

	SiTablerGrid.prototype.updateHeaderSort = function () {
		var o = this.opts;
		var ths = this.thead.querySelectorAll('th');
		for (var i = 0; i < ths.length; i++) {
			var th = ths[i];
			var icon = th.querySelector('.ti');
			if (icon) icon.remove();
			if (th.getAttribute('data-name') === o.sortname) {
				var ico = document.createElement('i');
				ico.className = 'ti ti-chevron-' + (o.sortorder === 'asc' ? 'up' : 'down');
				th.appendChild(document.createTextNode(' '));
				th.appendChild(ico);
			}
		}
	};

	SiTablerGrid.prototype.load = function () {
		var self = this;
		var o = this.opts;
		if (this.loading || !o.url) return;
		var rp = Math.max(1, parseInt(o.rp, 10) || 10);
		if (rp !== o.rp) o.rp = rp;
		this.loading = true;
		if (this.pagerStat) this.pagerStat.textContent = o.procmsg;
		this.tbody.innerHTML = '<tr><td colspan="' + o.colModel.length + '" class="text-center text-secondary">' + o.procmsg + '</td></tr>';

		var param = {
			page: Math.max(1, this.page),
			rp: o.rp,
			sortname: o.sortname,
			sortorder: o.sortorder,
			query: this.query,
			qtype: this.qtype
		};
		if (o.params && Array.isArray(o.params)) {
			o.params.forEach(function (p) { param[p.name] = p.value; });
		}
		var queryString = new URLSearchParams(param).toString();
		var isGet = (o.method || 'GET').toUpperCase() === 'GET';
		var requestUrl = isGet ? (o.url + (o.url.indexOf('?') >= 0 ? '&' : '?') + queryString) : o.url;
		var fetchOpts = {
			method: o.method || 'GET',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		};
		if (!isGet) fetchOpts.body = queryString;

		fetch(requestUrl, { credentials: 'same-origin', ...fetchOpts })
			.then(function (res) {
				if (!res.ok) {
					throw new Error('HTTP ' + res.status);
				}
				return res.text();
			})
			.then(function (text) {
				self.loading = false;
				var doc = parseXml(text);
				// Detect XML parse error (e.g. HTML error page)
				var parseErr = doc.querySelector('parsererror');
				if (parseErr) {
					self.tbody.innerHTML = '<tr><td colspan="' + o.colModel.length + '" class="text-center text-danger">' + escapeHtml(siGridStr('invalid_xml', 'Invalid XML response')) + '</td></tr>';
					if (self.pagerStat) self.pagerStat.textContent = '';
					return;
				}
				var rowsEl = doc.querySelector('rows');
				var total = 0;
				var page = 1;
				if (rowsEl) {
					var totalEl = rowsEl.querySelector('total');
					var pageEl = rowsEl.querySelector('page');
					if (totalEl) total = parseInt(getTextContent(totalEl), 10) || 0;
					if (pageEl) page = parseInt(getTextContent(pageEl), 10) || 1;
				}
				self.total = total;
				self.page = Math.max(1, parseInt(page, 10) || 1);
				var rp = Math.max(1, parseInt(o.rp, 10) || 10);
				if (rp !== o.rp) o.rp = rp;
				self.pages = Math.max(1, Math.ceil(total / rp));
				self.renderRows(doc);
				self.updatePager();
				if (o.onSuccess) o.onSuccess();
			})
			.catch(function () {
				self.loading = false;
				self.tbody.innerHTML = '<tr><td colspan="' + o.colModel.length + '" class="text-center text-danger">' + escapeHtml(siGridStr('connection_error', 'Connection error')) + '</td></tr>';
				if (self.pagerStat) self.pagerStat.textContent = '';
			});
	};

	SiTablerGrid.prototype.renderRows = function (doc) {
		var o = this.opts;
		var rowNodes = doc.querySelectorAll('rows > row');
		this.tbody.innerHTML = '';
		if (!rowNodes.length) {
			this.tbody.innerHTML = '<tr><td colspan="' + o.colModel.length + '" class="text-center text-secondary">' + o.nomsg + '</td></tr>';
			return;
		}
		for (var r = 0; r < rowNodes.length; r++) {
			var rowEl = rowNodes[r];
			var id = rowEl.getAttribute('id');
			var tr = document.createElement('tr');
			if (id) tr.id = 'row' + id;
			var cells = rowEl.querySelectorAll('cell');
			for (var c = 0; c < cells.length && c < o.colModel.length; c++) {
				var td = document.createElement('td');
				if (o.colModel[c].className) {
					o.colModel[c].className.trim().split(/\s+/).forEach(function (cls) { td.classList.add(cls); });
				}
				var align = o.colModel[c].align || 'left';
				td.classList.add('text-' + (align === 'center' ? 'center' : align === 'right' ? 'end' : 'start'));
				var content = getTextContent(cells[c]) || '\u00A0';
				if (o.colModel[c].name === 'enabled') {
					var raw = (content + '').toLowerCase();
					var isEnabled = raw.indexOf('disabled') < 0 && (raw.indexOf('enabled') >= 0 || raw.indexOf('tick') >= 0 || raw.indexOf('1') >= 0 || raw.indexOf('yes') >= 0);
					var labels = o.statusLabels || { enabled: 'Enabled', disabled: 'Disabled' };
					content = isEnabled
						? '<span class="status status-green">' + (labels.enabled || 'Enabled') + '</span>'
						: '<span class="status status-red">' + (labels.disabled || 'Disabled') + '</span>';
				}
				td.innerHTML = content;
				tr.appendChild(td);
			}
			this.tbody.appendChild(tr);
		}
	};

	SiTablerGrid.prototype.updatePager = function () {
		var self = this;
		var o = this.opts;
		if (!this.pagerStat) return;
		var rp = Math.max(1, parseInt(o.rp, 10) || 10);
		var page = Math.max(1, this.page);
		var from = this.total === 0 ? 0 : (page - 1) * rp + 1;
		var to = this.total === 0 ? 0 : Math.min(page * rp, this.total);
		var msg = (o.pagestat || siGridStr('pagestat_fallback', 'Displaying {from} to {to} of {total} items'))
			.replace('{from}', from).replace('{to}', to).replace('{total}', this.total);
		this.pagerStat.textContent = msg;
		if (this.pagerRpSelect && this.pagerRpSelect.querySelector('option[value="' + o.rp + '"]')) {
			this.pagerRpSelect.value = String(o.rp);
		}
		if (this.pagerPagesContainer) {
			this.pagerPagesContainer.innerHTML = '';
			var nav = document.createElement('nav');
			var ul = document.createElement('ul');
			ul.className = 'pagination mb-0';
			var addPageItem = function (label, active, disabled, clickPage) {
				var li = document.createElement('li');
				li.className = 'page-item' + (active ? ' active' : '') + (disabled ? ' disabled' : '');
				var isEllipsis = label === '...';
				var el = document.createElement(isEllipsis ? 'span' : 'a');
				el.className = 'page-link';
				if (!isEllipsis) el.href = '#';
				el.textContent = label;
				if (!isEllipsis) {
					el.addEventListener('click', function (e) {
						e.preventDefault();
						if (disabled) return;
						if (label === 'Prev') { if (self.page > 1) { self.page--; self.load(); } }
						else if (label === 'Next') { if (self.page < self.pages) { self.page++; self.load(); } }
						else if (clickPage !== undefined) { self.page = clickPage; self.load(); }
					});
				}
				li.appendChild(el);
				ul.appendChild(li);
			};
			//addPageItem('Prev', false, this.page <= 1);
			if (this.pages === 0) {
				addPageItem('1', false, true);
			} else {
				var maxVisible = 3;
				var start = Math.max(1, Math.min(this.page - 1, this.pages - maxVisible + 1));
				start = Math.max(1, start);
				var end = Math.min(this.pages, start + maxVisible - 1);
				if (start > 1) {
					addPageItem('1', this.page === 1, false, 1);
					if (start > 2) addPageItem('...', false, true);
				}
				for (var p = start; p <= end; p++) {
					addPageItem(String(p), p === this.page, false, p);
				}
				if (end < this.pages) {
					if (end < this.pages - 1) addPageItem('...', false, true);
					addPageItem(String(this.pages), this.page === this.pages, false, this.pages);
				}
			}
			//addPageItem('Next', false, this.page >= this.pages || this.pages === 0);
			nav.appendChild(ul);
			this.pagerPagesContainer.appendChild(nav);
		}
	};

	SiTablerGrid.prototype.reload = function () {
		this.load();
	};

	function siTablerGrid(selectorOrEl, options) {
		var el = typeof selectorOrEl === 'string' ? document.querySelector(selectorOrEl) : selectorOrEl;
		if (!el) return null;
		var inst = new SiTablerGrid(el, options);
		el._siTablerGrid = inst;
		return inst;
	}

	global.siTablerGrid = siTablerGrid;
})(typeof window !== 'undefined' ? window : this);
