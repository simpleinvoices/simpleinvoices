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
{if $number_of_rows == null }
	<p><em>{$LANG.no_products}</em></p>
	<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=products&view=add" class="positive">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_product}
            </a>

        </td>
    </tr>
</table>
{else}
 {include file='../modules/products/manage.js.php' LANG=$LANG}
<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=products&view=add" class="positive">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_product}
            </a>

        </td>
    </tr>
</table>
<table id="manageGrid" style="display:none"></table>
 {include file='../modules/products/manage.js.php'}
{/if}
