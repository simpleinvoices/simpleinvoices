{*
/*
* Script: manage.tpl
* 	 Products manage template
*
*
* License:
*	 GPL v3 or above
*/
*}
	<div class="si_toolbar si_toolbar_top">
            <a href="./index.php?module=products&view=add" class="">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_product}
            </a>
	</div>

{if $number_of_rows.count == 0 }

	<div class="si_message">{$LANG.no_products}</div>

{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/products/manage.js.php'}
	
{/if}
