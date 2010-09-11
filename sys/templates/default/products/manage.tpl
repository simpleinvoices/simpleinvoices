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
            <a href="./index.php?module=products&view=add" class="positive">
                <img src="{$smarty_embed_pathx}sys/images/famfam/add.png" alt=""/>
                {$LANG.add_new_product}
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
	<table id="manageGrid" style="display:none"></table>
	{include file="../sys/modules/products/manage.js.php"}
	
{/if}
