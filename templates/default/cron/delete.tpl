{*
 *  Script: delete.tpl
 *      Cron delete
 *
 *  Authors:
 *      Rich Rowley
 *
 *  Last edited:
 *      2016-08-08
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<br />
<br />
{if isset($err_message) && $err_message != ""}
<meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
<br />
<br />
<h1 style="font-weight: bold; font-color: red;text-align:center;">{$err_message|htmlsafe}</h1>
<br />
<br />
{/if}
{if $smarty.get.stage == 1}
<h3 style="text-align:center">Select <b>Delete</b> to remove this record:</h3>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=cron&view=delete&id={$cron.id|urlencode}&stage=2" >
  <input type="hidden" name="index_id" value="{$cron.index_id}">
  <div class="si_form si_form_view">
    <table class="center">
      <tr>
        <td class="details_screen">{$LANG.invoice}</td>
        <td>{$cron.index_id|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.start_date}</td>
        <td>{$cron.start_date|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.end_date}</td>
        <td>{$cron.end_date|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.recur_each}</td>
        <td>{$cron.recurrence|htmlsafe} {$cron.recurrence_type|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.email_biller_after_cron}</td>
        <td>
          {if $cron.email_biller == '1'}{$LANG.yes}{/if}
          {if $cron.email_biller == '0'}{$LANG.no}{/if}
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.email_customer_after_cron}</td>
        <td>
          {if $cron.email_customer == '1'}{$LANG.yes}{/if}
          {if $cron.email_customer == '0'}{$LANG.no}{/if}
        </td>
      </tr>
    </table>
  </div>
  <div class="si_toolbar si_toolbar_form">
    <button type="submit" class="positive" name="id" value="{$LANG.delete}">
      <img class="button_img" src="images/common/tick.png" alt="" />
      {$LANG.delete}
    </button>
    <a href="index.php?module=cron&amp;view=manage" class="negative">
      <img src="images/common/cross.png" alt="" />
      {$LANG.cancel}
    </a>
  </div>
</form>
{else if $smarty.get.stage == 2}
<meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
<br />
<br />
<h2 style="text-align:center;">Deleted record for Invoice #{$index_id|htmlsafe}</h2>
<br />
<br />
{/if}
