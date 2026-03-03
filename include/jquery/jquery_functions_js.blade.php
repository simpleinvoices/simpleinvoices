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
				if (qEl && quantity === '') qEl.setAttribute('value', '1');
				var up = document.getElementById('unit_price' + row_number);
				if (up) up.setAttribute('value', data.unit_price || '');
				var t0 = document.querySelector('#row' + row_number + ' [name="tax_id[' + row_number + '][0]"]');
				if (t0) t0.value = data.default_tax_id != null ? data.default_tax_id : '';
				var t1 = document.querySelector('#row' + row_number + ' [name="tax_id[' + row_number + '][1]"]');
				if (t1) t1.value = (data.default_tax_id_2 != null) ? data.default_tax_id_2 : '';
				var detailsTr = document.querySelectorAll('tbody#row' + row_number + ' tr.details');
				detailsTr.forEach(function (tr) {
					if (data.show_description === 'Y') tr.classList.remove('si_hide');
					else tr.classList.add('si_hide');
				});
				var descEl = document.getElementById('description' + row_number);
				if (descEl) {
					var rel = descEl.getAttribute('rel');
					if (descEl.value === rel || descEl.value === si_lang_description) {
						if (data.notes_as_description === 'Y') {
							descEl.value = data.notes || '';
							descEl.setAttribute('rel', data.notes || '');
						} else {
							descEl.value = si_lang_description;
							descEl.setAttribute('rel', si_lang_description);
						}
					}
				}
				if (data.json_html && data.json_html !== '') {
					var tbody = document.getElementById('row' + row_number);
					var details = tbody ? tbody.querySelector('tr.details') : null;
					if (tbody && details) {
						var wrap = document.createElement('tbody');
						wrap.innerHTML = data.json_html;
						while (wrap.firstChild) tbody.insertBefore(wrap.firstChild, details);
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
		var rows = itemtable.querySelectorAll('tbody.line_item');
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
		var lineItems = itemtable.querySelectorAll('tbody.line_item');
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
		var del = byId(clonedRow, 'delete' + rowID_old); if (del) { del.id = 'delete' + rowID_new; del.name = 'delete' + rowID_new; }
		var delImg = byId(clonedRow, 'delete_image' + rowID_old); if (delImg) { delImg.id = 'delete_image' + rowID_new; delImg.name = 'delete_image' + rowID_new; delImg.src = './images/common/delete_item.png'; }
		var trashImg = clonedRow.querySelector('#trash_image' + rowID_old); if (trashImg) trashImg.src = './images/common/delete_item.png';
		var lineItem = byId(clonedRow, 'line_item' + rowID_old); if (lineItem) { lineItem.id = 'line_item' + rowID_new; lineItem.name = 'line_item' + rowID_new; lineItem.value = ''; }
		var qNew = byId(clonedRow, 'quantity' + rowID_old); if (qNew) { qNew.id = 'quantity' + rowID_new; qNew.name = 'quantity' + rowID_new; qNew.removeAttribute('value'); qNew.classList.remove('validate[required]'); }
		var products = byId(clonedRow, 'products' + rowID_old); if (products) {
			products.setAttribute('rel', String(rowID_new)); products.id = 'products' + rowID_new; products.name = 'products' + rowID_new;
			products.querySelectorAll('option').forEach(function (o) { o.removeAttribute('selected'); });
			var emptyOpt = document.createElement('option'); emptyOpt.value = ''; emptyOpt.textContent = '';
			products.insertBefore(emptyOpt, products.firstChild);
			products.selectedIndex = 0;
			products.classList.remove('validate[required]');
		}
		var upOld = clonedRow.querySelector('#unit_price' + rowID_old); if (upOld) { upOld.id = 'unit_price' + rowID_new; upOld.name = 'unit_price' + rowID_new; upOld.value = ''; upOld.classList.remove('validate[required]'); }
		var descOld = clonedRow.querySelector('#description' + rowID_old); if (descOld) { descOld.id = 'description' + rowID_new; descOld.name = 'description' + rowID_new; descOld.value = si_lang_description; descOld.style.color = '#b2adad'; descOld.classList.remove('validate[required]'); }
		clonedRow.querySelectorAll('.details').forEach(function (el) { el.style.display = 'none'; });
		var tax0 = clonedRow.querySelector('[id="tax_id[' + rowID_old + '][0]"]'); if (tax0) { tax0.id = 'tax_id[' + rowID_new + '][0]'; tax0.name = 'tax_id[' + rowID_new + '][0]'; }
		var tax1 = clonedRow.querySelector('[id="tax_id[' + rowID_old + '][1]"]'); if (tax1) { tax1.id = 'tax_id[' + rowID_new + '][1]'; tax1.name = 'tax_id[' + rowID_new + '][1]'; }
		var jsonHtmlOld = clonedRow.querySelector('#json_html' + rowID_old); if (jsonHtmlOld) jsonHtmlOld.remove();
		itemtable.appendChild(clonedRow);
		if (window.hugeRTE) {
			var o = { selector: 'textarea.editor', base_url: 'https://cdn.jsdelivr.net/npm/hugerte@1.0.10', suffix: '.min', plugins: 'lists code', toolbar: 'undo redo | bold italic | bullist numlist | removeformat | code', menubar: false, statusbar: false, height: 220, promotion: false, branding: false, content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }' };
			if (document.documentElement.getAttribute('data-bs-theme') === 'dark') { o.skin = 'oxide-dark'; o.content_css = 'dark'; }
			window.hugeRTE.init(o);
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

	function invoice_save_remove_autofill() {
		siLog('debug', 'executed invoice save remove');
		var textareas = document.querySelectorAll('textarea[id^="description"]');
		textareas.forEach(function (el) {
			if (el.value === si_lang_description) { el.value = ''; siLog('info', 'autofill value was removed'); }
		});
	}
</script>
@endverbatim
