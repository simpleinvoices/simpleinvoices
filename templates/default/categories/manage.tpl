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
            <a href="./index.php?module=categories&view=add" class="">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_category}
            </a>
	</div>

{if $number_of_rows.count == 0 }

	<div class="si_message">{$LANG.no_categories}</div>

{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/categories/manage.js.php'}
	
{/if}