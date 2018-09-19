{*
*  Script: email.tpl
*      Send invoice via email page template
*
*  Authors:
*      Justin Kelly, Nicolas Ruflin
*
*  Last edited:
*      2016-08-03
*
*  License:
*      GPL v3 or above
*
*  Website:
*      https://simpleinvoices.group/doku.php?id=si_wiki:menu*}
{if $smarty.get.stage == 1 }
  {if $error == 1 }
  <div class="si_message_error"><h2>{$message}</h2></div>
  {/if}
  <div class="si_center">
    <h3>Email {$invoice.index_name|htmlsafe} to Customer as PDF</h3>
  </div>
  <form name="frmpost" action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={$smarty.get.id|urlencode}" method="post">
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
            <input type="text" name="email_from" size="50" value="{$biller.email|htmlsafe}" tabindex="10"
                   class="validate[required]" />
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
            <input type="text" name="email_to" size="50" value="{$customer.email|htmlsafe}" tabindex="20"
                   class="validate[required]" />
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
          <td><textarea name="email_notes" class="editor" rows="16" cols="70" tabindex="50" ></textarea></td>
        </tr>
<!--  TODO: Eventual use for adding additional attachments
        <tr>
          <th>{$LANG.attachments}</th>
          <td><input type="file" name="attachments[]" accept=".pdf|.txt|.doc|.docx|image/*" tabindex="60" /></td>
        </tr>
 -->
      </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}" tabindex="70" >
        <img class="button_img" src="images/common/tick.png" alt="" />
        {$LANG.email}
      </button>
    </div>
    <input type="hidden" name="op" value="insert_customer" />
  </form>
{else if $smarty.get.stage == 2}
  <meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=manage" />
  <div class="si_message">
    {$message|outhtml}
  </div>
{/if}
