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

	<h1 class="title">{$LANG.customers}
        <a class="btn btn-default" href="./index.php?module=customers&amp;view=add" >
        	<span class="glyphicon glyphicon-plus"></span>
        	{$LANG.customer_add}
        </a>
    </h1>
    
{if $number_of_customers.count == 0}
	<div class="si_message">
		{$LANG.no_customers}
	</div>

{else}

	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/customers/manage.js.php'}

{/if}
