<div class="card mb-4">
	<div class="card-header">
		<span class="avatar avatar-sm bg-purple-lt me-2 rounded"><i class="ti ti-chart-dots text-purple"></i></span>
		<h3 class="card-title">{{ $LANG['profit_per_invoice'] ?? '' }}</h3>
		<div class="card-options">
			<a href="index.php?module=reports&view=index" class="btn btn-sm btn-outline-secondary">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['reports'] ?? 'Reports' }}
			</a>
		</div>
	</div>

	<form name="frmpost" id="form_report_invoice_profit" action="index.php?module=reports&amp;view=report_invoice_profit" method="post">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-sm-5">
					<label class="form-label">{{ $LANG['start_date'] ?? '' }}</label>
					<div class="input-icon">
						<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
						<input type="text"
							class="form-control validate[required,custom[date],length[0,10]] date-picker"
							name="start_date" id="date1"
							value="{{ $start_date ?? '' }}" />
					</div>
				</div>
				<div class="col-sm-5">
					<label class="form-label">{{ $LANG['end_date'] ?? '' }}</label>
					<div class="input-icon">
						<span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
						<input type="text"
							class="form-control validate[required,custom[date],length[0,10]] date-picker"
							name="end_date" id="date2"
							value="{{ $end_date ?? '' }}" />
					</div>
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-primary w-100" name="submit" value="{{ $LANG['run_report'] ?? '' }}">
						<i class="ti ti-chart-bar me-1"></i>{{ $LANG['run_report'] ?? '' }}
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

@if(!empty($invoices))
@php
	$invoice_totals = $invoice_totals ?? [];
	$sum_total  = $invoice_totals['sum_total']  ?? 0;
	$sum_cost   = $invoice_totals['sum_cost']   ?? 0;
	$sum_profit = $invoice_totals['sum_profit'] ?? 0;
@endphp

<div class="card">
	<div class="card-header">
		<h3 class="card-title">
			{{ strtr($LANG['profit_per_invoice_summary'] ?? '', ['{start_date}' => $start_date ?? '', '{end_date}' => $end_date ?? '']) }}
		</h3>
	</div>

	{{-- Profit summary stats --}}
	<div class="card-body border-bottom">
		<div class="row g-3">
			<div class="col-sm-4">
				<div class="p-3 bg-blue-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['total'] ?? '' }}</div>
					<div class="h3 fw-bold text-blue mb-0">{{ siLocal::number($sum_total) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 bg-orange-lt rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['cost'] ?? '' }}</div>
					<div class="h3 fw-bold text-orange mb-0">{{ siLocal::number($sum_cost) ?: '-' }}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="p-3 {{ $sum_profit >= 0 ? 'bg-green-lt' : 'bg-red-lt' }} rounded-2 text-center">
					<div class="text-secondary small mb-1">{{ $LANG['profit'] ?? '' }}</div>
					<div class="h3 fw-bold {{ $sum_profit >= 0 ? 'text-green' : 'text-red' }} mb-0">
						{{ siLocal::number($sum_profit) ?: '-' }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-vcenter table-hover card-table">
			<thead>
				<tr>
					<th>{{ $LANG['id'] ?? '' }}</th>
					<th>{{ $LANG['biller'] ?? '' }}</th>
					<th>{{ $LANG['customer'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['total'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['cost'] ?? '' }}</th>
					<th class="text-end">{{ $LANG['profit'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
			@foreach(($invoices ?? []) as $invoice)
				@php
					$profit = $invoice['profit'] ?? 0;
					$profit_color = $profit >= 0 ? 'text-green' : 'text-red';
				@endphp
				@if($loop->index > 0 && (($invoices[$loop->index - 1]['preference'] ?? '') != ($invoice['preference'] ?? '')))
				<tr class="table-light">
					<td colspan="6" class="text-secondary small py-1"></td>
				</tr>
				@endif
				<tr>
					<td class="fw-medium">{{ $invoice['preference'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</td>
					<td>{{ $invoice['biller'] ?? '' }}</td>
					<td>{{ $invoice['customer'] ?? '' }}</td>
					<td class="text-end">{{ siLocal::number($invoice['invoice_total'] ?? 0) }}</td>
					<td class="text-end text-secondary">{{ siLocal::number($invoice['cost'] ?? 0) }}</td>
					<td class="text-end fw-bold {{ $profit_color }}">{{ siLocal::number($profit) }}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<tr class="fw-bold table-active">
					<td colspan="3" class="text-secondary">{{ strtoupper($LANG['totals'] ?? '') }}</td>
					<td class="text-end text-blue">{{ siLocal::number($sum_total) }}</td>
					<td class="text-end text-orange">{{ siLocal::number($sum_cost) }}</td>
					<td class="text-end {{ $sum_profit >= 0 ? 'text-green' : 'text-red' }}">{{ siLocal::number($sum_profit) }}</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
@endif
