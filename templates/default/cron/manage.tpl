{*
 *  Script: manage.tpl
 *    Manage invoices template
 *
 *  License:
 *    GPL v3 or above
 *
 *  Website:
 *    https://simpleinvoices.group
 *}

<div class="si_toolbar si_toolbar_top">
  <a href="index.php?module=cron&amp;view=add" class=""><img src="images/common/add.png" alt="" />{$LANG.new_recurrence}</a>
</div>


{if $number_of_crons == 0}
  <div class="si_message">{$LANG.no_crons} </div>
{else}
  <table id="manageGrid" style="display:none"></table>
  {include file='modules/cron/manage.js.php'}
{/if}
