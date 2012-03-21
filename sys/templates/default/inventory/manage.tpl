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

<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=inventory&amp;view=add" class="positive">
                <img src="{$baseUrl}sys/images/common/add.png" alt="" />
                {$LANG.new_inventory_movement}
            </a>

        </td>
    </tr>
</table>

{if $number_of_rows.count == 0}
	
	<br />
	<br />
	<span class="welcome">{$LANG.no_inventory_movements}</span>
	<br />
	<br />
	<br />
	<br />
{else}


	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file="$smarty_embed_path/sys/modules/inventory/manage.js.php"}

{/if}


