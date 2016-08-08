{*
 *  Script: email.tpl
 *      Send invoice via email page template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-01-16 by Rich Rowley to add signature file
 *      2007-08-03
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 *}
{if $smarty.get.stage == 1 }
<div class="si_center">
  <h3>Email {$invoice.index_name|htmlsafe} to Customer as PDF</h3>
</div>

<form name="frmpost"
      action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={$smarty.get.id|urlencode}"
      method="post">
  <div class="si_form">
    <table>
      <tr>
        <th>{$LANG.email_from}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_from"
             title="{$LANG.email_from} {$LANG.required_field}">
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="email_from" size="50" value="{$biller.email|htmlsafe}"
                 class="validate[required]" tabindex="10" autofocus />
        </td>
      </tr>
      <tr>
        <th>{$LANG.email_to}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_to"
             title="{$LANG.email_to} {$LANG.required_field}" >
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="email_to" size="50" value="{$customer.email|htmlsafe}"
                 class="validate[required]" tabindex="20" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.email_bcc}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_bcc"
             title="{$LANG.email_bcc}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td><input type="text" name="email_bcc" size="50" value="{$biller.email|htmlsafe}" tabindex="30" /></td>
      </tr>
      <tr>
        <th>{$LANG.subject}
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
             title="{$LANG.subject} {$LANG.required_field}" >
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>
        </th>
        <td>
          <input type="text" name="email_subject" size="70" class="validate[required]" tabindex="40"
                 value="{$invoice.index_name|htmlsafe} from {$biller.name|htmlsafe} is attached" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.message}</th>
        <td>
          <textarea name="email_notes" class="editor" rows="16" cols="70" tabindex="50" >
            {if !empty($biller.signature)}{$biller.signature|htmlsafe}{/if}
          </textarea>
        </td>
      </tr>
    </table>
  </div>
  <div class="si_toolbar si_toolbar_form">
    <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}" tabindex="60" >
      <img class="button_img" src="./images/common/tick.png" alt="" /> 
      {$LANG.email}
    </button>
  </div>
  <input type="hidden" name="op" value="insert_customer" />
</form>
{elseif $smarty.get.stage == 2}
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=manage" />
<div class="si_message">
  {$message|outhtml}
</div>
{/if}
