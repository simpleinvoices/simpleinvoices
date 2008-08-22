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
{if $number_of_rows.count == 0}
	<p><em>{$LANG.no_billers}</em></p>
{else}

<h3>{$LANG.manage_billers} :: <a href="index.php?module=billers&view=add">{$LANG.add_new_biller}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>
 {include file='../modules/billers/manage.js.php' LANG=$LANG}

{/if}
