<h1 class="title"><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a> <span>/</span> {$LANG.products_sold_customer_total}</h1>

<div class="table-responsive">
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th colspan="2">{$LANG.products_sold_customer_total}</th>
		</tr>
	</thead>
	<tbody>
	{foreach item=customer from=$data}
		<tr>
			<td colspan="2">{$customer.name|htmlsafe}</td>
		</tr>
		{foreach item=product from=$customer.products}
			<tr>
				<td>{$product.description|htmlsafe}</td>
				<td>{$product.sum_quantity|siLocal_number:'0'|default:'-'}</td>
			</tr>
		{/foreach}
		<tr>
			<td>{$LANG.total}</td>
			<td>{$customer.total_quantity|siLocal_number:'0'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
</div>
