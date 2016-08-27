<h3 class="si_report_title">{$LANG.debtors_by_amount_owing_customer}</h3>

<table class="si_report_table">
	<thead>
<!--		<tr>
			<th colspan="5">{$LANG.debtors_by_amount_owing_customer}</th>
		</tr>-->
		<tr>
			<th>{$LANG.id}</th>
			<th>{$LANG.customer}</th>
			<th>{$LANG.total}</th>
			<th>{$LANG.paid}</th>
			<th>{$LANG.owing}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="4">{$LANG.total_owing}</th>
			<td>{$total_owed|siLocal_number:'2'|default:'-'}</td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=customer from=$data}
		<tr>
			<td>{$customer.cid|htmlsafe}</td>
			<td>{$customer.customer|htmlsafe}</td>
			<td>{$customer.inv_total|siLocal_number:'2'|default:'-'}</td>
			<td>{$customer.inv_paid|siLocal_number:'2'|default:'-'}</td>
			<td>{$customer.inv_owing|siLocal_number:'2'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
