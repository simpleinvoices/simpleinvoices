<br />	 

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.invoice}</td>
		<td>
				{$cron.index_name}
		</td>
	</tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.start_date}</td>
            <td>
                {$cron.start_date}    
            </td>
    </tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.end_date}</td>
            <td >
                {$cron.end_date}   
            </td>
    </tr>
	<tr>
		<td class="details_screen">{$LANG.recur_each}</td>
		<td>
		{$cron.recurrence} {$cron.recurrence_type}
         </td>
     </tr>
	<tr>
		<td class="details_screen">{$LANG.email_biller_after_cron}</td>
		<td>
             {if $cron.email_biller == '1'}{$LANG.yes}{/if}</option>
             {if $cron.email_biller == '0'}{$LANG.no}{/if}</option>
         </td>
     </tr>
	<tr>
		<td class="details_screen">{$LANG.email_customer_after_cron}</td>
		<td>
             {if $cron.email_customer == '1'}{$LANG.yes}{/if}</option>
             {if $cron.email_customer == '0'}{$LANG.no}{/if}</option>
         </td>
     </tr>


</table>
<br />
<table class="buttons" align="center">
	<tr>
		<td>
				<a href="./index.php?module=cron&amp;view=edit&amp;id={$cron.id}" class="positive">
					<img src="./images/famfam/report_edit.png" alt=""/>
					{$LANG.edit}
				</a>
			<a href="./index.php?module=cron&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


