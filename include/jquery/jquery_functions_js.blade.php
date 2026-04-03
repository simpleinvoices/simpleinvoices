<script type="text/javascript">var si_lang_description = @json($LANG['description'] ?? 'Description');</script>
@verbatim
<script type="text/javascript">
	/*
	 * Script: invoice/line-item functions (vanilla JS, no jQuery)
	 */
	function selectItem(li) {
		if (li && li.extra) {
			var el = document.getElementById("js_total");
			if (el) el.innerHTML = " " + li.extra[0] + " ";
		}
	}
	function formatItem(row) {
		return row[0] + "<br><i>" + row[1] + "</i>";
	}

	function delete_row(row_number) {
		var el = document.getElementById('row' + row_number);
		if (el) el.remove();
	}

	function delete_line_item(row_number) {
		var rowEl = document.getElementById('row' + row_number);
		if (rowEl) rowEl.style.display = 'none';
		var q = document.getElementById('quantity' + row_number);
		if (q) q.removeAttribute('value');
		var d = document.getElementById('delete' + row_number);
		if (d) d.setAttribute('value', 'yes');
	}

	function invoice_product_change(product, row_number, quantity) {
		var loading = document.getElementById('gmail_loading');
		if (loading) loading.style.display = '';
		fetch('./index.php?module=invoices&view=product_ajax&id=' + encodeURIComponent(product) + '&row=' + encodeURIComponent(row_number))
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (loading) loading.style.display = 'none';
				var jsonHtml = document.getElementById('json_html' + row_number);
				if (jsonHtml) jsonHtml.remove();
				var qEl = document.getElementById('quantity' + row_number);
				if (qEl && quantity === '') { qEl.value = '1'; qEl.setAttribute('value', '1'); }
				var up = document.getElementById('unit_price' + row_number);
				if (up) { up.value = data.unit_price || ''; up.setAttribute('value', data.unit_price || ''); }
				var t0 = document.querySelector('#row' + row_number + ' [name="tax_id[' + row_number + '][0]"]');
				if (t0) t0.value = data.default_tax_id != null ? data.default_tax_id : '';
				var t1 = document.querySelector('#row' + row_number + ' [name="tax_id[' + row_number + '][1]"]');
				if (t1) t1.value = (data.default_tax_id_2 != null) ? data.default_tax_id_2 : '';
				var detailsTr = document.querySelectorAll('#row' + row_number + ' .details');
				var globalShowActive = !!document.querySelector('.si-toggle-all-desc[data-show="1"] input:checked');
				detailsTr.forEach(function (tr) {
					if (data.show_description === 'Y' || globalShowActive) tr.classList.remove('si_hide');
					else tr.classList.add('si_hide');
				});
				var descEl = document.getElementById('description' + row_number);
				if (descEl) {
					var ed = window.hugeRTE ? window.hugeRTE.get('description' + row_number) : null;
					var currentVal = ed ? ed.getContent({ format: 'text' }).trim() : descEl.value;
					if (!currentVal || currentVal === descEl.getAttribute('rel') || currentVal === si_lang_description) {
						var newContent = data.notes_as_description === 'Y' ? (data.notes || '') : '';
						if (ed) {
							ed.setContent(newContent);
						} else {
							descEl.value = newContent || si_lang_description;
							descEl.setAttribute('rel', newContent || si_lang_description);
						}
					}
				}
				if (data.json_html && data.json_html !== '') {
					var rowEl = document.getElementById('row' + row_number);
					var details = rowEl ? rowEl.querySelector('.details') : null;
					if (rowEl && details) {
						var wrap = document.createElement('div');
						wrap.innerHTML = data.json_html;
						while (wrap.firstChild) rowEl.insertBefore(wrap.firstChild, details);
					}
				}
			})
			.catch(function () { if (loading) loading.style.display = 'none'; });
	}
	window.invoice_product_change_price = invoice_product_change;

	function product_inventory_change(product, existing_cost) {
		if (existing_cost == null) return;
		var loading = document.getElementById('gmail_loading');
		if (loading) loading.style.display = '';
		fetch('./index.php?module=invoices&view=product_inventory_ajax&id=' + encodeURIComponent(product))
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (loading) loading.style.display = 'none';
				var costEl = document.getElementById('cost');
				if (costEl) costEl.setAttribute('value', data.cost != null ? data.cost : '');
			})
			.catch(function () { if (loading) loading.style.display = 'none'; });
	}

	function count_invoice_line_items() {
		var itemtable = document.getElementById('itemtable');
		if (!itemtable) return;
		var rows = itemtable.querySelectorAll('.line_item');
		if (!rows.length) return;
		var lastRow = rows[rows.length - 1];
		var qInput = lastRow.querySelector('input[id^="quantity"]');
		if (!qInput) return;
		var id = qInput.getAttribute('id') || '';
		var rowID_last = parseInt(id.slice(8), 10) || 0;
		var maxItems = document.getElementById('max_items');
		if (maxItems) maxItems.setAttribute('value', String(rowID_last));
		siLog('debug', 'Max Items = ' + rowID_last);
	}

	function siLog(level, message) {
		if (window.console && console[level]) console[level](message);
		else if (window.console && console.log) console.log('[' + level + '] ' + message);
	}

	function add_line_item() {
		var loading = document.getElementById('gmail_loading');
		if (loading) loading.style.display = '';
		var itemtable = document.getElementById('itemtable');
		if (!itemtable) { if (loading) loading.style.display = 'none'; return; }
		var lineItems = itemtable.querySelectorAll('.line_item');
		if (!lineItems.length) { if (loading) loading.style.display = 'none'; return; }
		var firstRow = lineItems[0];
		var lastRow = lineItems[lineItems.length - 1];
		var clonedRow = firstRow.cloneNode(true);
		var qOld = clonedRow.querySelector('input[id^="quantity"]');
		var qLast = lastRow.querySelector('input[id^="quantity"]');
		if (!qOld || !qLast) { if (loading) loading.style.display = 'none'; return; }
		var rowID_old = parseInt((qOld.getAttribute('id') || '').slice(8), 10) || 0;
		var rowID_last = parseInt((qLast.getAttribute('id') || '').slice(8), 10) || 0;
		var rowID_new = rowID_last + 1;
		siLog('debug', 'Line item ' + rowID_new + ' added');
		clonedRow.setAttribute('id', 'row' + rowID_new);
		function setAttr(el, att, val) { if (el) el.setAttribute(att, val); }
		function byId(r, id) { return clonedRow.querySelector('#' + id) || clonedRow.querySelector('[id="' + id + '"]'); }
		var trashLink = byId(clonedRow, 'trash_link' + rowID_old); if (trashLink) { trashLink.id = 'trash_link' + rowID_new; trashLink.name = 'trash_link' + rowID_new; trashLink.href = '#'; trashLink.setAttribute('rel', String(rowID_new)); }
		var trashLinkEdit = byId(clonedRow, 'trash_link_edit' + rowID_old); if (trashLinkEdit) { trashLinkEdit.id = 'trash_link_edit' + rowID_new; trashLinkEdit.name = 'trash_link_edit' + rowID_new; trashLinkEdit.href = '#'; trashLinkEdit.setAttribute('rel', String(rowID_new)); }
		// Row 0 has a hidden placeholder delete segment — replace it with a real one
		var placeholder = clonedRow.querySelector('.si-del-placeholder');
		if (placeholder) {
			var isEdit = !!document.getElementById('delete0');
			var linkClass = isEdit ? 'trash_link_edit' : 'trash_link';
			var linkId = isEdit ? 'trash_link_edit' + rowID_new : 'trash_link' + rowID_new;
			var existingBtn = itemtable.querySelector('.' + linkClass);
			var linkTitle = existingBtn ? (existingBtn.getAttribute('title') || '') : '';
			var newLabel = document.createElement('label');
			newLabel.id = linkId;
			newLabel.className = 'segmented-control-item ' + linkClass;
			newLabel.setAttribute('rel', String(rowID_new));
			if (linkTitle) newLabel.setAttribute('title', linkTitle);
			var inp = document.createElement('input');
			inp.type = 'radio';
			inp.className = 'segmented-control-input';
			var spanLabel = document.createElement('span');
			spanLabel.className = 'segmented-control-label';
			var icon = document.createElement('i');
			icon.className = 'ti ti-trash';
			if (isEdit) icon.id = 'delete_image' + rowID_new;
			spanLabel.appendChild(icon);
			newLabel.appendChild(inp);
			newLabel.appendChild(spanLabel);
			placeholder.parentNode.replaceChild(newLabel, placeholder);
		}
		var del = byId(clonedRow, 'delete' + rowID_old); if (del) { del.id = 'delete' + rowID_new; del.name = 'delete' + rowID_new; }
		var delImg = byId(clonedRow, 'delete_image' + rowID_old); if (delImg) { delImg.id = 'delete_image' + rowID_new; delImg.name = 'delete_image' + rowID_new; delImg.src = './images/common/delete_item.png'; }
		var trashImg = clonedRow.querySelector('#trash_image' + rowID_old); if (trashImg) trashImg.src = './images/common/delete_item.png';
		var lineItem = byId(clonedRow, 'line_item' + rowID_old); if (lineItem) { lineItem.id = 'line_item' + rowID_new; lineItem.name = 'line_item' + rowID_new; lineItem.value = ''; }
		var qNew = byId(clonedRow, 'quantity' + rowID_old); if (qNew) { qNew.id = 'quantity' + rowID_new; qNew.name = 'quantity' + rowID_new; qNew.removeAttribute('value'); qNew.value = ''; qNew.classList.remove('validate[required]'); }
		var products = byId(clonedRow, 'products' + rowID_old); if (products) {
			products.setAttribute('rel', String(rowID_new)); products.id = 'products' + rowID_new; products.name = 'products' + rowID_new;
			products.querySelectorAll('option').forEach(function (o) { o.removeAttribute('selected'); });
			if (!products.querySelector('option[value=""]')) {
				var emptyOpt = document.createElement('option'); emptyOpt.value = ''; emptyOpt.textContent = '';
				products.insertBefore(emptyOpt, products.firstChild);
			}
			products.selectedIndex = 0;
			products.classList.remove('validate[required]');
		}
		var upOld = clonedRow.querySelector('#unit_price' + rowID_old); if (upOld) { upOld.id = 'unit_price' + rowID_new; upOld.name = 'unit_price' + rowID_new; upOld.value = ''; upOld.removeAttribute('value'); upOld.classList.remove('validate[required]'); }
		var descOld = clonedRow.querySelector('#description' + rowID_old);
		if (descOld) {
			descOld.id   = 'description' + rowID_new;
			descOld.name = 'description' + rowID_new;
			descOld.value = '';
			descOld.removeAttribute('style');
			descOld.classList.remove('validate[required]');
		}
		// Strip any cloned hugeRTE wrapper and restore textarea visibility
		clonedRow.querySelectorAll('.tox-tinymce').forEach(function(el) { el.remove(); });
		if (descOld) descOld.style.display = '';
		var globalShowActiveAdd = !!document.querySelector('.si-toggle-all-desc[data-show="1"] input:checked');
		clonedRow.querySelectorAll('.details').forEach(function (el) {
			if (globalShowActiveAdd) el.classList.remove('si_hide');
			else el.classList.add('si_hide');
			el.classList.remove('si_show');
			el.style.display = '';
		});
		var expandBtn = clonedRow.querySelector('.si-expand-desc');
		if (expandBtn) {
			var expandIcon = expandBtn.querySelector('i');
			if (globalShowActiveAdd) {
				if (expandIcon) { expandIcon.classList.remove('ti-chevron-down'); expandIcon.classList.add('ti-chevron-up'); }
			} else {
				if (expandIcon) { expandIcon.classList.remove('ti-chevron-up'); expandIcon.classList.add('ti-chevron-down'); }
			}
			var expandChk = expandBtn.querySelector('input[type="checkbox"]');
			if (expandChk) expandChk.checked = globalShowActiveAdd;
		}
		var tax0 = clonedRow.querySelector('[id="tax_id[' + rowID_old + '][0]"]'); if (tax0) { tax0.id = 'tax_id[' + rowID_new + '][0]'; tax0.name = 'tax_id[' + rowID_new + '][0]'; tax0.value = ''; }
		var tax1 = clonedRow.querySelector('[id="tax_id[' + rowID_old + '][1]"]'); if (tax1) { tax1.id = 'tax_id[' + rowID_new + '][1]'; tax1.name = 'tax_id[' + rowID_new + '][1]'; tax1.value = ''; }
		var jsonHtmlOld = clonedRow.querySelector('#json_html' + rowID_old); if (jsonHtmlOld) jsonHtmlOld.remove();
		itemtable.appendChild(clonedRow);
		if (window.hugeRTE) {
			var isDarkAdd = document.documentElement.getAttribute('data-bs-theme') === 'dark';
			var sharedAdd = { base_url: 'https://cdn.jsdelivr.net/npm/hugerte@1.0.10', suffix: '.min', menubar: false, statusbar: false, promotion: false, branding: false, content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }' };
			if (isDarkAdd) { sharedAdd.skin = 'oxide-dark'; sharedAdd.content_css = 'dark'; }
			window.hugeRTE.init(Object.assign({}, sharedAdd, { selector: 'textarea.editor', plugins: 'lists code', toolbar: 'undo redo | bold italic | bullist numlist | removeformat | code', height: 220 }));
			window.hugeRTE.init(Object.assign({}, sharedAdd, { selector: '#description' + rowID_new, plugins: 'lists autoresize', toolbar: 'bold italic | bullist numlist', min_height: 80, max_height: 300 }));
		}
		if (loading) loading.style.display = 'none';
	}

	function export_invoice(row_number, spreadsheet, wordprocessor) {
		siLog('debug', 'export_dialog_show');
		var pdf = document.querySelector('.export_pdf'); if (pdf) pdf.setAttribute('href', 'index.php?module=export&view=invoice&id=' + row_number + '&format=pdf');
		var doc = document.querySelector('.export_doc'); if (doc) doc.setAttribute('href', 'index.php?module=export&view=invoice&id=' + row_number + '&format=file&filetype=' + wordprocessor);
		var xls = document.querySelector('.export_xls'); if (xls) xls.setAttribute('href', 'index.php?module=export&view=invoice&id=' + row_number + '&format=file&filetype=' + spreadsheet);
		var el = document.getElementById('export_dialog');
		if (el && window.bootstrap && window.bootstrap.Modal) { var m = window.bootstrap.Modal.getOrCreateInstance(el); m.show(); }
	}

	function siToggleAllDesc(show) {
		document.querySelectorAll('.details').forEach(function (e) {
			if (show) e.classList.remove('si_hide'); else e.classList.add('si_hide');
		});
		document.querySelectorAll('.si-expand-desc').forEach(function (b) {
			var i = b.querySelector('i');
			if (i) { i.classList.toggle('ti-chevron-down', !show); i.classList.toggle('ti-chevron-up', show); }
			var chk = b.querySelector('input[type="checkbox"]');
			if (chk) chk.checked = show;
		});
	}

	document.addEventListener('click', function (e) {
		var allBtn = e.target.closest('.si-toggle-all-desc');
		if (allBtn) {
			siToggleAllDesc(allBtn.getAttribute('data-show') === '1');
			return;
		}
		var btn = e.target.closest('.si-expand-desc');
		if (!btn) return;
		e.preventDefault();
		var lineItem = btn.closest('.si-line-item');
		if (!lineItem) return;
		var detailsRow = lineItem.querySelector('.details');
		if (!detailsRow) return;
		var icon = btn.querySelector('i');
		var chk = btn.querySelector('input[type="checkbox"]');
		if (detailsRow.classList.contains('si_hide')) {
			detailsRow.classList.remove('si_hide');
			if (icon) { icon.classList.remove('ti-chevron-down'); icon.classList.add('ti-chevron-up'); }
			if (chk) chk.checked = true;
		} else {
			detailsRow.classList.add('si_hide');
			if (icon) { icon.classList.remove('ti-chevron-up'); icon.classList.add('ti-chevron-down'); }
			if (chk) chk.checked = false;
		}
	});

	function invoice_save_remove_autofill() {
		siLog('debug', 'executed invoice save remove');
		var textareas = document.querySelectorAll('textarea[id^="description"]');
		textareas.forEach(function (el) {
			if (el.value === si_lang_description) { el.value = ''; siLog('info', 'autofill value was removed'); }
		});
	}
</script>
@endverbatim
