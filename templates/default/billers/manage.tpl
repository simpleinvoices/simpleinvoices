{*
/*
* Script: manage.tpl
* 	Biller manage template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
{if $number_of_customers.count == 0}
	<p><em>{$LANG.no_billers}</em></p>
{else}

 {include file='../modules/billers/manage.js.php' LANG=$LANG}

<h3>{$LANG.manage_billers} :: <a href="index.php?module=billers&view=add">{$LANG.biller_add}</a></h3>
<hr />
<div id="manageGrid"></div>

{/if}
