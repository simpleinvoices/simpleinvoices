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

<hr />
<table id="manageGrid" style="display:none"></table>
 {include file='../modules/user/manage.js.php' LANG=$LANG}

{/if}
