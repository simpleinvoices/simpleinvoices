{*
 *  Script: manage.tpl
 *      Manage invoices template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
*}
<div class="si_toolbar si_toolbar_top">
  <a href="index.php?module=inventory&amp;view=add" class="">
    <img src="images/common/add.png" alt="" />
    {$LANG.new_inventory_movement}
  </a>
</div>
{if $number_of_rows == 0}
  <div class="si_message">{$LANG.no_inventory_movements}</div>
{else}
  <table id="manageGrid" style="display:none"></table>
  {include file='modules/inventory/manage.js.php'}
{/if}

