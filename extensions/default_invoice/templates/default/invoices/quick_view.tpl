{*
 * Script: quick_view.tpl
 *      Quick view of invoice template
 *
 * Authors:
 *     Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *     Modified for 'default_invoice by Marcel van Dorp. Version 20090208
 *     'Customer' section has an extra button to set the default invoice
 *	   Modified by Rich Rowley to remove Invoice Summary section and always show
 *         invoice number and date. Also to eliminate notes section and always show
 *         notes when present.
 *
 * Last edited:
 *      2017-12-25
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *    http://www.simpleinvoices.org
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
  <tr class='details_screen'>
    <th class='details_screen'><b>{$preference.pref_inv_wording} {$LANG.number_short}:</b></th>
    <td class='details_screen'>{$invoice.index_id|htmlsafe}</td>
    <th class='details_screen' colspan="2"><b>{$preference.pref_inv_wording} {$LANG.date}:</b></th>
    <td class='details_screen' colspan="2">{$invoice.date|htmlsafe}</td>
  </tr>
  {$customFields.1}
  {$customFields.2}
  {$customFields.3}
  {$customFields.4}
  <tr>
    <td><br /></td>
  </tr>
  <!-- Biller section -->
  <tr class='details_screen'>
    <th class='details_screen'><b>{$LANG.biller}:</b></th>
    <td class='details_screen' colspan="3">{$biller.name|htmlsafe}</td>
    <td colspan="2" class='details_screen align_right'>
      <a href='#' class="show-biller" onclick="$('.biller').show();$('.show-biller').hide();">
        <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}" />
      </a>
      <a href='#' class="biller" onclick="$('.biller').hide();$('.show-biller').show();">
        <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}" />
      </a>
    </td>
  </tr>
  <tr class='details_screen biller'>
    <th class='details_screen'>{$LANG.street}:</th>
    <td class='details_screen' colspan="5">{$biller.street_address|htmlsafe}</td>
  </tr>
  <tr class='details_screen biller'>
    <th class='details_screen'>{$LANG.street2}:</th>
    <td class='details_screen' colspan="5">{$biller.street_address2|htmlsafe}</td>
  </tr>
  <tr class='details_screen biller'>
    <th class='details_screen'>{$LANG.city}:</th>
    <td class='details_screen' colspan="2">{$biller.city|htmlsafe}</td>
    <th class='details_screen'>{$LANG.phone_short}:</th>
    <td class='details_screen' colspan="2">{$biller.phone|htmlsafe}</td>
  </tr>
  <tr class='details_screen biller'>
    <th class='details_screen'>{$LANG.state}, {$LANG.zip}:</th>
    <td class='details_screen' colspan="2">{$biller.state|htmlsafe},&nbsp;{$biller.zip_code|htmlsafe}</td>
    <th class='details_screen'>{$LANG.mobile_short}:</th>
    <td class='details_screen' colspan="2">{$biller.mobile_phone|htmlsafe}</td>
  </tr>
  <tr class='details_screen biller'>
    <th class='details_screen'>{$LANG.country}:</th>
    <td class='details_screen' colspan="2">{$biller.country|htmlsafe}</td>
    <th class='details_screen'>{$LANG.fax}:</th>
    <td class='details_screen' colspan="2">{$biller.fax|htmlsafe}</td>
  </tr>
  <tr class='details_screen biller'>
    <th class='details_screen'>{$LANG.email}:</th>
    <td class='details_screen' colspan="5">{$biller.email|htmlsafe}</td>
  </tr>
  {if !empty($customFieldLabels.biller_cf1)}
    <tr class='details_screen biller'>
      <th class='details_screen'>{$customFieldLabels.biller_cf1|htmlsafe}:</th>
      <td class='details_screen' colspan="5">{$biller.custom_field1|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.biller_cf2)}
    <tr class='details_screen biller'>
      <th class='details_screen'>{$customFieldLabels.biller_cf2|htmlsafe}:</th>
      <td class='details_screen' colspan="5">{$biller.custom_field2|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.biller_cf3)}
    <tr class='details_screen biller'>
      <th class='details_screen'>{$customFieldLabels.biller_cf3|htmlsafe}:</th>
      <td class='details_screen' colspan="5">{$biller.custom_field3|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.biller_cf4)}
  <tr class='details_screen biller'>
    <th class='details_screen'>{$customFieldLabels.biller_cf4|htmlsafe}:</th>
    <td class='details_screen' colspan="5">{$biller.custom_field4|htmlsafe}</td>
  </tr>
  {/if}
  {* {showCustomFields categorieId="1" itemId=$biller.id } *}
  <tr>
    <td colspan="5"><br /></td>
  </tr>
  <!-- Customer section -->
  <tr class='details_screen'>
    <th class='details_screen'><b>{$LANG.customer}:</b></th>
    <td class='details_screen' colspan="3">{$customer.name|htmlsafe}</td>
    <td colspan="2" class='details_screen align_right'>
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
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.attention_short}:</th>
    <td class='details_screen' colspan="5" align="left">{$customer.attention|htmlsafe},</td>
  </tr>
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.street}:</th>
    <td class='details_screen' colspan="5" align="left">{$customer.street_address|htmlsafe}</td>
  </tr>
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.street2}:</th>
    <td class='details_screen' colspan="5" align="left">{$customer.street_address2|htmlsafe}</td>
  </tr>
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.city}:</th>
    <td class='details_screen' colspan="2">{$customer.city|htmlsafe}</td>
    <th class='details_screen'>{$LANG.phone_short}:</th>
    <td class='details_screen' colspan="2">{$customer.phone|htmlsafe}</td>
  </tr>
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.state}, {$LANG.zip}:</th>
    <td class='details_screen' colspan="2">{$customer.state|htmlsafe},&nbsp;{$customer.zip_code|htmlsafe}</td>
    <th class='details_screen'>Mobile:</th>
    <td class='details_screen' colspan="2">{$customer.mobile_phone|htmlsafe}</td>
  </tr>
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.country}:</th>
    <td class='details_screen' colspan="2">{$customer.country|htmlsafe}</td>
    <th class='details_screen'>{$LANG.fax}:</th>
    <td class='details_screen' colspan="2">{$customer.fax|htmlsafe}</td>
  </tr>
  <tr class='details_screen customer'>
    <th class='details_screen'>{$LANG.email}:</th>
    <td class='details_screen'colspan="5">{$customer.email|htmlsafe}</td>
  </tr>
  {if !empty($customFieldLabels.customer_cf1)}
    <tr class='details_screen customer'>
      <th class='details_screen'>{$customFieldLabels.customer_cf1}:</th>
      <td colspan="5" class='details_screen'>{$customer.custom_field1|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.customer_cf2)}
    <tr class='details_screen customer'>
      <th class='details_screen'>{$customFieldLabels.customer_cf2}:</th>
      <td colspan="5" class='details_screen'>{$customer.custom_field2|htmlsafe}</td>
    </tr>
  {/if}
  {if !empty($customFieldLabels.customer_cf3)}
    <tr class='details_screen customer'>
      <th class='details_screen'>{$customFieldLabels.customer_cf3}:</th>
      <td class='details_screen' colspan="5">{$customer.custom_field3|htmlsafe}</td>
    </tr>
  {/if}
  <tr class='details_screen customer'>
    {if !empty($customFieldLabels.customer_cf4)}
      <td class='details_screen'>{$customFieldLabels.customer_cf4}:</td>
      <td class='details_screen' colspan=4>{$customer.custom_field4}</td>
    {else}
      <td colspan="5"></td>
    {/if}
    <td class='details_screen align_right'>
      <a href="?module=invoices&view=usedefault&action=update_template&id={$invoice.id}&customer_id={$customer.id}">
        <img src="images/flexigrid/load.png" title='{$LANG.invoice} {$invoice.id} {$LANG.as_template} {$LANG.for} {$customer.name}' />
      </a>
    </td>
  </tr>
  {* {showCustomFields categorieId="2" itemId=$customer.id } *}
  {if $invoice.type_id == 1 }
    <tr>
      <th colspan="6"><br /></th>
    </tr>
    <tr>
      <th colspan="6"><b>{$LANG.description}</b></th>
    </tr>
    <tr>
      <td colspan="6">{$invoiceItems.0.description|unescape}</td>
    </tr>
    <tr>
      <td colspan="6"><br /></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td style="text-align:right"><b>{$LANG.gross_total}</b></td>
      <td style="text-align:right"><b>{$LANG.tax}</b></td>
      <td style="text-align:right"><b>{$LANG.total_uppercase}</b></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItems.0.gross_total|siLocal_number}</td>
      <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItems.0.tax_amount|siLocal_number}</td>
      <td style="text-align:right"><u>{$preference.pref_currency_sign|htmlsafe}{$invoiceItems.0.total|siLocal_number}</u></td>
    </tr>
    <tr>
      <td colspan="6"><br /><br /></td>
    </tr>
    <tr>
      <td colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
    </tr>
  {/if}
  {if $invoice.type_id == 2 || $invoice.type_id == 3  || $invoice.type_id == 4}
    <tr>
      <td colspan="6"><br /></td>
    </tr>
    <tr>
      <td colspan="6">
        <table width="100%">
          {if $invoice.type_id == 2 }
            <tr>
              <td colspan="6" class="details_screen align_right">
                <a href='#' class="show-itemised"
                   onclick="$('.itemised').show();$('.show-itemised').hide();">
                  <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/>
                </a>
                <a href='#' class="itemised"
                   onclick="$('.itemised').hide();$('.show-itemised').show();">
                  <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/>
                </a>
              </td>
            </tr>
            <tr>
              <td><b>{$LANG.quantity_short}</b></td>
              <td colspan="2"><b>{$LANG.item}</b></td>
              <td style="text-align:right"><b>{$LANG.unit_cost}</b></td>
              <td style="text-align:right"><b>{$LANG.price}</b></td>
            </tr>
          {/if}
          {if $invoice.type_id == 3 }
            <tr>
              <td colspan="6" class="details_screen align_right">
                <a href='#' class="show-consulting" onclick="$('.consulting').show();$('.show-consulting').hide();">
                  <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/>
                </a>
                <a href='#' class="consulting" onclick="$('.consulting').hide();$('.show-consulting').show();">
                  <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/>
                </a>
              </td>
            </tr>
            <tr>
              <td><b>{$LANG.quantity_short}</b></td>
              <td colspan="2"><b>{$LANG.item}</b></td>
              <td style="text-align:right"><b>{$LANG.unit_cost}</b></td>
              <td style="text-align:right"><b>{$LANG.price}</b></td>
            </tr>
          {/if}
          {foreach from=$invoiceItems item=invoiceItem }
            {if $invoice.type_id == 2 }
              <tr>
                <td>{$invoiceItem.quantity|siLocal_number_trim}</td>
                <td colspan="2">{$invoiceItem.product.description}</td>
                <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.unit_price|siLocal_number}</td>
                <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.gross_total|siLocal_number}</td>
              </tr>
              {if $invoiceItem.description != null}
                <tr class='show-itemised' >
                  <td></td>
                  <td colspan="5" class=''>
                    {$LANG.description}: {$invoiceItem.description|truncate:15:"...":true|htmlsafe}
                  </td>
                </tr>
                <tr class='itemised' >
                  <td colspan="6" class='details_screen'>
                    {$LANG.description}: {$invoiceItem.description|htmlsafe}
                  </td>
                </tr>
              {/if}
              <tr class='itemised'>
                <td colspan="6">
                  <table width="100%">
                    <tr>
                    {if !empty($customFieldLabels.product_cf1)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf1|htmlsafe}: {$invoiceItem.product.custom_field1|htmlsafe}
                      </td>
                    {else}
                      <td></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf2)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf2|htmlsafe}: {$invoiceItem.product.custom_field2|htmlsafe}
                      </td>
                    {/if}
                    </tr>
                    <tr>
                    {if !empty($customFieldLabels.product_cf3)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf3|htmlsafe}: {$invoiceItem.product.custom_field3|htmlsafe}
                      </td>
                    {else}
                      <td></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf4)}
                      <td width="50%" class='details_screen'>
                         {$customFieldLabels.product_cf4|htmlsafe}: {$invoiceItem.product.custom_field4|htmlsafe}
                      </td>
                    {/if}
                  </tr>
                </table>
              </td>
            </tr>
            {*TODO: CustomFields are normaly stored for a product. Here it needs to be added to the invoices Item->categorie 5 *}
            {* {showCustomFields categorieId="3" itemId=$invoiceItem.productId } *}
          {/if}
          {if $invoice.type_id == 3 }
            <tr>
              <td>{$invoiceItem.quantity|siLocal_number}</td>
              <td colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
              <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.unit_price|siLocal_number}</td>
              <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.gross_total|siLocal_number}</td>
            </tr>
            <tr  class='consulting' >
              <td colspan="6">
                <table width="100%">
                  <tr>
                    {if !empty($customFieldLabels.product_cf1)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf1|htmlsafe}: {$invoiceItem.product.custom_field1|htmlsafe}
                      </td>
                    {else}
                      <td></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf2)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf2|htmlsafe}: {$invoiceItem.product.custom_field2|htmlsafe}
                      </td>
                    {/if}
                  </tr>
                  <tr>
                    {if !empty($customFieldLabels.product_cf3)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf3|htmlsafe}: {$invoiceItem.product.custom_field3|htmlsafe}
                      </td>
                    {else}
                      <td></td>
                    {/if}
                    {if !empty($customFieldLabels.product_cf4)}
                      <td width="50%" class='details_screen'>
                        {$customFieldLabels.product_cf4|htmlsafe}: {$invoiceItem.product.custom_field4|htmlsafe}
                      </td>
                    {/if}
                  </tr>
                </table>
              </td>
              <!--    <td></td><td colspan="6" class='details_screen consulting'>{$prod_custom_field_label1}: {$product.custom_field1}, {$prod_custom_field_label2}: {$product.custom_field2}, {$prod_custom_field_label3}: {$product.custom_field3}, {$prod_custom_field_label4}: {$product.custom_field4}</td> -->
            </tr>
          {/if}
        {/foreach}
        <!-- we are still in the itemized or consulting loop -->
      </table>
    </td>
  </tr>
  <tr><td colspan="6">&nbsp;</td></tr>
  <tr class="details_screen">
    <th class="" style="text-align:left;"><b>{$LANG.notes}:</b></th>
    <td colspan="5" class="details_screen align_right"></td>
  </tr>
  <tr class='details_screen notes'>
    <td class='details_screen' colspan="6">{if ($invoice.note != null)}{$invoice.note|unescape}{/if}</td>
  </tr>
  <tr><td colspan="6">&nbsp;</td></tr>
  {foreach from=$invoice.tax_grouped item=taxg}
    {if $taxg.tax_amount != 0}
      <tr class='details_screen'>
        <td colspan="3"></td>
        <td colspan="2" class="align_right">{$taxg.tax_name}</td>
        <td colspan="1" class="align_right">{$preference.pref_currency_sign|htmlsafe}{$taxg.tax_amount|siLocal_number}</td>
      </tr>
    {/if}
  {/foreach}
  <tr class='details_screen'>
    <td colspan="3"></td>
    <td colspan="2" class="align_right"><b>{$LANG.tax_total}</b></td>
    <td colspan="1" class="align_right"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.total_tax|siLocal_number}</u></td>
  </tr>
  <tr class='details_screen'>
    <td colspan="3"></td>
    <td colspan="2" class="align_right"><b>{$preference.pref_inv_wording|htmlsafe} {$LANG.amount}</b></td>
    <td colspan="2" class="align_right"><span class="double_underline">{$preference.pref_currency_sign|htmlsafe}{$invoice.total|siLocal_number}</span></td>
  </tr>
  {*
  <tr>
    <td colspan="6"><br /><br /></td>
  </tr>
  <tr>
    <td colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
  </tr>
  *}
  {/if}
  {* {showCustomFields categorieId="4" itemId=$invoice.id } *}
  {*
  <tr>
    <td colspan="6"><i>{$preference.pref_inv_detail_line}</i></td>
  </tr>
  <tr>
    <td colspan="6">{$preference.pref_inv_payment_method}</td>
  </tr>
  <tr>
    <td>{$preference.pref_inv_payment_line1_name}</td>
    <td colspan="5">{$preference.pref_inv_payment_line1_value}</td>
  </tr>
  <tr>
    <td>{$preference.pref_inv_payment_line2_name}</td>
    <td colspan="5">{$preference.pref_inv_payment_line2_value}</td>
  </tr>
  *}
</table>
<br />
<br />
<table class="center">
  <tr class='details_screen'>
    <td class='details_screen' colspan="16">
      {$LANG.financial_status}
    </td>
  </tr>
  <tr class="account">
    <td class="account" colspan="8">{$preference.pref_inv_wording|htmlsafe} {$invoice.index_id}</td>
    <td width="5%"></td>
    <td class="columnleft" width="5%"></td>
    <td class="account" colspan="6">
      <a href='index.php?module=customers&view=details&id={$customer.id}&action=view'>{$LANG.customer_account}</a>
    </td>
  </tr>
  <tr>
    <td class="account">{$LANG.total}:</td>
    <td class="account">{$preference.pref_currency_sign|htmlsafe}{$invoice.total|siLocal_number}</td>
    <td class="account">
      <a href='index.php?module=payments&view=manage&id={$invoice.id}'>{$LANG.paid}:</a>
    </td>
    <td class="account">{$preference.pref_currency_sign|htmlsafe}{$invoice.paid|siLocal_number}</td>
    <td class="account">{$LANG.owing}:</td>
    <td class="account"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.owing|siLocal_number}</u></td>
    <td class="account">{$LANG.age}:</td>
    <td class="account" nowrap>{$invoice_age}
      <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_age" title="{$LANG.age}">
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td></td>
    <td class="columnleft"></td>
    <td class="account">{$LANG.total}:</td>
    <td class="account">{$preference.pref_currency_sign|htmlsafe}{$customerAccount.total|siLocal_number}</td>
    <td class="account">
      <a href='index.php?module=payments&view=manage&c_id={$customer.id}'>{$LANG.paid}:</a>
    </td>
    <td class="account">{$preference.pref_currency_sign|htmlsafe}{$customerAccount.paid|siLocal_number} </td>
    <td class="account">{$LANG.owing}:</td>
    <td class="account"><u>{$preference.pref_currency_sign|htmlsafe}{$customerAccount.owing|siLocal_number}</u></td>
  </tr>
</table>
<br />
