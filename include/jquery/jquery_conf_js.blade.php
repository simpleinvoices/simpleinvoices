<script type="text/javascript">
var si_conf_delete_line_item = @json($config->confirm->deleteLineItem ?? false);
var si_conf_spreadsheet = @json($config->export->spreadsheet ?? 'xls');
var si_conf_wordprocessor = @json($config->export->wordprocessor ?? 'doc');
var si_lang_description_conf = @json($LANG['description'] ?? 'Description');
</script>
@verbatim
<script type="text/javascript">
(function() {
	function init() {
		if (document.querySelector('.showdownloads')) {
			document.querySelectorAll('.showdownloads').forEach(function(el){
				el.setAttribute('data-bs-toggle', 'dropdown');
				el.setAttribute('aria-expanded', 'false');
				if (el.nextElementSibling && !el.nextElementSibling.classList.contains('dropdown-menu')) el.nextElementSibling.classList.add('dropdown-menu');
				if (el.parentNode && !el.parentNode.classList.contains('dropdown')) el.parentNode.classList.add('dropdown');
			});
		}
		if (document.querySelector('#custom-tab-by-hash')) {
			document.querySelector('#custom-tab-by-hash').addEventListener('click', function(e) { var w = window.open(this.href, '', 'directories,location,menubar,resizable,scrollbars,status,toolbar'); if (w) w.focus(); e.preventDefault(); });
		}
		// Help links: Bootstrap/Tabler popovers with content from rel URL (si-help-popover.js)

	if (!(
		(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i))
		&& navigator.userAgent.match(/CPU\sOS\s[0123]_\d/i)))
	{
		if (window.hugeRTE) {
			var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
			var sharedOpts = {
				base_url: 'https://cdn.jsdelivr.net/npm/hugerte@1.0.10',
				suffix: '.min',
				menubar: false,
				statusbar: false,
				promotion: false,
				branding: false,
				content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
			};
			if (isDark) { sharedOpts.skin = 'oxide-dark'; sharedOpts.content_css = 'dark'; }

			if (document.querySelector('textarea.editor')) {
				window.hugeRTE.init(Object.assign({}, sharedOpts, {
					selector: 'textarea.editor',
					plugins: 'lists code',
					toolbar: 'undo redo | bold italic | bullist numlist | removeformat | code',
					height: 220
				}));
			}

			if (document.querySelector('textarea.detail-editor')) {
				window.hugeRTE.init(Object.assign({}, sharedOpts, {
					selector: 'textarea.detail-editor',
					plugins: 'lists autoresize',
					toolbar: 'bold italic | bullist numlist',
					min_height: 50,
					max_height: 150
				}));
			}
		}
	}

	document.addEventListener('change', function(e) {
		if (e.target && e.target.matches && e.target.matches('.product_change')) {
			var row_number = e.target.getAttribute('rel');
			var product = e.target.value;
			var qel = document.getElementById('quantity'+row_number);
			invoice_product_change(product, row_number, qel ? qel.value : '');
			siLog('debug', si_lang_description_conf);
		}
		if (e.target && e.target.matches && e.target.matches('.product_inventory_change')) {
			var costEl = document.getElementById('cost');
			product_inventory_change(e.target.value, costEl ? costEl.value : null);
		}
	});
	document.addEventListener('click', function(e) {
		var t = e.target.closest ? e.target.closest('.trash_link') : null;
		if (t) { e.preventDefault(); var id = t.getAttribute('rel'); if (si_conf_delete_line_item && window.siConfirmDeleteModal) siConfirmDeleteModal.open(function(){ delete_row(id); }); else delete_row(id); return; }
		t = e.target.closest ? e.target.closest('.trash_link_edit') : null;
		if (t) { e.preventDefault(); var id = t.getAttribute('rel'); if (si_conf_delete_line_item && window.siConfirmDeleteModal) siConfirmDeleteModal.open(function(){ delete_line_item(id); }); else delete_line_item(id); return; }
		if (e.target.closest && e.target.closest('.add_line_item')) { e.preventDefault(); add_line_item(); return; }
		if (e.target.closest && e.target.closest('.invoice_save')) { var g = document.getElementById('gmail_loading'); if (g) g.style.display = ''; siLog('debug','invoice save'); count_invoice_line_items(); if (g) g.style.display = 'none'; return; }
		if (e.target.closest && e.target.closest('.export_window')) { var ed = document.getElementById('export_dialog'); if (ed && window.bootstrap && window.bootstrap.Modal) { var m = bootstrap.Modal.getInstance(ed); if (m) m.hide(); } return; }
		t = e.target.closest ? e.target.closest('.invoice_export_dialog') : null;
		if (t) { e.preventDefault(); export_invoice(t.getAttribute('rel'), si_conf_spreadsheet, si_conf_wordprocessor); }
	});
	document.querySelectorAll('.detail').forEach(function(el) {
		if (!el.value || el.value === '') { el.value = si_lang_description_conf; el.style.color = '#b2adad'; }
		el.addEventListener('focus', function() { if (this.value === si_lang_description_conf) { this.value = ''; this.style.color = '#333'; } });
		el.addEventListener('blur', function() { if (this.value === '') { this.value = si_lang_description_conf; this.style.color = '#b2adad'; } });
	});
	document.addEventListener('focus', function(e) { if (e.target && e.target.matches && e.target.matches('.detail') && e.target.value === si_lang_description_conf) { e.target.value = ''; e.target.style.color = '#333'; } }, true);
	document.addEventListener('blur', function(e) { if (e.target && e.target.matches && e.target.matches('.detail') && e.target.value === '') { e.target.value = si_lang_description_conf; e.target.style.color = '#b2adad'; } }, true);
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
</script>
@endverbatim
