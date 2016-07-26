{*
 * Script: add.tpl
 *   User add template
 *
 * Authors:
 *  Justin Kelly, Nicolas Ruflin, Soif, Rich Rowley
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
          <a class="cluetip" href="#" tabindex="910"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_username"
             title="{$LANG.username}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="username" size="35" id="username"
                 autocomplete="off" class="validate[required]" tabindex="10" autofocus />
        </td>
      </tr>
      <tr>
        <th>{$LANG.new_password}
          <a class="cluetip" href="#" tabindex="920"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
             title="{$LANG.new_password}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td><input type="password" name="password" size="20" pattern="{$pwd_pattern}"
                   title="See help for details." tabindex="20"
                   class="validate[required]"
                   onchange="genConfirmPattern(this,'confirm_pwd_id');"/></td>
      </tr>
      <tr>
        <th>{$LANG.confirm_password}
          <a class="cluetip" href="#" tabindex="930"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_confirm_password"
             title="{$LANG.confirm_password}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td><input type="password" name="confirm_password" size="20" tabindex="30"
                   class="validate[required]" pattern="{$pwd_pattern}" /></td>
      </tr>
      <tr>
        <th>{$LANG.email}
          <a class="cluetip" href="#" tabindex="940"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_email_address"
             title="{$LANG.required_field}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="email" size="35" id="email"
                 class="validate[required]" tabindex="40" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.role}
          <a class="cluetip" href="#" tabindex="950"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
             title="{$LANG.role}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <select name="role_id" tabindex="50" >
          {foreach from=$roles item=role name=urole}
            <option value="{$role.id|htmlsafe}" {if $smarty.foreach.urole.index == 0}selected{/if}>
              {$role.name|htmlsafe}
            </option>
          {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <th>{$LANG.enabled}</th>
        <td>{html_options name=enabled options=$enabled selected=1 tabindex=60}</td>
      </tr>
      <tr>
        <th>{$LANG.user_id}
          <a class="cluetip" href="#" tabindex="970"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_user_id"
             title="{$LANG.user_id}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>

        </th>
        <td>
          <select name="user_id" tabindex="60">
            <option selected value="0">0 - USER</option>
            {foreach from=$cust_info item=cust}
            <option value="{$cust.id|htmlsafe}">{$cust.id|htmlsafe} - {$cust.name|htmlsafe}</option>
            {/foreach}
          </select>

          <input type="text" name="user_id" value="0" size="12" id="user_id"
                 autocomplete="off" class="validate[required]"  tabindex="70"/>
        </td>
      </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="submit" value="Insert User">
        <img class="button_img" src="./images/common/tick.png" alt="" tabindex="100" />
        {$LANG.save}
      </button>
      <a href="./index.php?module=user&view=manage" class="negative" tabindex="110">
        <img src="./images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </div>
  <input type="hidden" name="op" value="insert_user" />
  <input type="hidden" name="domain_id" />
</form>
{/if}
