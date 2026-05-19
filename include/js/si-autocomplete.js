/**
 * Vanilla autocomplete for #ac_me (payments process) - replaces jQuery autocomplete
 */
(function () {
	'use strict';
	var acUrl = 'index.php?module=payments&view=process_ajax';
	function init() {
		var input = document.getElementById('ac_me');
		if (!input) return;
		var list = null;
		var cache = {};
		input.addEventListener('input', function () {
			var q = (input.value || '').trim();
			if (q.length < 1) { hide(); return; }
			if (cache[q]) { show(cache[q]); return; }
			fetch(acUrl + '&q=' + encodeURIComponent(q)).then(function (r) { return r.text(); }).then(function (text) {
				var rows = text.split('\n').map(function (line) { return line.split('|'); }).filter(function (r) { return r.length; });
				cache[q] = rows;
				show(rows);
			}).catch(function () { hide(); });
		});
		input.addEventListener('blur', function () { setTimeout(hide, 200); });
		function show(rows) {
			hide();
			list = document.createElement('ul');
			list.className = 'list-group position-absolute';
			list.style.zIndex = '1050';
			list.style.maxHeight = '200px';
			list.style.overflowY = 'auto';
			rows.forEach(function (row) {
				var li = document.createElement('li');
				li.className = 'list-group-item list-group-item-action';
				li.innerHTML = (row[0] || '') + (row[1] ? '<br><small class="text-muted">' + row[1] + '</small>' : '');
				li._row = row;
				li.addEventListener('mousedown', function (e) { e.preventDefault(); select(li); });
				list.appendChild(li);
			});
			input.parentNode.style.position = 'relative';
			input.parentNode.appendChild(list);
		}
		function hide() {
			if (list && list.parentNode) list.parentNode.removeChild(list);
			list = null;
		}
		function select(li) {
			var row = li._row;
			if (row && row[0]) input.value = row[0];
			if (typeof window.selectItem === 'function') {
				var fake = { extra: row ? row.slice(1) : [] };
				window.selectItem(fake);
			}
			hide();
		}
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
