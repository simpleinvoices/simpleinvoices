/**
 * Litepicker (Tabler.io) for .date-picker inputs - replaces jQuery datePicker
 */
(function () {
	'use strict';
	function init() {
		var inputs = document.querySelectorAll('input.date-picker');
		if (!inputs.length || !window.Litepicker) return;
		inputs.forEach(function (input) {
			if (input._siLitepicker) return;
			var opts = { element: input, singleMode: true, format: 'YYYY-MM-DD', autoApply: true };
			var start = input.getAttribute('data-start') || input.id === 'date2' ? '1970-01-01' : null;
			var end = input.getAttribute('data-end');
			if (start) opts.minDate = start;
			if (end) opts.maxDate = end;
			input._siLitepicker = new window.Litepicker(opts);
		});
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
