{*
 * Script: details.tpl
 *     User detail template
 * Last edited:
 *     2016-07-09
 * License: GPL v3 or above
 *}
{literal}
<script>
function setuseridlist() {
  var role = document.getElementById("role_id1");
  var role_idx = role.selectedIndex;
  var role_val = role.options[role_idx].value;
  var role_text = role.options[role_idx].text;
  var orole_val = document.getElementById("origrole1").value ;
  if (role_text == orole_val) return;
  
  var crole_elem = document.getElementById('currrole1");
  crole_elem.value = role_text;

  var list = document.getElementById("user_id1");
  var newlist = "";
  if (role_text == "customer") {
    var cust = document.getElementById("cust1");
    var cust_value = cust.value;
    var cust_vals = cust_value.split("~");
    for (var i=0; i<cust_vals.length; i++) {
      var tmp = cust_vals[i].split(" ");
      newlist += '<option value="' + tmp[0] + '">' + cust_vals[i] + '</option>';
    } 
  } else if (role_text == "biller") {
    var billers = document.getElementById("bilr1");
    var billers_value = billers.value;
    var billers_vals = billers_value.split("~");
    for (var i=0; i<billers_vals.length; i++) {
      var tmp = billers_vals[i].split(" ");
      newlist += '<option value="' + tmp[0] + '">' + billers_vals[i] + '</option>';
    } 
  } else {
    newlist = '<option selected value="0">0 - User</option>';
  }
  list.innerHTML = newlist;
  return;
}
</script>
{/literal}
<form name="frmpost"
      action="index.php?module=user&view=save&id={$smarty.get.id|urlencode}"
      method="post" id="frmpost" onsubmit="return checkForm(this);">
  {if $smarty.get.action== 'view' }
  <div class="si_form si_form_view">
    <table>
      <tr>
        <th>{$LANG.username}</th>
        <td>{$user.username|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.password}</th>
        <td>**********</td>
      </tr>
      <tr>
        <th>{$LANG.role}</th>
        <td>{$user.role_name|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.email}</th>
        <td>{$user.email|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.enabled}</th>
        <td>{$user.enabled_txt|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.user_id}</th>
        <td>{$user_id_desc|htmlsafe}</td>
      </tr>
    </table>
  </div>
  <div class="si_toolbar si_toolbar_form">
    <a href="./index.php?module=user&view=details&id={$user.id|urlencode}&action=edit"
       class="positive">
      <img src="./images/famfam/report_edit.png" alt="" />
      {$LANG.edit}
    </a>
    <a href="./index.php?module=user&view=manage" class="negative">
      <img src="./images/common/cross.png" alt="" />
      {$LANG.cancel}
    </a>
  </div>
  {elseif $smarty.get.action== 'edit' }
  <input type="hidden" name="cust" id="cust1" value="{$cust}" />
  <input type="hidden" name="bilr" id="bilr1" value="{$bilr}" />
  <input type="hidden" name="origrole" id="origrole1" value="{$orig_role_name}" />
  <input type="hidden" name="currrole" id="currrole1" value="{$orig_role_name}" />
  <input type="hidden" name="origuserid" id="origuserid1" value="{$orig_user_id}" />
  <div class="si_form">
    <table>
      <tr>
        <th>{$LANG.username}
          <a class="cluetip" href="#" tabindex="910"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_username"
             title="{$LANG.required_field}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="username" autocomplete="off" tabindex="10"
                 value="{$user.username|htmlsafe}" size="30" id="username"
                 {literal}pattern="(?=^.{6,}$)([A-Za-z][A-Za-z0-9@_\-\.#\$]+)$"{/literal}
                 title="See help for details." class="validate[required]" autofocus />
        </td>
      </tr>
      <tr>
        <th>{$LANG.new_password}
          <a class="cluetip" href="#" tabindex="920"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
             title="{$LANG.new_password}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="password" name="password" id="password_id" size="20" tabindex="20"
                 pattern="{$pwd_pattern}" title="See help for details."
                 onchange="genConfirmPattern(this,'confirm_pwd_id');" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.confirm_password}
          <a class="cluetip" href="#" tabindex="930"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_confirm_password"
             title="{$LANG.confirm_password}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="password" name="confirm_password" id="confirm_pwd_id"
                 size="20" tabindex="30" pattern="{$pattern}" />
        </td>
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
          <input type="text" name="email" autocomplete="off" tabindex="40"
                 value="{$user.email|htmlsafe}" size="35" id="email"
                 class="validate[required]" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.role}
          <a class="cluetip" href="#" tabindex="950"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
             title="{$LANG.role}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td>
          <select name="role_id" id="role_id1" tabindex="50" onchange="setuseridlist();" >
          {foreach from=$roles item=role}
            <option {if $role.id == $user.role_id}selected{/if} value="{$role.id|htmlsafe}">
              {$role.name|htmlsafe}
            </option>
          {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <th>{$LANG.user_id}
          <a class="cluetip" href="#" tabindex="960"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_user_id"
             title="{$LANG.user_id}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td>
          <select name="user_id" id="user_id1" tabindex="60">
          {if $user.role_name == "customer"}
            {assign var="ids" value="~"|explode:$cust}
            {foreach from=$ids item=id}
              {assign var="pts" value="-"|explode:$id}
              {assign var="uid" value=$pts[0]-1}
              <option {if $user.user_id == trim($pts[0])}selected{/if} value="{$uid|htmlsafe}">
                {$id|htmlsafe}
              </option>
            {/foreach}
          {elseif $user.role_name == "biller"}
            {assign var="ids" value="~"|explode:$bilr}
            {foreach from=$ids item=id}
              {assign var="pts" value="-"|explode:$id}
              {assign var="uid" value=$pts[0]-1}
              <option {if $user.user_id == trim($pts[0])}selected{/if} value="{$uid|htmlsafe}">
                {$id|htmlsafe}
              </option>
            {/foreach}
          {else}
            <option selected value="0">{$user_id_desc|htmlsafe}</option>
          {/if}
          </select>
        </td>
      </tr>
      <tr>
        <th>{$LANG.enabled}
          <a class="cluetip" href="#" tabindex="970" 
             rel="index.php?module=documentation&amp;view=view&amp;page=help_user_enabled"
             title="{$LANG.enabled} / {$LANG.disabled}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td>{html_options name=enabled options=$enabled_options selected=$user.enabled tabindex=70}</td>
      </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="save_user" tabindex="100">
        <img class="button_img" src="./images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="./index.php?module=user&view=manage" class="negative" tabindex="110">
        <img src="./images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </div>
  <input type="hidden" name="op" value="edit_user" />
  <input type="hidden" name="domain_id" value="{$user.domain_id|htmlsafe}" />
  {/if}
</form>
