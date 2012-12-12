{*
/*
* Script: manage.tpl
* 	 Manage payment types template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

	<div class="si_toolbar si_toolbar_top">
            <a href="./index.php?module=payment_types&amp;view=add" class="">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.add_new_payment_type}
            </a>
	</div>

{if $paymentTypes==null }

	<div class="si_message">{$LANG.no_payment_types}</div>

{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/payment_types/manage.js.php'}

{/if}
