{if $customers == null}
	<P><em>{$LANG.no_customers}.</em></p>
{else}


<h3>{$LANG.manage_customers} :: <a href="index.php?module=customers&view=add">{$LANG.customer_add}</a></h3>
<hr />

<table align="center" id="rico_customer" class="ricoLiveGrid manage">
<colgroup>
<col style='width:10%;' />
<col style='width:5%;' />
<col style='width:25%;' />
<col style='width:15%;' />
<col style='width:15%;' />
<col style='width:15%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter sortable">{$LANG.actions}</th>
<th class="index_table sortable">{$LANG.customer_id}</th>
<th class="index_table sortable">{$LANG.customer_name}</th>
<!--
<th class="index_table">{$LANG.phone}</th>
-->
<th class="index_table sortable">{$LANG.total}</th>
<!--
<th class="index_table">{$LANG.paid}</th>
-->
<th class="index_table sortable">{$LANG.owing}</th>
<th class="index_table sortable">{$LANG.enabled}</th>
</tr>
</thead>



{foreach from=$customers item=customer}

	<tr class="index_table">
	<td class="index_table"><a class="index_table"
	 href="index.php?module=customers&view=details&submit={$customer.id}&action=view">{$LANG.view}</a> ::
	<a class="index_table"
	 href="index.php?module=customers&view=details&submit={$customer.id}&action=edit">{$LANG.edit}</a> </td>
	<td class="index_table">{$customer.id}</td>
	<td class="index_table">{$customer.name}</td>
	<!--
	<td class="index_table">{$customer.phone}</td>
	-->
	<td class="index_table">{$customer.total}</td>
	<!--
	<td class="index_table">{$invoice.paid}</td>
	-->
	<td class="index_table">{$customer.owing}</td>
	<td class="index_table">{$customer.enabled}</td>
	</tr>

{/foreach}
	</table>
{/if}
