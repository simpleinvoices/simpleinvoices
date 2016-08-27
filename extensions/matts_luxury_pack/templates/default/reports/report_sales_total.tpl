<h3>{$LANG.total_sales}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th class="align_left">{$LANG.invoice_preferences}</th>
			<th class="align_right">{$LANG.invoices}</th>
			<th class="align_right">{$LANG.total_sales}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" colspan="2" class="PAGE_LAYER">{$LANG.total_sales}: </td>
			<td align="RIGHT" class="PAGE_LAYER"><span class="BOLD">{$grand_total_sales|siLocal_number:'2'|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=total_sales from=$data}
		<tr>
			<td class="align_left">{$total_sales.template|htmlsafe}</td>
			<td class="align_right">{$total_sales.count|siLocal_number:'0'|default:'-'}</td>
			<td class="align_right">{$total_sales.sum_total|siLocal_number:'2'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
