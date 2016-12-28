{*
 *  Script: manage.tpl
 * 	    Products manage template
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    http://www.simpleinvoices.org
 *}
<div class="si_toolbar si_toolbar_form">
  <a href="index.php?module=expense&view=add" class="positive">
    <img src="images/famfam/add.png" alt=""/>
    {$LANG.add_new_expense}
  </a>
</div>
{if $number_of_rows == 0 }
<br />
<br />
<h2 class="welcome">{$LANG.no_expenses}</hw>
<br />
<br />
<br />
<br />
{else}
<br />
<table id="manageGrid" style="display:none"></table>
{include file='extensions/expense/modules/expense/manage.js.php'}
{/if}
