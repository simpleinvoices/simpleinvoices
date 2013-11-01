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
<div class="jombotron">
	<h1 class="title">{$LANG.billers}
        <a class="btn btn-default" href="./index.php?module=billers&amp;view=add" >
        	<span class="glyphicon glyphicon-plus"></span>
        	{$LANG.add_new_biller}
        </a>
    </h1>

{if $number_of_rows.count == 0}

	<div class="si_message">{$LANG.no_billers}</div>

{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/billers/manage.js.php' LANG=$LANG}

{/if}
</div>