(function () {
	function normalizeText(value) {
		return (value || '').replace(/\s+/g, ' ').trim();
	}

	function parseSortableNumber(text) {
		var normalized = normalizeText(text)
			.replace(/[()]/g, function (match) { return match === '(' ? '-' : ''; })
			.replace(/[^0-9,.\-+]/g, '');

		if (!normalized || !/[0-9]/.test(normalized)) {
			return null;
		}

		var lastComma = normalized.lastIndexOf(',');
		var lastDot = normalized.lastIndexOf('.');
		var decimalSeparator = '';

		if (lastComma !== -1 && lastDot !== -1) {
			decimalSeparator = lastComma > lastDot ? ',' : '.';
		} else if (lastComma !== -1) {
			decimalSeparator = normalized.length - lastComma - 1 <= 2 ? ',' : '';
		} else if (lastDot !== -1) {
			decimalSeparator = normalized.length - lastDot - 1 <= 2 ? '.' : '';
		}

		var number = normalized;
		if (decimalSeparator) {
			var thousandsSeparator = decimalSeparator === ',' ? '.' : ',';
			number = number.split(thousandsSeparator).join('');
			if (decimalSeparator === ',') {
				number = number.replace(',', '.');
			}
		} else {
			number = number.replace(/[,.]/g, '');
		}

		var parsed = parseFloat(number);
		return Number.isFinite(parsed) ? parsed : null;
	}

	function getCellValue(row, columnIndex) {
		var cell = row.children[columnIndex];
		if (!cell) {
			return { type: 'string', value: '' };
		}

		var dataValue = cell.getAttribute('data-sort-value');
		if (dataValue !== null) {
			var dataNumber = parseSortableNumber(dataValue);
			if (dataNumber !== null) {
				return { type: 'number', value: dataNumber };
			}
			return { type: 'string', value: normalizeText(dataValue).toLowerCase() };
		}

		var text = normalizeText(cell.textContent);
		var number = parseSortableNumber(text);
		if (number !== null) {
			return { type: 'number', value: number };
		}

		var timestamp = Date.parse(text);
		if (!Number.isNaN(timestamp) && /[\-\/ ]/.test(text)) {
			return { type: 'date', value: timestamp };
		}

		return { type: 'string', value: text.toLowerCase() };
	}

	function compareValues(left, right, direction) {
		var multiplier = direction === 'desc' ? -1 : 1;

		if (left.type === right.type && left.type !== 'string') {
			if (left.value === right.value) {
				return 0;
			}
			return left.value > right.value ? multiplier : -multiplier;
		}

		var leftText = String(left.value);
		var rightText = String(right.value);
		return leftText.localeCompare(rightText, undefined, { numeric: true, sensitivity: 'base' }) * multiplier;
	}

	function renumberRows(table) {
		var firstHeader = table.tHead && table.tHead.rows[0] && table.tHead.rows[0].cells[0];
		var firstHeaderText = normalizeText(firstHeader ? firstHeader.textContent : '');
		if (firstHeaderText !== '#') {
			return;
		}

		Array.prototype.forEach.call(table.tBodies[0].rows, function (row, index) {
			if (row.cells[0]) {
				row.cells[0].textContent = index + 1;
			}
		});
	}

	function updateSortIndicators(table, activeHeader, direction) {
		Array.prototype.forEach.call(table.tHead.querySelectorAll('th'), function (th) {
			var icon = th.querySelector('.si-report-sort-icon');
			if (!icon) {
				return;
			}

			if (th === activeHeader) {
				th.setAttribute('aria-sort', direction === 'asc' ? 'ascending' : 'descending');
				icon.className = 'ti si-report-sort-icon ' + (direction === 'asc' ? 'ti-chevron-up' : 'ti-chevron-down');
			} else {
				th.setAttribute('aria-sort', 'none');
				icon.className = 'ti si-report-sort-icon ti-selector';
			}
		});
	}

	function sortTable(table, columnIndex, header) {
		var tbody = table.tBodies[0];
		if (!tbody) {
			return;
		}

		var currentColumn = parseInt(table.getAttribute('data-sort-column') || '-1', 10);
		var currentDirection = table.getAttribute('data-sort-direction') || 'asc';
		var direction = currentColumn === columnIndex && currentDirection === 'asc' ? 'desc' : 'asc';

		var rows = Array.prototype.map.call(tbody.rows, function (row, index) {
			return { row: row, index: index };
		});
		rows.sort(function (leftItem, rightItem) {
			var left = getCellValue(leftItem.row, columnIndex);
			var right = getCellValue(rightItem.row, columnIndex);
			var result = compareValues(left, right, direction);
			return result || leftItem.index - rightItem.index;
		});

		rows.forEach(function (item) {
			tbody.appendChild(item.row);
		});

		table.setAttribute('data-sort-column', String(columnIndex));
		table.setAttribute('data-sort-direction', direction);
		updateSortIndicators(table, header, direction);
		renumberRows(table);
	}

	function initTable(table) {
		if (!table.tHead || !table.tBodies.length) {
			return;
		}

		table.classList.add('si-report-sortable');

		Array.prototype.forEach.call(table.tHead.rows[0].cells, function (th, columnIndex) {
			if (th.getAttribute('colspan')) {
				return;
			}

			var label = normalizeText(th.textContent);
			if (!label || th.classList.contains('si-report-not-sortable')) {
				th.setAttribute('aria-sort', 'none');
				return;
			}

			th.classList.add('si-report-sort-trigger');
			th.setAttribute('role', 'button');
			th.setAttribute('tabindex', '0');
			th.setAttribute('aria-sort', 'none');

			var icon = document.createElement('i');
			icon.className = 'ti si-report-sort-icon ti-selector';
			icon.setAttribute('aria-hidden', 'true');
			th.appendChild(icon);

			function activateSort(event) {
				if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') {
					return;
				}
				event.preventDefault();
				sortTable(table, columnIndex, th);
			}

			th.addEventListener('click', activateSort);
			th.addEventListener('keydown', activateSort);
		});
	}

	function init() {
		Array.prototype.forEach.call(
			document.querySelectorAll('.table-responsive table'),
			initTable
		);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
