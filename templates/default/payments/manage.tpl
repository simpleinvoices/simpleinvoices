
{if $payments == null}
	<P><em>{$LANG.no_payments}.</em></p>
{else}

{if $smarty.get.id }

	<h3>{$LANG.payments_filtered} {$smarty.get.id}</h3> :: <a href='index.php?module=payments&view=process&submit=$_GET.id&op=pay_selected_invoice'>{$LANG.payments_filtered_invoice}</a>

{elseif $smarty.get.c_id }
<h3>{$LANG.payments_filtered_customer} {$smarty.get.c_id} :: <a href='index.php?module=payments&view=process&op=pay_invoice'>{$LANG.process_payment}</a></h3>


{else}

	<h3>{$LANG.manage_payments} :: <a href='index.php?module=payments&view=process&op=pay_invoice'>{$LANG.process_payment}</a></h3>

{/if}


<hr></hr>


<table align="center" class="ricoLiveGrid"  id="rico_payment" >
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:15%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:15%;' />
</colgroup>
<thead>

<tr class="sortHeader">
<th class="noFilter sortable">{$LANG.actions}</th>
<th class="index_table sortable">{$LANG.payment_id}</th>
<th class="index_table sortable">{$LANG.invoice_id}</th>
<th class="selectFilter index_table sortable">{$LANG.customer}</th>
<th class="selectFilter index_table sortable">{$LANG.biller}</th>
<th class="index_table sortable">{$LANG.amount}</th>
<th class="index_table sortable">{$LANG.notes}</th>
<th class="selectFilter index_table sortable">{$LANG.payment_type}</th>
<th class="noFilter index_table sortable">{$LANG.date_upper}</th>
</tr>
</thead>

	{foreach from=$payments item=payment}


	<tr class='index_table'>
		<td class='index_table'><a class='index_table' href='index.php?module=payments&view=details&id={$payment.id}'>{$LANG.view}</a></td>
		<td class='index_table'>{$payment.id}</td>
		<td class='index_table'>{$payment.ac_inv_id}</td>
		<td class='index_table'>{$payment.CNAME}</td>
		<td class='index_table'>{$payment.BNAME}</td>
		<td class='index_table'>{$payment.ac_amount}</td>
		<td class='index_table'>{$payment.ac_notes|truncate:10:"..."}</td>
		<td class='index_table'>{$payment.description}</td>
		<td class='index_table'>{$payment.ac_date}</td>
	</tr>
	
	{/foreach}

	</table>
{/if}



<br />
<div style="text-align:center;"><a href="docs.php?t=help&p=wheres_the_edit_button"
	rel="gb_page_center.450, 450"><img
	src="./images/common/help-small.png"></img> Wheres the Edit button?</a></div>
