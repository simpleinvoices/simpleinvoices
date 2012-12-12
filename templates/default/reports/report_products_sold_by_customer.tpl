<h3>{$LANG.products_sold_customer_total}</h3>
<hr />

<table class="si_report_table">
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