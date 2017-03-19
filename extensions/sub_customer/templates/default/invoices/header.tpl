{*
 * Script: header.tpl
 *   Header file for invoice template
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   http://www.simpleinvoices.org
 #$Id: header.tpl 3627 2011-05-09 01:00:32Z justin@kelly.org.au $
 *}
<br />
<span class="welcome">
    <a href="index.php?module=invoices&amp;view=itemised"><img class="action" src="images/common/edit.png"/>
        &nbsp;{$LANG.itemised_style}
    </a>
    &nbsp;&nbsp; 
    <a href="index.php?module=invoices&amp;view=total"><img class="action" src="images/common/page_white_edit.png"/>
        &nbsp;{$LANG.total_style}
    </a>
    &nbsp;&nbsp; 
    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_types" title="{$LANG.invoice_type}">
        <img class="action" src="{$help_image_path}help-small.png" alt="" />
    </a>
</span>
<br />
<br />
<br />
<input type="hidden" name="action" value="insert" />
<table class="center">
    <tr>
        <td>
            <table class="left">
                <tr>
                    <td class="details_screen">{$LANG.biller}</td>
                    <td>
                        {if $billers == null }
                            <p><em>{$LANG.no_billers}</em></p>
                        {else}
                            <select name="biller_id">
                                {foreach from=$billers item=biller}
                                    <option {if $biller.id == $defaults.biller} selected {/if}
                                            value="{$biller.id|htmlsafe}">
                                        {$biller.name|htmlsafe}
                                    </option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.customer}</td>
                    <td>
                        {if $customers == null }
                            <em>{$LANG.no_customers}</em>
                        {else}
                            <select name="customer_id" id="customer_id">
                                {foreach from=$customers item=customer}
                                    <option {if $customer.id == $defaults.customer} selected{/if}
                                            value="{$customer.id|htmlsafe}">
                                        {$customer.name|htmlsafe}
                                    </option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.sub_customer}</td>
                    <td>
                        {if $customers == null }
                            <em>{$LANG.no_customers}</em>
                        {else}
                            <select name="custom_field1" id="custom_field1">
                                {foreach from=$sub_customers item=customer}
                                    <option {if $customer.id == $defaultCustomerID}selected{/if}
                                            value="{$customer.id|htmlsafe}">
                                        {$customer.attention|htmlsafe}
                                    </option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.date_formatted}</td>
                    <td>
                        <input type="text" size="10" name="date" id="date1" size="10" name="date" id="date1" 
                               class="validate[required,custom[date],length[0,10]] date-picker" 
                               value="{if $smarty.get.date}{$smarty.get.date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
