<div class="table-responsive">
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>
{if $show_rates}
			<a class="but_show_rates si_button_mini" href="#">%</a>
{/if}
			</th>
		{foreach item=year from=$years}
			<th><b>{$year|htmlsafe}</b></th>
{if $show_rates}
			<th class="rate"></td>
{/if}
		{/foreach}

		</tr>
	</thead>

	<tfoot>
		<tr>
			<th>{$LANG.total}</th>

		{foreach item=year from=$years}
			<td>{$this_data.total.$year|siLocal_number:'0'|default:'-'}</td>
{if $show_rates}
			<td class="rate{if $this_data.total_rate.$year < 0} neg_rate{/if}">{if $this_data.total_rate.$year}{$this_data.total_rate.$year|siLocal_number:$rate_precision}%{/if}</td>
{/if}
		{/foreach}

		</tr>
	</tfoot>

	<tbody>
	{foreach key=month item=amount from=$this_data.months}
		<tr class="tr_{cycle values="A,B"}">
			<th>{"2000-$month-01"|siLocal_date:'month'|htmlsafe|ucfirst}</th>
		{foreach item=year from=$years}
			<td>{$amount.$year|siLocal_number:'0'|default:'-'}</td>
{if $show_rates}
			<td class="rate{if $this_data.months_rate.$month.$year < 0} neg_rate{/if}">{if $this_data.months_rate.$month.$year}{$this_data.months_rate.$month.$year|siLocal_number:$rate_precision}%{/if}</td>
{/if}
		{/foreach}

		</tr>
	{/foreach}

	</tbody>
</table>
</div>

