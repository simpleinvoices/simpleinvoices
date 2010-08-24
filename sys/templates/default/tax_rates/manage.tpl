{*
/*
* Script: manage.tpl
* 	 Tax Rates manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}

<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=tax_rates&view=add" class="positive">
                <img src="./images/common/add.png" alt="" />
                {$LANG.add_new_tax_rate}
            </a>

        </td>
    </tr>
</table>

{if $taxes == null}

	<br />
	<br />
	<span class="welcome">{$LANG.no_tax_rates}</span>
	<br />
	<br />
	<br />
	<br />

{else}
	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/tax_rates/manage.js.php'}
 
{/if}
