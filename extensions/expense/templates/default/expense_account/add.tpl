{* if bill is updated or saved.*}
{if $smarty.post.name != "" }
  {include file="extensions/expense/templates/default/expense_account/save.tpl"}
{else}
  {* if name was inserted *}
  {if $smarty.post.name !=null}
  <div class="validation_alert">
    <img src="images/common/important.png" alt="" /> You must enter a  name for the account
  </div>
  <hr />
  {/if}
  <form name="frmpost" action="index.php?module=expense_account&view=add"  method="POST">
    <input type="hidden" name="op" value="insert" />
    <input type="hidden" name="domain_id" value="{$domain_id}" />
    <br />
    <table class="center">
      <tr>
        <td class="details_screen">{$LANG.description}
          <a class="cluetip" href="#" title="{$LANG.required_field}"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" >
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </td>
        <td>
          <input type="text" name="name" value="{$smarty.post.name}" size="50" id="name"
                 class="validate[required]" />
        </td>
      </tr>
    </table>
    <br />
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="id" value="{$LANG.save}">
        <img class="button_img" src="images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="index.php?module=expense_account&amp;view=manage" class="negative">
        <img src="images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </form>
{/if}
