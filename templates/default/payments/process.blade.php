<form name="frmpost" action="index.php?module=payments&amp;view=save" method="post" class="needs-validation" novalidate>

<div class="card">
	<div class="card-body">
		@if(get('op') === "pay_selected_invoice")
			<div class="mb-4 p-3 bg-light rounded">
				<div class="row row-cols-1 row-cols-md-2">
					<div class="col mb-2">
						<strong>{{ $invoice['preference'] ?? '' }}</strong> {{ $invoice['index_id'] ?? '' }}
					</div>
					<div class="col mb-2">
						<strong>{{ $LANG['total'] ?? '' }}:</strong> {{ CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }}{{ number_format($invoice['total'] ?? 0, 2) }}
					</div>
					<div class="col mb-2">
						<strong>{{ $LANG['biller'] ?? '' }}:</strong> {{ $biller['name'] ?? '' }}
					</div>
					<div class="col mb-2">
						<strong>{{ $LANG['paid'] ?? '' }}:</strong> {{ CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }}{{ number_format($invoice['paid'] ?? 0, 2) }}
					</div>
					<div class="col mb-2">
						<strong>{{ $LANG['customer'] ?? '' }}:</strong> {{ $customer['name'] ?? '' }}
					</div>
					<div class="col mb-2">
						<strong>{{ $LANG['owing'] ?? '' }}:</strong> <u>{{ CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }}{{ number_format($invoice['owing'] ?? 0, 2) }}</u>
					</div>
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label">{{ $LANG['amount'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_auto_amount" title="{{ $LANG['process_payment_auto_amount'] ?? '' }}"><i class="ti ti-help"></i></a>
				</label>
				<div class="input-group">
					<span class="input-group-text">{{ CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }}</span>
					<input type="text" name="ac_amount" class="form-control" value="{{ $invoice['owing'] ?? '' }}" />
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label">{{ $LANG['date_formatted'] ?? '' }}</label>
				<div class="input-icon">
					<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
					<input type="text" class="form-control date-picker" name="ac_date" id="date1" value="{{ $today ?? '' }}" />
				</div>
			</div>
		@endif

		@if(get('op') === "pay_invoice")
			<div class="mb-3">
				<label class="form-label">{{ $LANG['invoice'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_inv_id" title="{{ $LANG['process_payment_inv_id'] ?? '' }}"><i class="ti ti-asterisk text-danger"></i></a>
				</label>
				<select name="invoice_id" id="payment-invoice-id" class="form-select" required>
					<option value=''></option>
					@foreach(($invoice_all ?? []) as $invoice)
						<option value="{{ siLocal::number($invoice['id'] ?? '') }}" data-owing="{{ siLocal::number($invoice['owing'] ?? 0) }}" data-currency-sign="{{ CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? '') }}">{{ $invoice['index_name'] ?? '' }} ({{ $invoice['biller'] ?? '' }}, {{ $invoice['customer'] ?? '' }}, {{ $LANG['total'] ?? '' }} {{ $invoice['invoice_total'] ?? '' }} : {{ $LANG['owing'] ?? '' }} {{ siLocal::number($invoice['owing'] ?? 0) }})</option>
					@endforeach
				</select>
				<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
			</div>
			<div class="mb-3">
				<label class="form-label">{{ $LANG['amount'] ?? '' }}</label>
				<div class="input-group">
					<span class="input-group-text" id="payment-currency-addon">{{ CurrencySignHelper::forDisplay($preference['pref_currency_sign'] ?? '')|si_currency_display }}</span>
					<input type="text" name="ac_amount" id="payment-amount" class="form-control" />
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label">{{ $LANG['date_formatted'] ?? '' }}</label>
				<div class="input-icon">
					<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
					<input type="text" class="form-control date-picker" name="ac_date" id="date1" value="{{ $today ?? '' }}" />
				</div>
			</div>
		@endif

		<div class="mb-3">
			<label class="form-label">{{ $LANG['payment_type_method'] ?? '' }}</label>
			@if($paymentTypes == null)
				<p class="text-muted mb-0"><em>{{ $LANG['no_payment_types'] ?? '' }}</em></p>
			@else
				<select name="ac_payment_type" class="form-select">
					@foreach(($paymentTypes ?? []) as $paymentType)
						<option value="{{ $paymentType['pt_id'] ?? '' }}" @if($paymentType['pt_id'] == $defaults->payment_type)selected@endif>
							{{ $paymentType['pt_description'] ?? '' }}
						</option>
					@endforeach
				</select>
			@endif
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['note'] ?? '' }}</label>
			<textarea class="form-control editor" name="ac_notes" rows="5"></textarea>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payments&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="process_payment" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>

@if(get('op') == 'pay_selected_invoice')
	<input type="hidden" name="invoice_id" value="{{ $invoice['id'] ?? '' }}" />
@endif
</form>

@if(get('op') === 'pay_invoice')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		var invoiceSelect = document.getElementById('payment-invoice-id');
		var amountInput = document.getElementById('payment-amount');
		var curAddon = document.getElementById('payment-currency-addon');

		if (!invoiceSelect || !amountInput) {
			return;
		}

		function syncAmountFromInvoice() {
			var selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
			amountInput.value = selectedOption ? (selectedOption.getAttribute('data-owing') || '') : '';
			if (curAddon && selectedOption) {
				var s = selectedOption.getAttribute('data-currency-sign') || '';
				curAddon.textContent = s;
			}
		}

		invoiceSelect.addEventListener('change', syncAmountFromInvoice);
		syncAmountFromInvoice();
	});
</script>
@endif
