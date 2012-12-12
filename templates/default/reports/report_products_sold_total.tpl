<h3>{$LANG.products_sold_total}</h3>
<hr />


<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.products_sold_total}</th>
		</tr>
	</thead>
	<tbody>
	{foreach item=customer from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.description|htmlsafe}</td>
			<td>{$customer.sum_quantity|siLocal_number:'0'|default:'-'}</td>
		</tr>
	{/foreach}

		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{$LANG.total}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{$total_quantity|siLocal_number:'0'|default:'-'}</span></td>
		</tr>
	</tbody>
</table>
