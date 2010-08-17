

<div id="top"><h3>Monthly tax summary per year</h3></div>

<table width="100%">
 {foreach item=year from=$years}
   <tr>
   <td  class="details_screen"><b>{$year}</b></td>
   <tr>
   <td></td><td  class="details_screen">Month:<br />Tax on invoices:<br />Tax on expenses:<br />Tax owing</td>
     {foreach key=key item=item_sales from=$total_sales.$year}
	      <td  class="details_screen">{$key}<br />{$item_sales|siLocal_number_trim} &nbsp;<br />{$total_payments.$year.$key|siLocal_number_trim} &nbsp; <br />{$tax_summary.$year.$key|siLocal_number_trim} &nbsp;</td>
     {/foreach}

	</tr>
 {/foreach}
 </table>
