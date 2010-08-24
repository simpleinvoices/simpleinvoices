{*
/*
* Script: manage.tpl
* 	 Products manage template
*
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
            <a href="./index.php?module=expense&view=add" class="positive">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_expense}
            </a>

        </td>
    </tr>
</table>

{if $number_of_rows.count == 0 }

	<br />
	<br />
	<span class="welcome">{$LANG.no_products}</span>
	<br />
	<br />
	<br />
	<br />

{else}
	<br />
{*
    <span class="welcome">
       Filters:
    <a href="index.php?module=expense&view=manage&qtype=status&query=0">{$LANG.not_paid}</a> : 
    <a href="index.php?module=expense&view=manage&qtype=status&query=1">{$LANG.paid}</a> : 
    <a href="index.php?module=invoices&view=manage">Clear filter</a> 

   </span>
    <br />
    <br />
	<br />
*}
	<table id="manageGrid" style="display:none"></table>
	{include file='../extensions/expense/modules/expense/manage.js.php'}
	
{/if}
