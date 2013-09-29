{*
/*
* Script: manage.tpl
* 	 Customer manage template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

	<div class="si_toolbar si_toolbar_top">
            <a href="./index.php?module=customers&amp;view=add" class="">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.customer_add}
            </a>
	</div>
 
{if $number_of_customers.count == 0}
	<div class="si_message">
		{$LANG.no_customers}
	</div>

{else}

	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/customers/manage.js.php'}

{/if}
