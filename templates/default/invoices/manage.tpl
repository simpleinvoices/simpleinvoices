{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $number_of_invoices.count == 0}
	<p><em>{$LANG.no_invoices}</em></p>
{else}

<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=invoices&view=itemised" class="positive">
                <img src="./images/common/add.png" alt=""/>
                Add a new Invoice {* TODO $LANG  *}
            </a>

        </td>
    </tr>
</table>

<table id="manageGrid" style="display:none"></table>

 {include file='../modules/invoices/manage.js.php'}
{/if}
