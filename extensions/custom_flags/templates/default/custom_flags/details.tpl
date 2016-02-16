{*
 * Script: details.tpl
 * Custom flags details template
 *
 * License:
 *  GPL v3 or above
 *}

<form name="frmpost"
  action="index.php?module=custom_flags&view=save&associated_table={$cflg.associated_table|urlencode}&flg_id={$cflg.flg_id|urlencode}"
  method="POST" onsubmit="return frmpost_Validator(this);">
  {if $smarty.get.action == "view" }
  <div class="si_form si_form_view">
    <table>
      <tr>
        <th>{$LANG.associated_table}</th>
        <td>{$cflg.associated_table|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.flag_number}</th>
        <td>{$cflg.flg_id|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.field_label}</th>
        <td>{$cflg.field_label|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.enabled}</th>
        <td>{$cflg.wording_for_enabled|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.field_help}</th>
        <td>{$cflg.field_help|htmlsafe}</td>
      </tr>
    </table>
  </div>
  <div class="si_toolbar si_toolbar_form">
    <a href="./index.php?module=custom_flags&view=details&associated_table={$cflg.associated_table|urlencode}&flg_id={$cflg.flg_id|urlencode}&action=edit"
       class="positive">
       <img src="./images/famfam/report_edit.png" alt="" />
       {$LANG.edit}
    </a>
    <a href="./index.php?module=custom_flags&view=manage" class="negative">
       <img src="./images/common/cross.png" alt="" />
       {$LANG.cancel}
    </a>
  </div>
  {elseif $smarty.get.action == "edit" }
  <div class="si_form">
    <table>
      <tr>
        <th>
          {$LANG.associated_table}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_flags_associated_table"
             title="{$LANG.associated_table}"><img src="./images/common/help-small.png" alt="" /></a>
        </th>
        <td>{$cflg.associated_table|htmlsafe}</td>
      </tr>
      <tr>
        <th>
          {$LANG.flag_number}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_flags_flag_number"
             title="{$LANG.flag_number}"><img src="./images/common/help-small.png" alt="" /></a>
        </th>
        <td>{$cflg.flg_id|htmlsafe}</td>
      </tr>
      <tr>
        <th>
          {$LANG.field_label_upper}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_flags_field_label"
             title="{$LANG.field_label_upper}"><img src="./images/common/help-small.png" alt="" /></a>
        </th>
        <td><input type="text" name="{$LANG.field_label_upper|lower}" value="{$cflg.field_label|escape}" size="20" /></td>
      </tr>
      <tr>
        <th>{$LANG.enabled}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_flags_products"
             title="{$LANG.custom_flags_upper}"><img src="./images/common/help-small.png" alt="" /></a>
        </th>
        <td style="float:left;margin-left:auto;width:10px;">
          {html_options name=$LANG.enabled|lower options=$enable_options selected=$cflg.enabled|htmlsafe}
        </td>
      </tr>
      <tr>
        <th>
          {$LANG.reset_custom_flags}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_reset_custom_flags_products"
             title="{$LANG.reset_custom_flags}"><img src="./images/common/help-small.png" alt="" /></a>
        </th>
        <td><input type="checkbox" name="clear_custom_flags_{$cflg.flg_id}" value="1" /></td>
      </tr>
      <tr>
        <th>
          {$LANG.field_help_upper}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_flags_field_help"
             title="{$LANG.field_help_upper}"><img src="./images/common/help-small.png" alt="" /></a>
        </th>
        <td><textarea name="{$LANG.field_help_upper|lower}" cols="100" maxlength="255" >{$cflg.field_help|escape}</textarea></td>
      </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="save_custom_flag" value="{$LANG.save}">
        <img class="button_img" src="./images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="./index.php?module=custom_flags&view=manage" class="negative">
        <img src="./images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </div>
  <input type="hidden" name="op" value="edit_custom_flag">
  <input type="hidden" name="associated_table" value="{$cflg.associated_table|htmlsafe}" />
  <input type="hidden" name="flg_id" value="{$cflg.flg_id|htmlsafe}" />
  {/if}
</form>
