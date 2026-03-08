
<table class="table table-vcenter si_report_table mb-0">
	<thead>
		<tr>
			<th>
@if($show_rates)
			<a class="but_show_rates si_button_mini" href="#">%</a>
@endif
			</th>
		@foreach(($years ?? []) as $year)
			<th><b>{{ $year ?? '' }}</b></th>
@if($show_rates)
			<th class="rate"></td>
@endif
		@endforeach

		</tr>
	</thead>

	<tfoot>
		<tr>
			<th>{{ $LANG['total'] ?? '' }}</th>

		@foreach(($years ?? []) as $year)
			<td>{{ siLocal::number($this_data['total'][$year] ?? 0) ?: '-' }}</td>
@if($show_rates)
			<td class="rate @if(($this_data['total_rate'][$year] ?? 0) < 0) neg_rate @endif">@if(($this_data['total_rate'][$year] ?? 0)){{ siLocal::number($this_data['total_rate'][$year] ?? 0) }}%@endif</td>
@endif
		@endforeach

		</tr>
	</tfoot>

	<tbody>
	@foreach(($this_data['months'] ?? []) as $month => $amount)
		<tr class="tr_{cycle values="A,B"}">
			<th>{{ ucfirst(siLocal::date('2000-' . $month . '-01', 'month')) }}</th>
		@foreach(($years ?? []) as $year)
			<td>{{ siLocal::number($amount[$year] ?? 0) ?: '-' }}</td>
@if($show_rates)
			<td class="rate @if(($this_data['months_rate'][$month][$year] ?? 0) < 0) neg_rate @endif">@if(($this_data['months_rate'][$month][$year] ?? 0)){{ siLocal::number($this_data['months_rate'][$month][$year] ?? 0) }}%@endif</td>
@endif
		@endforeach

		</tr>
	@endforeach

	</tbody>
</table>

