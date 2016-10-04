<form name="frmpost" action="index.php?module=expense_account&view=save&id={$smarty.get.id}" method="post">
{if $smarty.get.action == 'view'}
<br />
<table class="center">
  <tr>
    <td class="details_screen">{$LANG.name}</td>
    <td>{$expense_account.name}</td>
  </tr>
</table>
<br />
<div class="si_toolbar si_toolbar_form">
  <a href="./index.php?module=expense_account&view=details&id={$expense_account.id}&action=edit" class="positive">
    <img src="./images/famfam/add.png" alt=""/>
    {$LANG.edit}
  </a>
</div>
{else if $smarty.get.action == 'edit'}
<input type="hidden" name="op" value="edit" />
<input type="hidden" name="domain_id" value="{$expense_account.domain_id}" />
<br />
<table class="center">
  <tr>
    <td class="details_screen">{$LANG.name}</td>
    <td>
      <input type="text" name="name" size="50" value="{$expense_account.name}" id="name" class="validate[required]" />
    </td>
  </tr>
</table>
<br />
<div class="si_toolbar si_toolbar_form">
  <button type="submit" class="positive" name="submit" value="{$LANG.save}">
    <img class="button_img" src="./images/common/tick.png" alt="" />{$LANG.save}
  </button>
  <a href="./index.php?module=expense_account&amp;view=manage" class="negative">
    <img src="./images/common/cross.png" alt="" />
    {$LANG.cancel}
  </a>
</div>
{/if}
</form>
