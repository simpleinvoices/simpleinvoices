{*
/*
* Script: manage.tpl
* 	 Manage payment types template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=payment_types&amp;view=add" class="positive">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.add_new_payment_type}
            </a>

        </td>
    </tr>
</table>

{if $paymentTypes==null }

	<br />
	<br />
	<span class="welcome">{$LANG.no_payment_types}</span>
	<br />
	<br />
	<br />
	<br />
	
{else}

	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/payment_types/manage.js.php'}

{/if}
