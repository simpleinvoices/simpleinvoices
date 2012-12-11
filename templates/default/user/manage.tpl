{*
/*
* Script: manage.tpl
* 	Biller manage template
*
*
* License:
*	 GPL v3 or above
*/
*}
	<div class="si_toolbar si_toolbar_top">
            <a href="index.php?module=user&view=add" class="">
                <img src="./images/common/add.png" alt="" />
                {$LANG.user_add}
            </a>
	</div>

{if $number_of_rows.count == 0}

	<div class="si_message">{$LANG.no_users}</div>

{else}
	
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/user/manage.js.php' LANG=$LANG}
	
{/if}