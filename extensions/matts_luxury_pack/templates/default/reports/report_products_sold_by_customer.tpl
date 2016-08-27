<h3>{$LANG.products_sold_customer_total}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.customer}</th>
			<th>{$LANG.product}</th>
			<th>{$LANG.amount}</th>
			<th>{$LANG.total}</th>
		</tr>
	</thead>
	<tbody>
{foreach item=customer from=$data}
		<tr>
			<td>{$customer.name|htmlsafe}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	{foreach item=product from=$customer.products}
		<tr>
			<td>&nbsp;</td>
			<td>{$product.description|htmlsafe}</td>
			<td>{$product.sum_quantity|siLocal_number:'0'|default:'-'}</td>
			<td>&nbsp;</td>
		</tr>
	{/foreach}
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
<!--			<td>{$LANG.total}</td>-->
			<td>&nbsp;</td>
			<td>{$customer.total_quantity|siLocal_number:'0'|default:'-'}</td>
		</tr>
{/foreach}
	</tbody>
</table>
