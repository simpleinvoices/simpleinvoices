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

	<div class="si_toolbar si_toolbar_top">
            <a href="index.php?module=tax_rates&view=add" class="">
                <img src="./images/common/add.png" alt="" />
                {$LANG.add_new_tax_rate}
            </a>
	</div>

{if $taxes == null}

	<div class="si_message">{$LANG.no_tax_rates}</div>

{else}

	<div id="manageGrid"></div>
	{include file='tax_rates/manage.js.php'}
 
{/if}
