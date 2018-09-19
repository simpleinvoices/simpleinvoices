{*
 *  Script: details.tpl
 *    Invoice details template
 *
 *  Modified for 'default_invoices' by Marcel van Dorp. Version 20090208
 *    if no invoice_id set, the date will be today, and the action will be
 *    'insert' instead of 'edit'
 *  Modified by Rich Rowley 20160212 to fix missing closing <td> tag and format for readability.
 *
 *  License:
 *    GPL v3 or above
 *
 *  Website:
 *    https://simpleinvoices.group/doku.php?id=si_wiki:menu *}

{* Still needed ?*}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
  <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." />
  {$LANG.loading} ...
</div>
<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post">
  <div class="si_invoice_form">
    <table class='si_invoice_top'>
      <tr>
        <th>{$preference.pref_inv_wording|htmlsafe} {$LANG.number_short}</th>
        <td>{$invoice.index_id|htmlsafe}</td>
      </tr>
      <tr>
        <th>{$LANG.date_formatted}</th>
        {if $invoice.id == null}
        <td>
          <input type="text" size="10" class="date-picker" name="date" id="date1"
                 value="{$smarty.now|date_format:'%Y-%m-%d'}" />
        </td>
        {else}
        <td>
          <input type="text" size="10" class="date-picker" name="date" id="date1"
                 value="{$invoice.calc_date|htmlsafe}" />
        </td>
        {/if}
      </tr>
      <tr>
        <th>{$LANG.biller}</th>
        <td>
        {if $billers == null }
          <em>{$LANG.no_billers}</em>
        {else}
          <select name="biller_id">
            {foreach from=$billers item=biller}
            <option {if $biller.id == $invoice.biller_id} selected {/if}
                    value="{$biller.id|htmlsafe}">{$biller.name|htmlsafe}</option>
            {/foreach}
          </select>
        {/if}
        </td>
      </tr>
      <tr>
        <th>{$LANG.customer}</th>
        <td>
        {if $customers == null}
          <em>{$LANG.no_customers}</em>
        {else}
          <select name="customer_id">
            {foreach from=$customers item=customer}
            <option {if $customer.id == $invoice.customer_id} selected {/if}
                    value="{$customer.id|htmlsafe}">{$customer.name|htmlsafe}</option>
            {/foreach}
          </select>
        {/if}
        </td>
      </tr>
      {* TODO: implement status
      <tr>
        <th>Invoice Status</th>
        <td>
          <select name="status_id">
            <option value="0">New</option>
            <option {if $invoice.status_id == 1} selected{/if} value="1">Sent</option>
            <option {if $invoice.status_id == 2} selected{/if} value="1">Paid</option>
          </select>
        </td>
      </tr>
      *}
    </table>
    {if $invoice.type_id == 1 }
    <table id="itemtable" class="si_invoice_items">
      <tr>
        <td class='si_invoice_notes' colspan="2">
          <h5>{$LANG.description}</h5>
          <textarea class="editor" name="description0" rows="10" cols="70"
                    style="overflow:scroll;">{$invoiceItems.0.description|outhtml}</textarea>
        </td>
      </tr>
    </table>
    <table class="si_invoice_bot">
      <tr>
        <th>{$LANG.gross_total}</th>
        <td>
          <input type="text" name="unit_price0"
                 value="{$invoiceItems.0.unit_price|siLocal_number_trim}" size="10" />
          <input type="hidden" name="quantity0" value="1" />
          <input type="hidden" name="id0" value="{$invoiceItems.0.id|htmlsafe}" />
          <input type="hidden" name="products0" value="{$invoiceItems.0.product_id|htmlsafe}" />
        </td>
      </tr>
      <tr>
        <th>{$LANG.tax}</th>
        <td>
          <table class="si_invoice_taxes">
            <tr>
            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
              <td>
                <select id="tax_id[0][{$smarty.section.tax.index|htmlsafe}]"
                        name="tax_id[0][{$smarty.section.tax.index|htmlsafe}]">
                  <option value=""></option>
                  {assign var="index" value=$smarty.section.tax.index}
                  {foreach from=$taxes item=tax}
                  <option {if $tax.tax_id === $invoiceItems.0.tax.$index} selected {/if}
                          value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
                  {/foreach}
                </select>
              </td>
            {/section}
            </tr>
          </table>
        </td>
      </tr>
      {$customFields.1}
      {$customFields.2}
      {$customFields.3}
      {$customFields.4}
      {* {showCustomFields categorieId="4" itemId=$smarty.get.invoice} *}
      {* This is intentionally not the terminous of the table *}
    {/if}
    {if $invoice.type_id == 2 || $invoice.type_id == 3 }
    <table id="itemtable" class="si_invoice_items">
      <thead>
        <tr>
          <td></td>
          <td>{$LANG.quantity_short}</td>
          <td>{$LANG.description}</td>
          {section name=tax_header loop=$defaults.tax_per_line_item }
          <td>{$LANG.tax}{if $defaults.tax_per_line_item > 1} {$smarty.section.tax_header.index+1|htmlsafe}{/if}</td>
          {/section}
          <td>{$LANG.unit_price}</td>
        </tr>
      </thead>
      {foreach key=line from=$invoiceItems item=invoiceItem name=line_item_number}
      <tbody class="line_item" id="row{$line|htmlsafe}">
        <tr class="tr_{cycle name="rows" values="A,B"}">
          <input type="hidden" id="delete{$line|htmlsafe}" name="delete{$line|htmlsafe}" size="3" />
          <input type="hidden" name="line_item{$line|htmlsafe}" id="line_item{$line|htmlsafe}"
                 value="{$invoiceItem.id|htmlsafe}" />
          <td>
            <a id="trash_link_edit{$line|htmlsafe}" class="trash_link_edit"
               title="{$LANG.delete_line_item}" href="#" style="display:inline;"
               rel="{$line|htmlsafe}">
              <img id="delete_image{$line|htmlsafe}"
                   src="images/common/{if $line == "0"}blank.gif{else}delete_item.png{/if}" alt="" />
            </a>
          </td>
          <td>
            <input class="si_right" type="text" name='quantity{$line|htmlsafe}'
                   id='quantity{$line|htmlsafe}'
                   value='{$invoiceItem.quantity|siLocal_number_trim}' size="10" />
          </td>
          <td>
          {if $products == null }
            <em>{$LANG.no_products}</em>
          {else}
            {* onchange="invoice_product_change_price($(this).val(), {$line|htmlsafe},
                                                      jQuery('#quantity{$line|htmlsafe}').val() );" *}
            <select name="products{$line|htmlsafe}" id="products{$line|htmlsafe}"
                    rel="{$line|htmlsafe}" class="product_change">
            {foreach from=$products item=product}
              <option {if $product.id == $invoiceItem.product_id} selected {/if}
                      value="{$product.id|htmlsafe}">{$product.description|htmlsafe}</option>
            {/foreach}
            </select>
          {/if}
          </td>
          {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
          <td>
            <select id="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                    name="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]">
              <option value=""></option>
              {assign var="index" value=$smarty.section.tax.index}
              {foreach from=$taxes item=tax}
              <option {if $tax.tax_id === $invoiceItem.tax.$index} selected {/if}
                      value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
              {/foreach}
            </select>
          </td>
          {/section}
          <td>
            <input class="si_right" id="unit_price{$line|htmlsafe}"
                   name="unit_price{$line|htmlsafe}" size="7"
                   value="{$invoiceItem.unit_price|siLocal_number_trim}" />
          </td>
        </tr>
        {$invoiceItem.html}
        <tr class="details si_hide">
          <td></td>
          <td colspan="4">
            <textarea class="detail" name="description{$line|htmlsafe}" style="overflow:scroll;"
                      id="description{$line|htmlsafe}" rows="3" cols="70">{$invoiceItem.description|outhtml}</textarea>
          </td>
        </tr>
      </tbody>
      {/foreach}
    </table>
    <div class="si_toolbar si_toolbar_inform">
      {* onclick="add_line_item();" *}
      <a href="#" class="add_line_item">
        <img src="images/common/add.png" alt=""/>{$LANG.add_new_row}</a>
      <a href='#' class="show-details" onclick="javascript: $('.details').show();$('.show-details').hide();">
        <img src="images/common/page_white_add.png"
             title="{$LANG.show_details}" alt="" />{$LANG.show_details}</a>
      <a href='#' class="details" onclick="javascript: $('.details').hide();$('.show-details').show();" style="display:none">
        <img src="images/common/page_white_delete.png"
             title="{$LANG.hide_details}" alt="" />{$LANG.hide_details}</a>
    </div>
    <table class="si_invoice_bot">
      {$customFields.1}
      {$customFields.2}
      {$customFields.3}
      {$customFields.4}
      {* {showCustomFields categorieId="4" itemId=$smarty.get.invoice} *}
      <tr>
        <td class='si_invoice_notes' colspan="2">
          <h5>{$LANG.notes}</h5>
          <textarea class="editor" name="note" rows="10" cols="70"
                    style="overflow:scroll;">{$invoice.note|outhtml}</textarea>
        </td>
      </tr>
      {* This is intentionally not the terminous of the table *}
    {/if}
      <tr>
        <th>{$LANG.inv_pref}</th>
        <td>
        {if $preferences == null }
          <em>{$LANG.no_preferences}</em>
        {else}
          <select name="preference_id">
          {foreach from=$preferences item=preference}
            <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                    value="{$preference.pref_id|htmlsafe}">{$preference.pref_description|htmlsafe}</option>
          {/foreach}
          </select>
        {/if}
        </td>
      </tr>
    {* This is the end of the non-terminated tables in the IF statements *}
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
        <img class="button_img" src="images/common/tick.png" alt="" />{$LANG.save}
      </button>
      <a href="index.php?module=invoices&amp;view=manage" class="negative">
        <img src="images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
  </div>
  <div>
    {if $invoice.id == null}
    <input type="hidden" name="action" value="insert" />
    {else}
    <input type="hidden" name="id" value="{$invoice.id|htmlsafe}" />
    <input type="hidden" name="action" value="edit" />
    {/if}
    {if $invoice.type_id == 1 }
    <input id="quantity0" type="hidden" size="10" value="1.00" name="quantity0"/>
    <input id="line_item0" type="hidden" value="{$invoiceItems.0.id|htmlsafe}" name="line_item0"/>
    {/if}
    <input type="hidden" name="type" value="{$invoice.type_id|htmlsafe}" />
    <input type="hidden" name="op" value="insert_preference" />
    <input type="hidden" id="max_items" name="max_items" value="{$lines|htmlsafe}" />
  </div>
</form>
