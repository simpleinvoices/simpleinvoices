

<div id="top"><h3>{$LANG.monthly_sales_per_year}</h3></div>
 <hr />

<table width="100%">
 {foreach item=year from=$years}
   <tr>
   <td><b>{$year|htmlsafe}</b></td>
   <tr>
   <td></td><td>Month:<br />Sales:<br />Payments:</td>
     {foreach key=key item=item_sales from=$total_sales.$year}
	      <td>{$key|htmlsafe}
		  <br />{if $item_sales > 0}{$item_sales|siLocal_number}{else}{$item_sales|htmlsafe}{/if}
		  <br />{if $total_payments.$year.$key > 0}{$total_payments.$year.$key|siLocal_number}{else}{$total_payments.$year.$key|htmlsafe}{/if}</td>
     {/foreach}

	</tr>
 {/foreach}
 </table>
