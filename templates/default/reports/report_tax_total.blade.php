@php
	$taxes = $taxes ?? [];
	$total_taxes = $total_taxes ?? 0;
	$multi = count($taxes) > 1;
@endphp

<div class="card">
	<div class="card-body">
		@if($multi)
		{{-- Multi-currency: table of currencies + totals --}}
		<div class="table-responsive">
			<table class="table table-vcenter table-hover card-table">
				<thead>
					<tr>
						<th>{{ $LANG['currency'] ?? 'Currency' }}</th>
						<th class="text-end">{{ $LANG['total_taxes'] ?? '' }}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($taxes as $row)
					<tr>
						<td class="text-secondary">{{ ($row['currency_sign'] ?? '')|si_currency_display }}{{ !empty($row['currency_code']) ? ' ' . $row['currency_code'] : '' }}</td>
						<td class="text-end fw-bold text-orange">{!! CurrencySignHelper::format($row['sum_tax_total'] ?? 0, $row['currency_sign'] ?? '', '', $row['currency_code'] ?? '') !!}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		@else
		{{-- Single currency: large stat card --}}
		<div class="row justify-content-center">
			<div class="col-sm-8 col-md-6 col-lg-4">
				<div class="card bg-orange-lt border-0">
					<div class="card-body text-center py-5">
						<div class="mb-3">
							<span class="avatar avatar-lg bg-orange text-white rounded-circle">
								<i class="ti ti-receipt-tax fs-2"></i>
							</span>
						</div>
						<div class="display-5 fw-bold text-orange mb-2">
							{!! CurrencySignHelper::format($taxes[0]['sum_tax_total'] ?? 0, $taxes[0]['currency_sign'] ?? '', '', $taxes[0]['currency_code'] ?? '') !!}
						</div>
						<div class="text-secondary">{{ $LANG['total_taxes'] ?? '' }}</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
