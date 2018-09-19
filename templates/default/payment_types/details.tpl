{*
 *  Script: details.tpl
 *      Payment type details template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group/doku.php?id=si_wiki:menu *}
{if $smarty.get.action == "view" }
<div class="si_form si_form_view">
  <table>
    <tr>
      <th style="font-weight: bold;">{$LANG.description}</th>
      <td>{$paymentType.pt_description|htmlsafe}</td>
    </tr>
    <tr>
      <th style="font-weight: bold;">{$LANG.status}</th>
      <td>{$paymentType.enabled|htmlsafe}</td>
    </tr>
  </table>
</div>
<div class="si_toolbar si_toolbar_form">
  <a href="index.php?module=payment_types&amp;view=details&amp;id={$paymentType.pt_id}&amp;action=edit" class="positive">
    <img src="images/famfam/report_edit.png" alt="" />
    {$LANG.edit}
  </a>
  <a href="index.php?module=payment_types&amp;view=manage" class="negative">
    <img src="images/common/cross.png" alt="" />
    {$LANG.cancel}
  </a>
</div>
{else}
<form name="frmpost" method="post" onsubmit="return frmpost_Validator(this)"
      action="index.php?module=payment_types&amp;view=save&amp;id={$smarty.get.id|htmlsafe}">
  <div class="si_form">
    <input type="hidden" name="op" value="edit_payment_type">
    <table>
      <tr>
        <td class="details_screen">{$LANG.description}
          <a class="cluetip cluetip-clicked" href="#" title="Payment Type Description"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field">
            <img src="{$help_image_path}required-small.png" alt="(required)" />
          </a>
        </td>
        <td>
          <input type="text" class="validate[required]" name="pt_description" size-"30"
                 value="{$paymentType.pt_description|htmlsafe|htmlsafe}" tabindex="10" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.status}</th>
        <td>{html_options name=pt_enabled options=$enabled selected=$paymentType.pt_enabled tabindex=20}</td>
      </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="save_payment_type" value="{$LANG.save}">
        <img class="button_img" src="images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="index.php?module=payment_types&amp;view=manage" class="negative"> <img src="images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </div>
</form>
{/if}
