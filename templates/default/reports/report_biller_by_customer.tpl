<div id="top"><h3>{$LANG.biller_sales_by_customer_totals}</h3></div>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.biller_sales_by_customer_totals}</th>
		</tr>
	</thead>
	<tbody>
	{foreach item=biller from=$data}
		<tr>
			<th colspan="2">{$LANG.biller_name}: {$biller.name|htmlsafe}</th>
		</tr>
		<tr>
			<th>{$LANG.customer_name}</th>
			<th>{$LANG.sales}</th>
		</tr>
		{foreach item=customer from=$biller.customers}
			<tr>
				<td>{$customer.name|htmlsafe}</td>
				<td>{$customer.sum_total|siLocal_number:'2'|default:'-'}</td>
			</tr>
		{/foreach}
		<tr>
			<td>{$LANG.total}</td>
			<td>{$biller.total_sales|siLocal_number:'2'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
