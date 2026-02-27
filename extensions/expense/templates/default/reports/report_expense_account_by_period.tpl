
<form name="frmpost" action="index.php?module=reports&amp;view=report_expense_account_by_period" method="post">
<table align="center">
    <tr>
        <td wrap="nowrap">Start date (YYYY-MM-DD)
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date}' />   
         </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td wrap="nowrap" >End date (YYYY-MM-DD)
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date}' />   
            </td>
    </tr>
</table>
<br />
<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="{$LANG.insert_biller}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                Run report
            </button>

        </td>
    </tr>
</table>
</form>
<div id="top"><h3>Expense account summary for the period {$start_date} to {$end_date}</h3></div>

<table align="center">
    <tr>
        <td  class="details_screen">
            <b>Account</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Amount</b>
        </td>
	</tr>
 {foreach item=account from=$accounts}
    <tr>
        <td class="details_screen">
            {$account.account}
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$account.expense|siLocal_number}
        </td>
	</tr>
 {/foreach}
 </table>
