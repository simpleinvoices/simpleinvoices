{*
 *  Script: manage_cronlogs.tpl
 *      Manage Cron Logs template
 *
 *  Authors:
 *      Ap.Muthu
 *
 *  Last edited:
 *      2017-01-18
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}

<h3>Cron Log - Recurrent Invoices Inserted</h3>
<hr />
<table class="manage" id="live-grid" class="center">
  <colgroup>
    <col style='width: 20%;' />
    <col style='width: 30%;' />
    <col style='width: 20%;' />
    <!--    <col style='width:30%;' /> -->
  </colgroup>
  <thead>
    <tr>
      <th class="sortable">ID</th>
      <th class="sortable">Date</th>
      <th class="sortable">Cron ID</th>
      <!--    <th class="sortable">Invoice No</th> -->
    </tr>
  </thead>
  {foreach from=$cronlogs item=cronlog}
  <tr>
    <td class='index_table'>{$cronlog.id|htmlsafe}</td>
    <td class='index_table'>{$cronlog.run_date|htmlsafe}</td>
    <td class='index_table'><a href="index.php?module=cron&view=view&id={$cronlog.cron_id|htmlsafe}">{$cronlog.cron_id|htmlsafe}</a></td>
  </tr>
  {/foreach}


</table>
