{*
/*
* Script: manage.tpl
* 	 Customer manage template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=customers&amp;view=add" class="positive">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.customer_add}
            </a>

        </td>
    </tr>
 </table>
 
{if $number_of_customers.count == 0}
	<br />
	<br />
	<span class="welcome">{$LANG.no_customers}</span>
	<br />
	<br />
	<br />
	<br />
	
{else}

	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/customers/manage.js.php'}

{/if}
