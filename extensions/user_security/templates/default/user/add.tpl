{*
 * Script: save.tpl
 *   User save template
 *
 * Authors:
 *  Justin Kelly, Nicolas Ruflin, Soif
 *  Rich Rowley
 *
 * Last edited:
 *    2016-05-28
 *
 * License:
 *  GPL v3 or above
 *}
{if $smarty.post.username != null && $smarty.post.submit != null }
  {include file="../extensions/user_security/templates/default/user/save.tpl"}
{else}
<form name="frmpost" action="index.php?module=user&amp;view=add" method="post" id="frmpost">
  <div class="si_form">
    <table>
      <tr>
        <th>{$LANG.username}
          <a class="cluetip" href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
             title="{$LANG.required_field}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="username" value="{$smarty.post.username|htmlsafe}" size="35" id="username"
                 autocomplete="off" class="validate[required]" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.new_password}
          <a class="cluetip" href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
             title="{$LANG.new_password}">
          <img src="{$help_image_path}help-small.png" alt="" />
        </a>
        </th>
        <td><input type="password" name="password" size="20" pattern="{$pwd_pattern}"
                   title="See help for details."
                   onchange="form.confirm_password.pattern = this.value;"/></td>
      </tr>
      <tr>
        <th>{$LANG.confirm_password}
          <a class="cluetip" href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_confirm_password"
             title="{$LANG.confirm_password}">
            <img src="{$help_image_path}help-small.png" alt="" />
        </a>
        </th>
        <td><input type="password" name="confirm_password" size="20" pattern="{$pwd_pattern}" /></td>
      </tr>
      <tr>
        <th>{$LANG.email}
          <a class="cluetip" href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_email_address"
             title="{$LANG.required_field}">
            <img src="{$help_image_path}required-small.png" alt="" />
        </a>
        </th>
        <td>
          <input type="text" name="email" value="{$smarty.post.email|htmlsafe}" size="35" id="email"
                 autocomplete="off" class="validate[required]" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.role}
          <a class="cluetip" href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
             title="{$LANG.role}">
            <img src="{$help_image_path}help-small.png" alt="" />
        </a>
        </th>
        <td>
          <select name="role">
          {foreach from=$roles item=role}
            <option value="{$role.id|htmlsafe}">{$role.name|htmlsafe}</option>
          {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <th>{$LANG.enabled}</th>
        <td>{html_options name=enabled options=$enabled selected=1}</td>
      </tr>
      <tr>
        <th>{$LANG.users}</th>
        <td>
          <input type="text" name="user_id" value="{$smarty.post.user_id|htmlsafe}" size="12" id="user_id"
                 autocomplete="off" class="validate[required]" />
        </td>
      </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="submit" value="Insert User">
        <img class="button_img" src="./images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="./index.php?module=user&view=manage" class="negative">
        <img src="./images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </div>
  <input type="hidden" name="op" value="insert_user" />
</form>
{/if}
