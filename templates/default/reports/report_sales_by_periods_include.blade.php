
<div class="table-responsive">
<table class="table table-sm table-vcenter table-hover si_report_table mb-0">
	<thead>
		<tr>
			<th></th>
		@foreach(($years ?? []) as $year)
			<th class="text-end fw-bold">{{ $year ?? '' }}</th>
@if($show_rates ?? false)
			<th class="rate text-end text-secondary small">%</th>
@endif
		@endforeach
		</tr>
	</thead>

	<tfoot>
		<tr class="fw-bold table-active">
			<th>{{ $LANG['total'] ?? '' }}</th>
		@foreach(($years ?? []) as $year)
			<td class="text-end">{{ siLocal::number($this_data['total'][$year] ?? 0) ?: '-' }}</td>
@if($show_rates ?? false)
			@php $rate = $this_data['total_rate'][$year] ?? 0; @endphp
			<td class="rate text-end small">
				@if($rate)
				<span class="badge {{ $rate < 0 ? 'bg-red-lt text-red' : 'bg-green-lt text-green' }}">
					{{ $rate > 0 ? '+' : '' }}{{ siLocal::number($rate) }}%
				</span>
				@endif
			</td>
@endif
		@endforeach
		</tr>
	</tfoot>

	<tbody>
	@foreach(($this_data['months'] ?? []) as $month => $amount)
		<tr>
			<th class="text-secondary">{{ ucfirst(siLocal::date('2000-' . $month . '-01', 'month')) }}</th>
		@foreach(($years ?? []) as $year)
			<td class="text-end">{{ siLocal::number($amount[$year] ?? 0) ?: '-' }}</td>
@if($show_rates ?? false)
			@php $mrate = $this_data['months_rate'][$month][$year] ?? 0; @endphp
			<td class="rate text-end small">
				@if($mrate)
				<span class="badge {{ $mrate < 0 ? 'bg-red-lt text-red' : 'bg-green-lt text-green' }}">
					{{ $mrate > 0 ? '+' : '' }}{{ siLocal::number($mrate) }}%
				</span>
				@endif
			</td>
@endif
		@endforeach
		</tr>
	@endforeach
	</tbody>
</table>
</div>
