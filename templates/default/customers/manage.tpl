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

{if $number_of_customers.count == 0}
	<p><em>{$LANG.no_customers}</em></p>
{else}

<h3>{$LANG.manage_customers} :: <a href="index.php?module=customers&view=add">{$LANG.customer_add}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/customers/manage.js.php'}

{/if}
