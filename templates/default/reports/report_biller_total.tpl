<h3>{$LANG.biller_sales_total}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.biller_sales_total}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{$LANG.total_sales}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{$total_sales|siLocal_number:'2'|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=biller from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$biller.name|htmlsafe}</td>
			<td>{$biller.sum_total|siLocal_number:'2'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
