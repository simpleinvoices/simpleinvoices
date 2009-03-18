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

{if $number_of_customers.count == 0}
	<p><em>{$LANG.no_customers}</em></p>
	<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=customers&view=add" class="positive">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.customer_add}
            </a>

        </td>
    </tr>
 </table>
{else}

<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=customers&view=add" class="positive">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.customer_add}
            </a>

        </td>
    </tr>
 </table>
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/customers/manage.js.php'}

{/if}
