/**
 * Bootstrap 5 / Tabler replacements for jQuery UI and plugins.
 * - Confirm delete modal (replaces jQuery UI dialog)
 * - Tab enhancer for #tabs_customer and #tabmenu (replaces jQuery UI tabs)
 * - Event delegation (replaces livequery)
 */
(function () {
	'use strict';

	var confirmModalEl = null;
	var confirmDeleteFn = null;

	function createConfirmModal() {
		if (document.getElementById('confirm_delete_line_item')) return;
		var container = document.getElementById('Container') || document.body;
		var div = document.createElement('div');
		div.className = 'modal fade';
		div.id = 'confirm_delete_line_item';
		div.tabIndex = -1;
		div.setAttribute('aria-labelledby', 'confirm_delete_line_item_title');
		div.setAttribute('aria-hidden', 'true');
		div.innerHTML =
			'<div class="modal-dialog modal-dialog-centered">' +
			'<div class="modal-content">' +
			'<div class="modal-header">' +
			'<h5 class="modal-title" id="confirm_delete_line_item_title">Delete this line item?</h5>' +
			'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
			'</div>' +
			'<div class="modal-body">If you choose "Delete" the line item will be removed on Save.</div>' +
			'<div class="modal-footer">' +
			'<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>' +
			'<button type="button" class="btn btn-danger" id="confirm_delete_line_item_btn">Delete</button>' +
			'</div></div></div>';
		container.appendChild(div);
		confirmModalEl = div;
		var btn = div.querySelector('#confirm_delete_line_item_btn');
		if (btn) {
			btn.addEventListener('click', function () {
				if (typeof confirmDeleteFn === 'function') confirmDeleteFn();
				confirmDeleteFn = null;
				var Modal = window.tabler && window.tabler.Modal;
				var m = div._bsModal || (Modal && Modal.getInstance(div));
				if (m) m.hide();
			});
		}
	}

	window.siConfirmDeleteModal = {
		open: function (deleteFn) {
			confirmDeleteFn = deleteFn;
			if (!confirmModalEl) createConfirmModal();
			if (window.tabler && window.tabler.Modal && confirmModalEl) {
				var m = window.tabler.Modal.getOrCreateInstance(confirmModalEl);
				m.show();
			}
		}
	};

	function enhanceTabs() {
		var tc = document.getElementById('tabs_customer');
		if (tc && !tc.querySelector('.nav-tabs')) {
			var ul = tc.querySelector('ul.anchors');
			if (ul) {
				ul.classList.add('nav', 'nav-tabs', 'nav-fill', 'mb-3');
				ul.setAttribute('role', 'tablist');
				ul.querySelectorAll('li a').forEach(function (a, i) {
					a.classList.add('nav-link');
					if (i === 0) a.classList.add('active');
					a.setAttribute('data-bs-toggle', 'tab');
					a.setAttribute('role', 'tab');
				});
				var fragments = [].slice.call(tc.querySelectorAll('div.fragment, div[id^="section-"]'));
				var wrap = document.createElement('div');
				wrap.className = 'tab-content';
				fragments.forEach(function (frag, i) {
					frag.classList.add('tab-pane');
					frag.setAttribute('role', 'tabpanel');
					if (i === 0) frag.classList.add('active');
					wrap.appendChild(frag);
				});
				tc.appendChild(wrap);
			}
		}
		var tabmenu = document.querySelector('#tabmenu > ul');
		if (tabmenu && !tabmenu.classList.contains('nav-tabs')) {
			tabmenu.classList.add('nav', 'nav-tabs');
			tabmenu.querySelectorAll('a').forEach(function (a) {
				a.classList.add('nav-link');
				var href = a.getAttribute('href');
				if (href && href.indexOf('#') === 0) {
					a.setAttribute('data-bs-toggle', 'tab');
					a.setAttribute('role', 'tab');
				}
			});
		}
	}

	/**
	 * Select tab in #tabmenu by index (0-based) or by href (e.g. '#setting').
	 * Call after enhanceTabs(); e.g. window.siSelectTabmenuTab(0) or siSelectTabmenuTab('#money')
	 */
	window.siSelectTabmenuTab = function (activeTab) {
		var ul = document.querySelector('#tabmenu > ul');
		if (!ul) return;
		var links = ul.querySelectorAll('a.nav-link[data-bs-toggle="tab"]');
		if (!links.length) return;
		var idx = -1;
		if (typeof activeTab === 'number' && activeTab >= 0 && activeTab < links.length) idx = activeTab;
		else if (/^\d+$/.test(String(activeTab).trim())) idx = parseInt(activeTab, 10);
		if (idx >= 0) {
			var link = links[idx];
			if (window.tabler && window.tabler.Tab && link) {
				window.tabler.Tab.getOrCreateInstance(link).show();
			}
			return;
		}
		var want = String(activeTab).trim();
		if (want.indexOf('#') !== 0) want = '#' + want;
		for (var i = 0; i < links.length; i++) {
			if (links[i].getAttribute('href') === want) {
				if (window.tabler && window.tabler.Tab) window.tabler.Tab.getOrCreateInstance(links[i]).show();
				break;
			}
		}
	};

	function init() {
		createConfirmModal();
		enhanceTabs();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
