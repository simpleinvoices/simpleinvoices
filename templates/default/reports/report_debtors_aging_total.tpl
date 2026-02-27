<h3 class="si_report_title">{$LANG.total_by_aging_periods}</h3>

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="4">{$LANG.total_by_aging_periods}</th>
		</tr>
		<tr>
			<th>{$LANG.total}</th>
			<th>{$LANG.paid}</th>
			<th>{$LANG.owing}</th>
			<th>{$LANG.aging}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td>{$sum_total|siLocal_number:'2'|default:'-'}</td>
			<td>{$sum_paid|siLocal_number:'2'|default:'-'}</td>
			<td>{$sum_owing|siLocal_number:'2'|default:'-'}</td>
			<td></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=period from=$data}
		<tr>
			<td>{$period.inv_total|siLocal_number:'2'|default:'-'}</td>
			<td>{$period.inv_paid|siLocal_number:'2'|default:'-'}</td>
			<td>{$period.inv_owing|siLocal_number:'2'|default:'-'}</td>
			<td>{$period.aging}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
