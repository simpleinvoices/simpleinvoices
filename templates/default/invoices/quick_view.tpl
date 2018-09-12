{*
 * Script: quick_view.tpl
 * Quick view of invoice template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *   2008-01-03
 *
 * License:
 *   GPL v2 or above
 *
 * Website:
 *   https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
  <a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
     href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print">
    <img src='images/common/printer.png' class='action' />&nbsp;{$LANG.print_preview}
  </a>
  <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
     href="index.php?module=invoices&amp;view=details&amp;id={$invoice.id|urlencode}&amp;action=view">
    <img src='images/common/edit.png' class='action' />&nbsp;{$LANG.edit}
  </a>
  <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
     href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice">
    <img src='images/common/money_dollar.png' class='action' />&nbsp;{$LANG.process_payment}
  </a>
  {if $eway_pre_check == 'true'}
    <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
       href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}">
      <img src='images/common/money_dollar.png' class='action' />&nbsp;{$LANG.process_payment_via_eway}
    </a>
  {/if}
  <!-- EXPORT TO PDF -->
  <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe} {$LANG.export_pdf_tooltip}"
     href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf">
    <img src='images/common/page_white_acrobat.png' class='action' />&nbsp;{$LANG.export_pdf}
  </a>
  <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet|htmlsafe} {$LANG.format_tooltip}"
     href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}">
    <img src='images/common/page_white_excel.png' class='action' />&nbsp;{$LANG.export_as}.{$spreadsheet|htmlsafe}
  </a>
  <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording} {$invoice.index_id|htmlsafe} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor|htmlsafe} {$LANG.format_tooltip}"
     href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}">
    <img src='images/common/page_white_word.png' class='action' />&nbsp;{$LANG.export_as}.{$wordprocessor|htmlsafe}
  </a>
  <a title="{$LANG.email} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
     href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}">
    <img src='images/common/mail-message-new.png' class='action' />&nbsp;{$LANG.email}
  </a>
  {if $defaults.delete == '1'}
    <a title="{$LANG.delete} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
       href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlencode}">
      <img src='images/common/delete.png' class='action' />&nbsp;{$LANG.delete}
    </a>
  {/if}
</div>
<!--Actions heading - start-->
<!-- #PDF end -->
<table class='si_invoice_view'>
  <!-- Invoice Summary section -->
  <tr class="tr_head">
    <th class="">{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.number_short}:</th>
    <td colspan="4">{$invoice.index_id|htmlsafe}</td>
    <td class="si_switch">
      <a href='#' class="show-summary" onclick="$('.summary').show();$('.show-summary').hide();">
        <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
      </a>
      <a href='#' class="summary" onclick="$('.summary').hide();$('.show-summary').show();">
        <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
      </a>
    </td>
  </tr>
  <tr class="summary">
    <th class="">{$LANG.date_upper}:</th>
    <td colspan="5">{$invoice.date|htmlsafe}</td>
  </tr>
  {$customFields.1}
  {$customFields.2}
  {$customFields.3}
  {$customFields.4}
  <!-- Biller section -->
  <tr class="tr_head">
    <th>{$LANG.biller}:</th>
    <td colspan="4">{$biller.name|htmlsafe}</td>
    <td class="si_switch">
      <a href='#' class="show-biller" onclick="$('.biller').show();$('.show-biller').hide();">
        <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
      </a>
      <a href='#' class="biller" onclick="$('.biller').hide();$('.show-biller').show();">
        <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
      </a>
    </td>
  </tr>
  <tr class="biller">
    <th>{$LANG.street}:</th>
    <td colspan="5">{$biller.street_address|htmlsafe}</td>
  </tr>
  <tr class="biller">
    <th>{$LANG.street2}:</th>
    <td colspan="5">{$biller.street_address2|htmlsafe}</td>
  </tr>
  <tr class="biller">
    <th>{$LANG.city}:</th>
    <td colspan="3">{$biller.city|htmlsafe}</td>
    <th>{$LANG.phone_short}:</th>
    <td>{$biller.phone|htmlsafe}</td>
  </tr>
  <tr class="biller">
    <th>{$LANG.state}, {$LANG.zip}:</th>
    <td colspan="3">{$biller.state|htmlsafe},&nbsp;{$biller.zip_code|htmlsafe}</td>
    <th>{$LANG.mobile_short}:</th>
    <td>{$biller.mobile_phone|htmlsafe}</td>
  </tr>
  <tr class="biller">
    <th>{$LANG.country}:</th>
    <td colspan="3">{$biller.country|htmlsafe}</td>
    <th>{$LANG.fax}:</th>
    <td>{$biller.fax|htmlsafe}</td>
  </tr>
  <tr class="biller">
    <th>{$LANG.email}:</th>
    <td colspan="5">{$biller.email|htmlsafe}</td>
  </tr>
  {if !empty($customFieldLabels.biller_cf1)}
    <tr class="biller">
      <th>{$customFieldLabels.biller_cf1|htmlsafe}:</th>
      <td colspan="5">{$biller.custom_field1|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.biller_cf2)}
    <tr class="biller">
      <th>{$customFieldLabels.biller_cf2|htmlsafe}:</th>
      <td colspan="5">{$biller.custom_field2|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.biller_cf3)}
    <tr class="biller">
      <th>{$customFieldLabels.biller_cf3|htmlsafe}:</th>
      <td colspan="5">{$biller.custom_field3|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.biller_cf4)}
    <tr class="biller">
      <th>{$customFieldLabels.biller_cf4|htmlsafe}:</th>
      <td colspan="5">{$biller.custom_field4|htmlsafe}</td>
    </tr>
  {/if}
  {* {showCustomFields categorieId="1" itemId=$biller.id } *}
  <!-- Customer section -->
  <tr class="tr_head">
    <th>{$LANG.customer}:</th>
    <td colspan="4">{$customer.name|htmlsafe}</td>
    <td class="si_switch">
      <a href='#' class="show-customer"
         {literal}onclick="$('.customer').show(); $('.show-customer').hide(); {/literal}">
        <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
      </a>
      <a href='#' class="customer"
         {literal}onclick="$('.customer').hide(); $('.show-customer').show(); {/literal}">
        <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
      </a>
    </td>
  </tr>
  <tr class="customer">
    <th>{$LANG.attention_short}:</th>
    <td colspan="5" align="left">{$customer.attention|htmlsafe},</td>
  </tr>
  <tr class="customer">
    <th>{$LANG.street}:</th>
    <td colspan="5" align="left">{$customer.street_address|htmlsafe}</td>
  </tr>
  <tr class="customer">
    <th>{$LANG.street2}:</th>
    <td colspan="5" align="left">{$customer.street_address2|htmlsafe}</td>
  </tr>
  <tr class="customer">
    <th>{$LANG.city}:</th>
    <td colspan="3">{$customer.city|htmlsafe}</td>
    <th>{$LANG.phone_short}:</th>
    <td>{$customer.phone|htmlsafe}</td>
  </tr>
  <tr class="customer">
    <th>{$LANG.state}, {$LANG.zip}:</th>
    <td colspan="3">{$customer.state|htmlsafe},&nbsp;{$customer.zip_code|htmlsafe}</td>
    <th>{$LANG.mobile_short}:</th>
    <td>{$customer.mobile_phone|htmlsafe}</td>
  </tr>
  <tr class="customer">
    <th>{$LANG.country}:</th>
    <td colspan="3">{$customer.country|htmlsafe}</td>
    <th>{$LANG.fax}:</th>
    <td>{$customer.fax|htmlsafe}</td>
  </tr>
  <tr class="customer">
    <th>{$LANG.email}:</th>
    <td colspan="5">{$customer.email|htmlsafe}</td>
  </tr>
  {if !empty($customFieldLabels.customer_cf1)}
    <tr class="customer">
      <th>{$customFieldLabels.customer_cf1}:</th>
      <td colspan="5">{$customer.custom_field1|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.customer_cf2)}
    <tr class="customer">
      <th>{$customFieldLabels.customer_cf2}:</th>
      <td colspan="5">{$customer.custom_field2|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.customer_cf3)}
    <tr class="customer">
      <th>{$customFieldLabels.customer_cf3}:</th>
      <td colspan="5">{$customer.custom_field3|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.customer_cf4)}
    <tr class="customer">
      <th>{$customFieldLabels.customer_cf4}:</th>
      <td colspan="5">{$customer.custom_field4|htmlsafe}</td>
    </tr>
  {/if}
  {* {showCustomFields categorieId="2" itemId=$customer.id } *}
  {if $invoice.type_id == 1 }
    <tr class="tr_head">
      <th colspan="6">{$LANG.description}</th>
    </tr>
    <tr>
      <td colspan="6">{$invoiceItems.0.description|outhtml}</td>
    </tr>
  {/if}
  {if $invoice.type_id == 2 || $invoice.type_id == 3}
    <tr class="tr_head">
      <th colspan=5></th>
      <td class="si_switch">
      {if $invoice.type_id == 2 }
        <a href='#' class="show-itemised" onclick="$('.itemised').show();$('.show-itemised').hide();">
          <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
        </a>
        <a href='#' class="itemised" onclick="$('.itemised').hide();$('.show-itemised').show();">
          <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
        </a>
      {/if}
      {if $invoice.type_id == 3 }
        <a href='#' class="show-consulting" onclick="$('.consulting').show();$('.show-consulting').hide();">
          <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
        </a>
        <a href='#' class="consulting" onclick="$('.consulting').hide();$('.show-consulting').show();">
          <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
        </a>
      {/if}
      </td>
    </tr>
    <tr>
      <td colspan="6">
        <table class="si_invoice_view_items">
          <tr class="tr_head_items">
            <th class="si_quantity">{$LANG.quantity_short}</th>
            <th colspan="2">{$LANG.item}</th>
            <th class="si_right">{$LANG.unit_cost}</th>
            <th class="si_right">{$LANG.price}</th>
          </tr>
          {foreach from=$invoiceItems item=invoiceItem }
            {if $invoice.type_id == 2 }
              <tr>
                <td class="si_quantity">{$invoiceItem.quantity|siLocal_number_trim}</td>
                <td class="td_product" colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
                <td class="si_right">{$preference.pref_currency_sign}&nbsp;{$invoiceItem.unit_price|siLocal_number}</td>
                <td class="si_right">{$preference.pref_currency_sign}&nbsp;{$invoiceItem.gross_total|siLocal_number}</td>
              </tr>
              {if $invoiceItem.attribute != null}
                <tr class="si_product_attribute">
                  <td></td>
                  <td>
                    <table>
                      <tr class="si_product_attribute">
                      {foreach from=$invoiceItem.attribute_json key=k item=v}
                        <td class="si_product_attribute">
                        {if $v.type == 'decimal'}
                          {$v.name}:&nbsp;{$preference.pref_currency_sign}{$v.value|siLocal_number} ;
                        {else}
                          {$v.name}:&nbsp;{$v.value} ;
                        {/if}
                        </td>
                      {/foreach}
                      </tr>
                    </table>
                  </td>
                </tr>
              {/if}
              {if $invoiceItem.description != null}
                <tr class="show-itemised tr_desc">
                  <td></td>
                  <td colspan="5" class="">
                    {$invoiceItem.description|truncate:80:"...":true|htmlsafe}
                  </td>
                </tr>
                <tr class="itemised tr_desc">
                  <td></td>
                  <td colspan="5" class="">{$invoiceItem.description|htmlsafe}</td>
                </tr>
              {/if}
              <tr class="itemised tr_custom">
                <td></td>
                <td colspan="5">
                  <table class="si_invoice_view_custom_items">
                    <tr>
                    {if !empty($customFieldLabels.product_cf1)}
                      <th>{$customFieldLabels.product_cf1|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field1|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf2)}
                      <th>{$customFieldLabels.product_cf2|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field2|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    </tr>
                    <tr>
                    {if !empty($customFieldLabels.product_cf3)}
                      <th>{$customFieldLabels.product_cf3|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field3|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf4)}
                      <th>{$customFieldLabels.product_cf4|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field4|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    </tr>
                  </table>
                </td>
              </tr>
              {*TODO: CustomFields are normaly stored for a product.
                      Here it needs to be added to the invoices Item -> categorie 5 *}
              {* {showCustomFields categorieId="3" itemId=$invoiceItem.productId} *}
            {/if}
            {if $invoice.type_id == 3 }
              <tr>
                <td class="si_quantity">{$invoiceItem.quantity|siLocal_number}</td>
                <td class="td_product" colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
                <td class="si_right">{$preference.pref_currency_sign}&nbsp;{$invoiceItem.unit_price|siLocal_number}</td>
                <td class="si_right">{$preference.pref_currency_sign}&nbsp;{$invoiceItem.gross_total|siLocal_number}</td>
              </tr>
              <tr class="consulting tr_custom">
                <td></td>
                <td colspan="5">
                  <table class="si_invoice_view_custom_items">
                    <tr>
                    {if !empty($customFieldLabels.product_cf1)}
                      <th>{$customFieldLabels.product_cf1|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field1|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf2)}
                      <th>{$customFieldLabels.product_cf2|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field2|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    </tr>
                    <tr>
                    {if !empty($customFieldLabels.product_cf3)}
                      <th>{$customFieldLabels.product_cf3|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field3|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf4)}
                      <th>{$customFieldLabels.product_cf4|htmlsafe}:</th>
                      <td>{$invoiceItem.product.custom_field4|htmlsafe}</td>
                    {else}
                      <td colspan="2"></td>
                    {/if}
                    </tr>
                  </table>
                </td>
              </tr>
            {/if}
          {/foreach}
        <!-- we are still in the itemized or consulting loop -->
        </table>
      </td>
    </tr>
    {if ($invoice.note != null) }
      <tr class="tr_head">
        <th>{$LANG.notes}:</th>
        <td colspan="4"></td>
        <td class="si_switch">
        {if ($invoice.note|count_characters:true > 25)}
          <a href='#' class="show-notes"
             onclick="$('.notes').show();$('.si_notes_hide').show();$('.show-notes').hide();">
            <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
          </a>
          <a href='#' class="notes si_hide"
             onclick="$('.notes').hide();$('.si_notes_hide').hide();$('.show-notes').show();">
            <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
          </a>
        {/if}
        </td>
      </tr>
      <!-- if hide detail click - the stripped note will be displayed -->
      <tr class="show-notes tr_notes">
        <td colspan="6">{$invoice.note|truncate:25:"...":true|outhtml}</td>
      </tr>
      <!-- if show detail click - the full note will be displayed -->
      <tr class="notes tr_notes si_notes_hide">
        <td colspan="6">{$invoice.note|outhtml}</td>
      </tr>
    {/if}
    {* end itemized invoice *}
  {/if}
  {* tax section - start --------------------- *}
  {if $invoice_number_of_taxes > 0}
    <tr class="tr_tax">
      <td colspan="4"></td>
      <th>{$LANG.sub_total}</th>
      <td class="si_right" {if $invoice_number_of_taxes > 1}style="text_decoration:underline;"{/if}>
        {$preference.pref_currency_sign}{$invoice.gross|siLocal_number}
      </td>
    </tr>
  {/if}
  {if $invoice_number_of_taxes > 1 } {/if}
  {foreach from=$invoice.tax_grouped item=taxg}
    {if $taxg.tax_amount != 0}
      <tr class="tr_tax">
        <td colspan="4"></td>
        <th>{$taxg.tax_name|htmlsafe}</th>
        <td class="si_right">{$preference.pref_currency_sign}{$taxg.tax_amount|siLocal_number}</td>
      </tr>
    {/if}
  {/foreach}
  {if $invoice_number_of_taxes > 1}
    <tr class="tr_tax">
      <td colspan="4"></td>
      <th>{$LANG.tax_total}</th>
      <td class="si_right" style="text_decoration:underline;">
        {$preference.pref_currency_sign}{$invoice.total_tax|siLocal_number}
      </td>
    </tr>
  {/if}
  {if $invoice_number_of_taxes > 1} {/if}
  <tr class="tr_total">
    <td colspan="4"></td>
    <th>{$preference.pref_inv_wording|htmlsafe} {$LANG.amount}</th>
    <td class="si_right">{$preference.pref_currency_sign}{$invoice.total|siLocal_number}</td>
  </tr>
  {* tax section - end *}
</table>
<div class="si_center">
  <div class="si_invoice_account">
    <h4>{$LANG.financial_status}</h4>
    <div class="si_invoice_account1">
      <h5>{$preference.pref_inv_wording|htmlsafe}&nbsp;{$invoice.index_id|htmlsafe}</h5>
      <table>
        <tr>
          <th>{$LANG.total}</th>
          <th>
            <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">
              {$LANG.paid}
            </a>
          </th>
          <th>{$LANG.owing}</th>
          <th>{$LANG.age}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_age"
               title="{$LANG.age}"><img
               src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
        </tr>
        <tr>
          <td>{$preference.pref_currency_sign}{$invoice.total|siLocal_number}</td>
          <td>{$preference.pref_currency_sign}{$invoice.paid|siLocal_number}</td>
          <td>{$preference.pref_currency_sign}{$invoice.owing|siLocal_number}</td>
          <td>{$invoice_age|htmlsafe}</td>
        </tr>
      </table>
    </div>
    <div class="si_invoice_account2">
      <h5>
        <a href="index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=view">
          {$LANG.customer_account}
        </a>
      </h5>
      <table>
        <tr>
          <th>{$LANG.total}</th>
          <th>
            <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
              {$LANG.paid}
            </a>
          </th>
          <th>{$LANG.owing}</th>
        </tr>
        <tr>
          <td>{$preference.pref_currency_sign}{$customerAccount.total|siLocal_number}</td>
          <td>{$preference.pref_currency_sign}{$customerAccount.paid|siLocal_number}</td>
          <td>{$preference.pref_currency_sign}{$customerAccount.owing|siLocal_number}</td>
        </tr>
      </table>
    </div>
  </div>
</div>
