<h3>{$LANG.total_sales_by_customer}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.total_sales_by_customer}</th>
		</tr>
	</thead>
	<tbody>
	{foreach item=customer from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.name|htmlsafe}</td>
			<td>{$customer.sum_total|siLocal_number:'2'|default:'-'}</td>
		</tr>
	{/foreach}

		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{$LANG.total_sales}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{$total_sales|siLocal_number:'2'|default:'-'}</span></td>
		</tr>
	</tbody>
</table>
