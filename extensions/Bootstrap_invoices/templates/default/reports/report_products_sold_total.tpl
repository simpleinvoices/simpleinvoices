<h1 class="title"><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a> <span>/</span> {$LANG.products_sold_total}</h1>

<div class="table-responsive">
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th colspan="2">{$LANG.products_sold_total}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{$LANG.total}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{$total_quantity|siLocal_number:'0'|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=customer from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.description|htmlsafe}</td>
			<td>{$customer.sum_quantity|siLocal_number:'0'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
</div>
