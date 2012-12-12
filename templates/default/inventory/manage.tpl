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

	<div class="si_toolbar si_toolbar_top">
            <a href="index.php?module=inventory&amp;view=add" class="">
                <img src="./images/common/add.png" alt="" />
                {$LANG.new_inventory_movement}
            </a>
	</div>

{if $number_of_rows.count == 0}

	<div class="si_message">{$LANG.no_inventory_movements}</div>

{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/inventory/manage.js.php'}

{/if}


