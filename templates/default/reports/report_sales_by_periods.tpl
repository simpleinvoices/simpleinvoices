

<div id="top"><h3>{$LANG.monthly_sales_per_year}</h3></div>
 <hr />

<table width="100%">
 {foreach item=year from=$years}
   <tr>
   <td><b>{$year}</b></td>
   <tr>
   <td></td><td>Month:<br>Sales:<br>Payments:</td>
     {foreach key=key item=item_sales from=$total_sales.$year}
	      <td>{$key}<br>{$item_sales}<br>{$total_payments.$year.$key}  </td>
     {/foreach}

	</tr>
 {/foreach}
 </table>


 
 
