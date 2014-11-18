<h1 class="title"><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a> <span>/</span> {$LANG.total_sales_by_customer}</h1>

<div class="table-responsive">
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th colspan="2">{$LANG.total_sales_by_customer}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{$LANG.total_sales}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{$total_sales|siLocal_number:'2'|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=customer from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.name|htmlsafe}</td>
			<td>{$customer.sum_total|siLocal_number:'2'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
</div>
