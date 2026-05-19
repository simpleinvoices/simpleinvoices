/**
 * Bootstrap / Tabler help popovers.
 * Replaces cluetip: shows help content loaded from the linked documentation page (rel URL).
 * Content is extracted from the fetched page (#left or .page-body .container-xl).
 */
(function () {
	'use strict';

	var contentCache = {};
	var CONTENT_SELECTOR = '#left'; // documentation view wraps content in #left
	var FALLBACK_SELECTOR = '.page-body .container-xl';

	function extractContent(html) {
		try {
			var parser = new DOMParser();
			var doc = parser.parseFromString(html, 'text/html');
			var el = doc.querySelector(CONTENT_SELECTOR) || doc.querySelector(FALLBACK_SELECTOR);
			return el ? el.innerHTML.trim() : html;
		} catch (e) {
			return html;
		}
	}

	function loadHelpContent(url) {
		if (contentCache[url]) return Promise.resolve(contentCache[url]);
		return fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return r.text(); })
			.then(function (html) {
				var content = extractContent(html);
				contentCache[url] = content;
				return content;
			})
			.catch(function () {
				return '<p class="text-danger mb-0">Unable to load help content.</p>';
			});
	}

	function initHelpPopovers() {
		if (!window.tabler || !window.tabler.Popover) return;
		var links = document.querySelectorAll('a.cluetip[rel]');
		links.forEach(function (el) {
			var url = el.getAttribute('rel');
			var title = el.getAttribute('title') || '';
			if (!url) return;
			el.addEventListener('click', function (e) {
				e.preventDefault();
			});
			var popover = new window.tabler.Popover(el, {
				trigger: 'click',
				html: true,
				title: title,
				content: '<span class="text-muted">Loading…</span>',
				sanitize: false,
				customClass: 'si-help-popover',
				placement: 'auto'
			});
			el.addEventListener('show.bs.popover', function () {
				if (contentCache[url]) {
					popover.setContent({ '.popover-body': contentCache[url] });
				}
			});
			el.addEventListener('shown.bs.popover', function () {
				if (contentCache[url]) return;
				loadHelpContent(url).then(function (content) {
					popover.setContent({ '.popover-body': content });
				});
			});
		});
		// Close on click outside or Escape (Bootstrap-style dismiss)
		document.addEventListener('click', function (e) {
			var target = e.target;
			document.querySelectorAll('a.cluetip[rel]').forEach(function (link) {
				var inst = window.tabler.Popover.getInstance(link);
				if (!inst) return;
				var tipId = link.getAttribute('aria-describedby');
				var tip = tipId ? document.getElementById(tipId) : null;
				if (!tip || !tip.classList.contains('show')) return;
				if (link === target || link.contains(target) || tip === target || tip.contains(target)) return;
				inst.hide();
			});
		}, true);
		document.addEventListener('keydown', function (e) {
			if (e.key !== 'Escape') return;
			document.querySelectorAll('a.cluetip[rel]').forEach(function (link) {
				var inst = window.tabler.Popover.getInstance(link);
				if (inst) inst.hide();
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHelpPopovers);
	} else {
		initHelpPopovers();
	}
})();
