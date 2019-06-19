<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="{$css|urlsafe}" media="all">
<title>{$preference.pref_inv_wording|htmlsafe} {$LANG.number_short}: {$invoice.index_id|htmlsafe}</title>
</head>
<body class="rec">
<br />
<div id="container">
	<div id="header">
	</div>

<table width="100%" align="center">
		<tr>
			<td colspan="5"><img src="{$logo|urlsafe}" border="0" hspace="0" align="left"></td>
		  <th align="right"><span class="font1">{$preference.pref_inv_heading|htmlsafe}</span>
            <br/>REF#{invoice_referencia categoryId=$invoice.category_id}-{invoice_number invoiceId=$invoice.index_id}
          <br/>{invoice_fecha invoiceId=$invoice.id}</th>
		</tr>
        <tr><td>&nbsp;</td></tr>
		<tr>
			<td colspan="6" class="tbl1st">&nbsp;</td>
  </tr>
	</table>
    
<div>
	<div id="general">
		<div class="customer">
     <span class="tbl1-bottom col1" ><b>{$LANG.customer}:</b> {$customer.name|htmlsafe}</span><br/>
    {$customer.attention|htmlsafe}<br/>
    Teléfono: {$customer.phone|htmlsafe}<br/>
	Móvil: {$customer.mobile_phone|htmlsafe}<br/>
	{$LANG.email}: <span class="blue">{$customer.email|htmlsafe}</span><br/>
		
        </div>
		<div class="billing">
        <span class="tbl1-bottom col1" ><b>{$LANG.biller}:</b> Empretel</span><br/>
	{$biller.name|htmlsafe}<br/>
    Teléfono: {$biller.phone|htmlsafe}<br/>
	Móvil: {$biller.mobile_phone|htmlsafe}<br/>
	{$LANG.email}: <span class="blue">{$biller.email|htmlsafe}</span><br/>
		</div>
	</div>
</div>
	<br /><br />
<div style="clear:both;"></div>   
<div>    
<table>
		<tr>
			<td class="" colspan="6"><br /></td>
		</tr>
		<tr>
			<td class="" colspan="6" align="left"><b>{$LANG.notes}:</b></td>
		</tr>
		<tr>
			<td class="" colspan="6">{$invoice.note|outhtml}</td>
		</tr>
</table>
</div>
    
	<table class="left">
    
	<table class="left" width="100%">
<tr>
			<td colspan="6"><br /></td>
		</tr>

	{if $invoice.type_id == 2 }
					<tr>
				<td class="tbl1-bottom col1" width="8%" align="center"><b>{$LANG.quantity_short}</b></td>
				<td class="tbl1-bottom col1" width="18%" colspan="2" align="center"><b>{$LANG.item}</b></td>
  				<td class="tbl1-bottom col1" width="46%" align="center"><b>{$LANG.description_short}</b></td>
				<td class="tbl1-bottom col1" width="13%" align="center"><b>{$LANG.Unit_Cost}</b></td>
				<td class="tbl1-bottom col1" width="15%" align="center"><b>{$LANG.total_uppercase}</b></td>
			</tr>
			
				{foreach from=$invoiceItems item=invoiceItem}

<tr class="font3" >
  <td class="separay" width="8%" align="center">{$invoiceItem.quantity|siLocal_number_trim}</td>
<td class="separay" width="18%" colspan="2" align="left" margin-left:"4px" >{$invoiceItem.product.description|htmlsafe}</td>
				<td class="separay" width="46%" align="left">{$invoiceItem.product.custom_field1|htmlsafe}</td>
				<td class="separay" width="13%" align="center">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.unit_price|siLocal_number}</td>
<td class="separay" width="15%" align="center">{$preference.pref_currency_sign|htmlsafe}{$invoiceItem.gross_total|siLocal_number}</td>
			</tr>
			{if $invoiceItem.description != null}
			<tr class="">
				<td class="separay"></td>
				<td class="separay" colspan="5">{$LANG.description}: {$invoiceItem.description|htmlsafe}</td>
			</tr>
			{/if}
			
<tr class="tbl1-bottom">
                <td class=""></td>
				<td class="" colspan="5">
					<table width="100%">
						<tr>

					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf2 field=$invoiceItem.product.custom_field2}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf3 field=$invoiceItem.product.custom_field3}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf4 field=$invoiceItem.product.custom_field4}
					{do_tr number=4 class="blank-class"}
 
						</tr>
					</table>
</td>
            </tr>
             	{/foreach}
	{/if}

	{if $invoice.type_id == 3 }
			<tr class="tbl1-bottom col1">
				<td class="tbl1-bottom "><b>{$LANG.quantity_short}</b></td>
				<td colspan="3" class=" tbl1-bottom"><b>{$LANG.item}</b></td>
				<td align="right" class=" tbl1-bottom"><b>{$LANG.Unit_Cost}</b></td>
				<td align="right" class=" tbl1-bottom  "><b>{$LANG.Price}</b></td>
			</tr>
		
			{foreach from=$invoiceItems item=invoiceItem}
	
			<tr class="">
				<td class="" >{$invoiceItem.quantity|siLocal_number}</td>
				<td>{$invoiceItem.product.description|htmlsafe}</td>
				<td class="" colspan="4"></td>
			</tr>
			
						
<tr>       
                <td class=""></td>
				<td class="" colspan="5">
                    <table width="100%">
                        <tr>

					{inv_itemised_cf label=$customFieldLabels.product_cf1 field=$invoiceItem.product.custom_field1}
					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf2 field=$invoiceItem.product.custom_field2}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf3 field=$invoiceItem.product.custom_field3}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels.product_cf4 field=$invoiceItem.product.custom_field4}
					{do_tr number=4 class="blank-class"}

                      </tr>
                    </table>
                </td>
            </tr>
	
<tr class="">
				<td class=""></td>
				<td class="" colspan="5"><i>{$LANG.description}: </i>{$invoiceItem.description|htmlsafe}</td>
</tr>
			<tr class="">
				<td class="" ></td>
				<td class=""></td>
				<td class=""></td>
				<td class=""></td>
				<td align="right" class="">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
				<td align="center" class="">{$preference.pref_currency_sign}{$invoiceItem.total|siLocal_number}</td>
			</tr>
			{/foreach}
	{/if}
	
	{if $invoice.type_id == 1 }
		    <table class="left" width="100%">

                <tr class="col1" >
                    <td class="tbl1-bottom col1" colspan="6"><b>{$LANG.description}</b></td>
                </tr>
                
          {foreach from=$invoiceItems item= invoiceItem}

			    <tr class="">
                    <td class="t" colspan="6">{$invoiceItem.description|outhtml}</td>
                </tr>

		{/foreach}
	{/if}


    {* tax section - start *}
	{if $invoice_number_of_taxes > 0}
	<tr class="font3">
        <td colspan="2"></td>
		<td colspan="3" align="right">{$LANG.sub_total}&nbsp;</td>
		<td colspan="1" align="center">{if $invoice_number_of_taxes > 1}<u>{/if}{$preference.pref_currency_sign|htmlsafe}{$invoice.gross|siLocal_number}{if $invoice_number_of_taxes > 1}</u>{/if}</td>
    </tr>
    {/if}
	{if $invoice_number_of_taxes > 1 }
	        <tr>
        	        <td colspan="6"><br /></td>
	        </tr>
    {/if}
    {section name=line start=0 loop=$invoice.tax_grouped step=1}
    	{if ($invoice.tax_grouped[line].tax_amount != "0") }
    	
    	<tr class="font3">
	        <td colspan="2"></td>
			<td colspan="3" align="right">{$invoice.tax_grouped[line].tax_name|htmlsafe}&nbsp;(13%)</td>
			<td colspan="1" align="center">{$preference.pref_currency_sign|htmlsafe}{$invoice.tax_grouped[line].tax_amount|siLocal_number}</td>
	    </tr>
	    {/if}
	    
	{/section}
	{if $invoice_number_of_taxes > 1}
	<tr class="font3">
        <td colspan="2"></td>
		<td colspan="3" align="right">{$LANG.tax_total}&nbsp;</td>
		<td colspan="1" align="center"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.total_tax|siLocal_number}</u></td>
    </tr>
    {/if}
	{if $invoice_number_of_taxes > 1}
	<tr>
		<td colspan="6"><br /></td>
	</tr>
    {/if}
    <tr class="font3">
        <td colspan="2"></td>
		<td colspan="3" align="right"><b>{$LANG.amount}&nbsp;</b></td>
		<td colspan="1" align="center"><span class="double_underline"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.total|siLocal_number}</u></span></td>
    </tr>
    {* tax section - end *}
{*
		<tr class="font3">
			<td class="" colspan="2"></td>
			<td align="right" colspan="2">{$LANG.sub_total}</td>
			<td align="center" class="">{$preference.pref_currency_sign|htmlsafe}{$invoice.gross|siLocal_number}</td>
		</tr>
	
	
    {section name=line start=0 loop=$invoice.tax_grouped step=1}

		{if ($invoice.tax_grouped[line].tax_amount != "0") }  
		
		<tr class='font3'>
	        <td colspan="2"></td>
			<td colspan="2" align="right">{$invoice.tax_grouped[line].tax_name|htmlsafe}</td>
			<td colspan="1" align="center">{$preference.pref_currency_sign|htmlsafe}{$invoice.tax_grouped[line].tax_amount|siLocal_number}</td>
	    </tr>
	    
	    {/if}
	    
	{/section}
	
	<tr class='font3'>
        <td colspan="2"></td>
		<td colspan="3" align="right">{$LANG.tax_total}</td>
		<td colspan="1" align="center"><u>{$preference.pref_currency_sign|htmlsafe}{$invoice.total_tax|siLocal_number}</u></td>
    </tr>
	
	
	<tr class="">
		<td class="" colspan="6" ><br /></td>
	</tr>
	<tr class="font3">
		<td class="" colspan="2"></td>
		<td class="" align="right" colspan="3"><b>{$preference.pref_inv_wording|htmlsafe} {$LANG.amount}</b></td>
		<td  class="" align="center"><span class="double_underline" >{$preference.pref_currency_sign|htmlsafe}{$invoice.total|siLocal_number}</span></td>
	</tr>
*}
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>
	
	<!-- invoice details section - start -->

	<tr>
		<td class="tbl1-bottom col1" colspan="6"><b><small>{$preference.pref_inv_detail_heading|htmlsafe}</small></b></td>
	</tr>
	<tr>
		<td colspan="6"><i><small>{$preference.pref_inv_detail_line|outhtml}</small></i></td>
	</tr>
	<tr>
		<td class="" colspan="6"><small>{$preference.pref_inv_payment_method|htmlsafe}</small></td>
	</tr>
    {section name=line start=0 loop=$invoice.tax_grouped step=1}
    {if ($invoice.tax_grouped[line].tax_amount == "0") }
	<tr>
		<td colspan="6"><small>Precios no incluyen IVA</small></i></td>
	</tr>
    {/if}
    {/section}       
	<tr>
		<td class="" colspan="6"><small>{$preference.pref_inv_payment_line1_name|htmlsafe} {$preference.pref_inv_payment_line1_value|htmlsafe}</small></td>
	</tr>
	<tr>
		<td class="" colspan="6"><small>{$preference.pref_inv_payment_line2_name|htmlsafe} {$invoice.custom_field1|htmlsafe}</small></td>
	</tr>
		<tr>
		<td class="" colspan="6"><small>{$preference.pref_inv_payment_line2_value|htmlsafe} {$invoice.custom_field2|htmlsafe}</small></td>
	</tr>    
	<tr>
		<td><br /></td>
	</tr>
	<tr>
		<td colspan="6"><div style="font-size:8pt;" align="center">{$biller.footer|outhtml}</div></td>
	</tr>
	<tr>
    <td></td>
		<td>
			{online_payment_link 
				type=$preference.include_online_payment business=$biller.paypal_business_name 
				item_name=$invoice.index_name invoice=$invoice.id 
				amount=$invoice.total currency_code=$preference.currency_code
				link_wording=$LANG.paypal_link
				notify_url=$biller.paypal_notify_url return_url=$biller.paypal_return_url
				domain_id = $invoice.domain_id include_image=true
			}

		</td>
	</tr>

	<!-- invoice details section - end -->

</table>

<div id="footer"></div>

</table>
</table>
</div>

</body>
</html>