<br />	 

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.date_upper}</td>
		<td>
				{$inventory.date|htmlsafe}
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product}</td>
		<td>
				{$inventory.description|htmlsafe}
		</td>
	</tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.quantity}</td>
            <td>
                {$inventory.quantity|siLocal_number}    
            </td>
    </tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.cost}</td>
            <td >
                {$inventory.cost|siLocal_number}   
            </td>
    </tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td>
		{$inventory.note}
         </td>
     </tr>

</table>
<br />
<table class="buttons" align="center">
	<tr>
		<td>
				<a href="./index.php?module=inventory&amp;view=edit&amp;id={$inventory.id|urlencode}" class="positive">
					<img src="./images/famfam/report_edit.png" alt=""/>
					{$LANG.edit}
				</a>
			<a href="./index.php?module=inventory&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


