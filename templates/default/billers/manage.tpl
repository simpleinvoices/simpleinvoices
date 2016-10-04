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
            <a href="./index.php?module=billers&amp;view=add" class="">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.add_new_biller}
            </a>
	</div>

{if $number_of_rows.count == 0}

	<div class="si_message">{$LANG.no_billers}</div>

{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../templates/default/billers/manage.js.tpl' LANG=$LANG}

{/if}
