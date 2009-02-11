<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional/EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>{$preference.pref_inv_wording} {$LANG.number_short}: {$invoice.id}</title>
   <link href="{$css}" rel="stylesheet" media="screen" type="text/css" />
   <link href="{$siUrl}/templates/invoices/wiwo/pstyle.css" rel="stylesheet" media="print" type="text/css" />
  </head>

 <body>
  <div id="envelope">
  <img id="bol" src="{$logo}" />
  <div id="heading">  
   <table id="kop">
    <tr>
     <td id="logoblok">
      <ul>
       <li id="biller">{$biller.name}</li>
       <li class="webadres">{$biller.custom_field1}</li> 
       <li class="webadres">{$LANG.email}: {$biller.email}</li> 
      </ul>
     </td>
     <td id="naw">
      <ul id="adres">
       {if $biller.street_address != null}
       <li>{$biller.street_address}</li>
       {/if}
       {if $biller.street_address2 != null}
       <li>{$biller.street_address2}</li>
       {/if}
       <li>{$biller.zip_code}  {$biller.city}</li>
        {if $biller.country != null }
       <li>{$biller.country}</li>
       {/if}
      </ul>
      <ul id="phone">
       <li>&nbsp;
       {if $biller.phone != null} {$LANG.phone}: {/if}
       {$biller.phone}</li>
       <li>&nbsp;
       {if $biller.fax != null} {$LANG.fax}: {/if}
       {$biller.fax}</li>
      </ul>
     </td>
    </tr>
   </table>
  </div>
  </div>
  <ul id="adresblok">
   <li>{$customer.name}</li>
   {if $customer.attention != null }
   <li>{$LANG.attention_short} {$customer.attention}</li>
   {/if}
   {if $customer.street_address != null }
   <li>{$customer.street_address}</li>
   {/if}
   {if $customer.street_address2 != null }
   <li>{$customer.street_address2}</li>
   {/if}
   <li>{$customer.zip_code} {$customer.city}</li>
   {if $customer.country != null}
   <li>{$customer.country}</li>
   {/if}
  </ul>
  <table id="faktuurinfo">
   <tr><td class="left">{$preference.pref_inv_wording}{$LANG.date}</td><td>: {$invoice.date}</td></tr>
   <tr><td class="left">{$preference.pref_inv_wording}{$LANG.number_short}</td><td>: D{$invoice.id}</td></tr>
   <tr><td class="left">Betaalwijze</td><td>: {$preference.pref_inv_payment_method}</td></tr>
   {if $customer.custom_field1 != null }
   <tr><td class="left">{$customFieldLabels.customer_cf1}</td><td>: {$customer.custom_field1}</td></tr>
   {/if}
  </table>
  <div id="info">
   <b>Betreft:</b><hr />
   {$invoice.note}
  </div>
  <table id="fregels">
   <tr>
    <th class="desc">{$LANG.description}</th>
    <th class="aantal">{$LANG.quantity_short}</th>
    <th class="geld">{$LANG.unit_price}</th>
    <th class="geld">{$LANG.tax}</th>
    <th class="geld">{$LANG.gross_total}</th>
   </tr>
   {foreach from=$invoiceItems item=invoiceItem}
   <tr>
   <td class="desc">{$invoiceItem.product.description}</td>
   <td class="aantal">{$invoiceItem.quantity|number_format:2} {$invoiceItem.product.custom_field1}</td>
   <td class="geld">{$preference.pref_currency_sign} {$invoiceItem.unit_price|number_format:2}</td>
   <td class="geld">{$preference.pref_currency_sign} {$invoiceItem.tax_amount|number_format:2}</td>
   <td class="geld">{$preference.pref_currency_sign} {$invoiceItem.gross_total|number_format:2}</td>
   </tr>
   {/foreach}
  </table>
  <div id="totalen">
   {php}   global $invoice; $this->assign('invoice_gross_total', $invoice[total] - $invoice[total_tax]); {/php}
   <table> 
    <tr><td class="left">{$LANG.gross_total}</td>
        <td class="geld">{$preference.pref_currency_sign} {$invoice_gross_total|number_format:2}</td></tr>
    <tr><td class="left">BTW 19%</td>
        <td class="geld">{$preference.pref_currency_sign} {$invoice.total_tax|number_format:2}</td></tr>
    <tr><td class="left">Totaal te betalen</td>
        <td class="totaal">{$preference.pref_currency_sign} {$invoice.total|number_format:2}</td></tr>
   </table>
  </div>
  <div id="foot">
   <div id="conditie">
   <b>{$preference.pref_inv_detail_heading}</b><br />
    {$preference.pref_inv_detail_line} {$preference.pref_inv_payment_method} {$preference.pref_inv_payment_line1_name} {$preference.pref_inv_payment_line1_value} {$preference.pref_inv_payment_line2_name} {$preference.pref_inv_payment_line2_value}
    {if $customer.custom_field2 != null } {$customer.custom_field2} {/if}
   </div>
   {$biller.footer}
  </div>
 </body>
</html>
