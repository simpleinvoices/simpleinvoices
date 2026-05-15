<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>{{ $invoice['index_name'] ?? '' }}</title>
</head>
<body>
<br />
<div id="container">
<div id="header">
</div>


<table width="100%" align="center">
		<tr>
	   		<td colspan="5"><img src="{{ $logo | urlsafe }}" alt="" style="display:block; max-width:220px; max-height:52px; width:auto; height:auto; vertical-align:middle;" border="0" hspace="0" align="left"></td>
			<th align="right"><span>{{ $preference['pref_inv_heading'] ?? '' }}</span></th>
		</tr>
		<tr>
			<td colspan="6"><hr size="1"></td>
		</tr>
</table>
	

<table >
		<tr>
				<td colspan="4"><b>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['summary'] ?? '' }}</b></td>
		</tr>
		<tr>
				<td >{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['number_short'] ?? '' }}:</td>
				<td colspan="3">{{ $invoice['denorm_index_id'] ?? '' }}</td>
		</tr>
		<tr>
				<td nowrap >{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['date'] ?? '' }}:</td>
				<td colspan="3">{{ $invoice['date'] ?? '' }}</td>
		</tr>
		@if(!empty($invoice['payment_term_id']) || !empty($invoice['payment_term_label']))
		<tr>
				<td nowrap>{{ $LANG['payment_terms'] ?? 'Payment terms' }}:</td>
				<td colspan="3">{{ $invoice['payment_term_label'] ?? '' }}</td>
		</tr>
		@endif
		@if(!empty($invoice['calc_due_date']))
		<tr>
				<td nowrap>{{ $LANG['due_date'] ?? 'Due date' }}:</td>
				<td colspan="3">{{ $invoice['due_date'] ?? '' }}</td>
		</tr>
		@endif
	<!-- Show the Invoice Custom Fields if valid -->
		@if($invoice['custom_field1'] != null)
		<tr>
				<td nowrap>{{ $customFieldLabels['invoice_cf1'] ?? '' }}:</td>
				<td colspan="3">{{ $invoice['custom_field1'] ?? '' }}</td>
		</tr>
		@endif
		@if($invoice['custom_field2'] != null)
		<tr>
				<td nowrap>{{ $customFieldLabels['invoice_cf2'] ?? '' }}:</td>
				<td colspan="3">{{ $invoice['custom_field2'] ?? '' }}</td>
		</tr>
		@endif
		@if($invoice['custom_field3'] != null)
		<tr>
				<td nowrap>{{ $customFieldLabels['invoice_cf3'] ?? '' }}:</td>
				<td colspan="3">{{ $invoice['custom_field3'] ?? '' }}</td>
		</tr>
		@endif
		@if($invoice['custom_field4'] != null)
		<tr>
				<td nowrap>{{ $customFieldLabels['invoice_cf4'] ?? '' }}:</td>
				<td colspan="3">{{ $invoice['custom_field4'] ?? '' }}</td>
		</tr>
		@endif

		<tr>
				<td >{{ $LANG['total'] ?? '' }}: </td>
				<td colspan="3">{!! CurrencySignHelper::formatInvoice($invoice['total'] ?? 0, $invoice, $preference) !!}</td>
		</tr>
		<tr>
				<td >{{ $LANG['paid'] ?? '' }}:</td>
				<td colspan="3">{!! CurrencySignHelper::formatInvoice($invoice['paid'] ?? 0, $invoice, $preference) !!}</td>
		</tr>
		<tr>
				<td nowrap >{{ $LANG['owing'] ?? '' }}:</td>
				<td colspan="3">{!! CurrencySignHelper::formatInvoice($invoice['owing'] ?? 0, $invoice, $preference) !!}</td>
		</tr>

</table>
	<!-- Summary - end -->


<table>

	<!-- Biller section - start -->
        <tr>
                <td border=1 cellpadding=2 cellspacing=1><b>{{ $LANG['biller'] ?? '' }}:</b></td>
				<td border=1 cellpadding=2 cellspacing=1 colspan="3">{{ $biller['name'] ?? '' }}</td>
        </tr> 

        @if($biller['street_address'] != null)
		<tr>
				<td >{{ $LANG['address'] ?? '' }}:</td>
				<td  align="left" colspan="3">{{ $biller['street_address'] ?? '' }}</td>
		</tr>   
        @endif
        @if($biller['street_address2'] != null )
			@if($biller['street_address'] == null )
		<tr>
				<td >{{ $LANG['address'] ?? '' }}:</td>
				<td align="left" colspan="3">{{ $biller['street_address2'] ?? '' }}</td>
		</tr>   
			@endif
			@if($biller['street_address'] != null)
		<tr>
				<td></td>
				<td align="left" colspan="3">{{ $biller['street_address2'] ?? '' }}</td>
		</tr>   
			@endif
		@endif

		{merge_address field1=$biller['city'] field2=$biller['state'] field3=$biller['zip_code'] street1=$biller['street_address'] street2=$biller['street_address2'] class1="" class2="" colspan="3"}
	
	    @if($biller['country'] != null )
        <tr>
        		<td></td>
				<td colspan="3">{{ $biller['country'] ?? '' }}</td>
        </tr>
   		@endif

        @if($biller['phone'] != null )
        <tr>
                <td >{{ $LANG['phone_short'] ?? '' }}:</td>
				<td colspan="3">{{ $biller['phone'] ?? '' }}</td>
        </tr>
   		@endif
        @if($biller['fax'] != null )
		<tr>
				<td >{{ $LANG['fax'] ?? '' }}:</td>
				<td colspan="3">{{ $biller['fax'] ?? '' }}</td>
		</tr>
   		@endif
        @if($biller['mobile_phone'] != null )
		<tr>
                <td >{{ $LANG['mobile_short'] ?? '' }}:</td>
				<td colspan="3">{{ $biller['mobile_phone'] ?? '' }}</td>
		</tr>
   		@endif
        @if($biller['email'] != null )
        <tr>
                <td >{{ $LANG['email'] ?? '' }}:</td>
				<td colspan="3">{{ $biller['email'] ?? '' }}</td>
        </tr>
		@endif
        @if($biller['custom_field1'] != null )
        <tr>
                <td >{{ $customFieldLabels['biller_cf1'] ?? '' }}:</td>
				<td colspan="3">{{ $biller['custom_field1'] ?? '' }}</td>
        </tr>	
		@endif
        @if($biller['custom_field2'] != null )
        <tr>
                <td >{{ $customFieldLabels['biller_cf2'] ?? '' }}:</td>
				<td  colspan="3">{{ $biller['custom_field2'] ?? '' }}</td>
        </tr>	
		@endif
        @if($biller['custom_field3'] != null )
        <tr>
                <td >{{ $customFieldLabels['biller_cf3'] ?? '' }}:</td>
				<td  colspan="3">{{ $biller['custom_field3'] ?? '' }}</td>
        </tr>	
		@endif
        @if($biller['custom_field4'] != null )
        <tr>
                <td >{{ $customFieldLabels['biller_cf4'] ?? '' }}:</td>
				<td  colspan="3">{{ $biller['custom_field4'] ?? '' }}</td>
        </tr>	
		@endif
         @if($biller['tax_id_name_1'] != null )
         <tr>
                 <td >{{ $biller['tax_id_label_1'] ?? '' }}:</td>
 				<td  colspan="3">{{ $biller['tax_id_name_1'] ?? '' }}</td>
         </tr>	
 		@endif
         @if($biller['tax_id_name_2'] != null )
         <tr>
                 <td >{{ $biller['tax_id_label_2'] ?? '' }}:</td>
 				<td  colspan="3">{{ $biller['tax_id_name_2'] ?? '' }}</td>
         </tr>	
 		@endif
		<tr>
				<td colspan="4"></td>
		</tr>

	<!-- Biller section - end -->

	<br />
		<tr>
				<td colspan="3"><br /><td>
		</tr>

	<!-- Customer section - start -->

	<tr>
		<td><b>{{ $LANG['customer'] ?? '' }}:</b></td>
		<td colspan="3">{{ $customer['name'] ?? '' }}</td>
	</tr>

        @if($customer['attention'] != null)
    <tr>
			<td >{{ $LANG['attention_short'] ?? '' }}:</td>
			<td align="left"  colspan="3" >{{ $customer['attention'] ?? '' }}</td>
    </tr>
        @endif
        @if($customer['street_address'] != null )
    <tr>
			<td >{{ $LANG['address'] ?? '' }}:</td>
			<td  align="left" colspan="3">{{ $customer['street_address'] ?? '' }}</td>
    </tr>   
        @endif
        
        @if($customer['street_address2'] != null )

			@if($customer['street_address'] == null )
                <tr >
                        <td >{{ $LANG['address'] ?? '' }}:</td>
						<td  align="left" colspan="3">{{ $customer['street_address2'] ?? '' }}</td>
                </tr>   
			@endif
			@if($customer['street_address'] != null)
                <tr >
                        <td ></td>
						<td  align="left" colspan="3">{{ $customer['street_address2'] ?? '' }}</td>
                </tr>   
            @endif
        @endif

		{merge_address field1=$biller['city'] field2=$biller['state'] field3=$biller['zip_code'] street1=$biller['street_address'] street2=$biller['street_address2'] class1="" class2="" colspan="3"}
	
            @if($customer['country'] != null )
                <tr>
                        <td ></td>
						<td  colspan="3">{{ $customer['country'] ?? '' }}</td>
                </tr>
       		@endif
            @if($customer['phone'] != null )
                <tr>
                        <td >{{ $LANG['phone_short'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['phone'] ?? '' }}</td>
                </tr>
       		@endif
            @if($customer['fax'] != null )
                <tr>
                        <td >{{ $LANG['fax'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['fax'] ?? '' }}</td>
                </tr>
       		@endif
            @if($customer['mobile_phone'] != null )
                <tr>
                        <td >{{ $LANG['mobile_short'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['mobile_phone'] ?? '' }}</td>
                </tr>
       		@endif
            @if($customer['email'] != null )
                <tr>
                        <td >{{ $LANG['email'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['email'] ?? '' }}</td>
                </tr>
			@endif
        	@if($customer['custom_field1'] != null )
                <tr>
                        <td >{{ $customFieldLabels['customer_cf1'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['custom_field1'] ?? '' }}</td>
                </tr>	
			@endif
        	@if($customer['custom_field2'] != null )
                <tr>
                        <td >{{ $customFieldLabels['customer_cf2'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['custom_field2'] ?? '' }}</td>
                </tr>	
			@endif
        	@if($customer['custom_field3'] != null )
                <tr>
                        <td >{{ $customFieldLabels['customer_cf3'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['custom_field3'] ?? '' }}</td>
                </tr>	
			@endif
        	@if($customer['custom_field4'] != null )
                <tr>
                        <td >{{ $customFieldLabels['customer_cf4'] ?? '' }}:</td>
						<td  colspan="3">{{ $customer['custom_field4'] ?? '' }}</td>
                </tr>	
			@endif
         	@if($customer['tax_id_name_1'] != null )
                 <tr>
                         <td >{{ $customer['tax_id_label_1'] ?? '' }}:</td>
 						<td  colspan="3">{{ $customer['tax_id_name_1'] ?? '' }}</td>
                 </tr>	
 			@endif
         	@if($customer['tax_id_name_2'] != null )
                 <tr>
                         <td >{{ $customer['tax_id_label_2'] ?? '' }}:</td>
 						<td  colspan="3">{{ $customer['tax_id_name_2'] ?? '' }}</td>
                 </tr>	
 			@endif
                
				<tr>
						<td colspan="4"></td>
				</tr>

	<!-- Customer section - end -->

</table>

<table width="100%">
	<tr>
		<td colspan="6"><br /></td>
	</tr>
		
	@if($invoice['type_id'] == 2 )
		@include($template_path . '.itemised')
	@endif

	@if($invoice['type_id'] == 3 )
		@include($template_path . '.consulting')
	@endif

	@if($invoice['type_id'] == 1 )
		@include($template_path . '.total')
	@endif
	

    {{-- tax section - start --}}
	@if($invoice_number_of_taxes > 0)
	<tr>
        <td colspan="2"></td>
		<td colspan="3" align="right">{{ $LANG['sub_total'] ?? '' }}&nbsp;</td>
		<td colspan="1" align="right">@if($invoice_number_of_taxes > 1)<u>@endif{!! CurrencySignHelper::formatInvoice($invoice['gross'] ?? 0, $invoice, $preference) !!}@if($invoice_number_of_taxes > 1)</u>@endif</td>
    </tr>
    @endif
	@if($invoice_number_of_taxes > 1 )
	        <tr>
        	        <td colspan="6"><br /></td>
	        </tr>
    @endif
    @foreach(($invoice['tax_grouped'] ?? []) as $line)
    	@if(($line['tax_amount'] ?? 0) != "0")
    	
    	<tr>
	        <td colspan="2"></td>
			<td colspan="3" align="right">{{ $line['tax_name'] ?? '' }}&nbsp;</td>
			<td colspan="1" align="right">{!! CurrencySignHelper::formatInvoice($line['tax_amount'] ?? 0, $invoice, $preference) !!}</td>
	    </tr>
	    @endif
	@endforeach

	@if($invoice_number_of_taxes > 1)
	<tr>
        <td colspan="2"></td>
		<td colspan="3" align="right">{{ $LANG['tax_total'] ?? '' }}&nbsp;</td>
		<td colspan="1" align="right"><u>{!! CurrencySignHelper::formatInvoice($invoice['total_tax'] ?? 0, $invoice, $preference) !!}</u></td>
    </tr>
    @endif
	@if($invoice_number_of_taxes > 1)
	<tr>
		<td colspan="6"><br /></td>
	</tr>
    @endif
    <tr>
        <td colspan="2"></td>
		<td colspan="3" align="right"><b>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['amount'] ?? '' }}&nbsp;</b></td>
		<td colspan="1" align="right"><span class="double_underline">{!! CurrencySignHelper::formatInvoice($invoice['total'] ?? 0, $invoice, $preference) !!}</span></td>
    </tr>
    {{-- tax section - end --}}

    {{-- @foreach(($invoice['tax_grouped'] ?? []) as $line)
    
    	@if(($line['tax_amount'] ?? 0) != "0")  
    	
    	<tr class='details_screen'>
	        <td colspan="2"></td>
			<td colspan="3" align="right">{{ $line['tax_name'] ?? '' }}</td>
			<td colspan="1" align="right">{!! CurrencySignHelper::formatInvoice($line['tax_amount'] ?? 0, $invoice, $preference) !!}</td>
	    </tr>
	    
	    @endif
	    
	


	<tr >
		<td colspan="3"></td>
		<td align="right" colspan="2">{{ $LANG['tax_total'] ?? '' }}</td>
		<td align="right" >{!! CurrencySignHelper::formatInvoice($invoice['total_tax'] ?? 0, $invoice, $preference) !!}</td>
	</tr>
	<tr >
		<td colspan="6" ><br /></td>
	</tr>
	<tr >
		<td colspan="3"></td>
		<td align="right" colspan="2"><b>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['amount'] ?? '' }}</b></td>
		<td  align="right"><u>{!! CurrencySignHelper::formatInvoice($invoice['total'] ?? 0, $invoice, $preference) !!}</u></td>
	</tr> --}}
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>
	
		<!-- invoice details section - start -->
	<tr>
		<td colspan="6"><b>{{ $preference['pref_inv_detail_heading'] ?? '' }}</b></td>
	</tr>
	<tr>
		<td colspan="6"><i>{{ $preference['pref_inv_detail_line'] ?? '' }}</i></td>
	</tr>
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_method'] ?? '' }}</td>
	</tr>
	@if(!empty(($preference['pref_inv_payment_line0_value'] ?? '')))
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_line0_name'] ?? '' }} {{ $preference['pref_inv_payment_line0_value'] ?? '' }}</td>
	</tr>
	@endif
	@if(!empty(($preference['pref_inv_payment_line1_value'] ?? '')))
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_line1_name'] ?? '' }} {{ $preference['pref_inv_payment_line1_value'] ?? '' }}</td>
	</tr>
	@endif
	@if(!empty(($preference['pref_inv_payment_line2_value'] ?? '')))
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_line2_name'] ?? '' }} {{ $preference['pref_inv_payment_line2_value'] ?? '' }}</td>
	</tr>
	@endif
	@if(!empty(($preference['pref_inv_payment_line3_value'] ?? '')))
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_line3_name'] ?? '' }} {{ $preference['pref_inv_payment_line3_value'] ?? '' }}</td>
	</tr>
	@endif
	@if(!empty(($preference['pref_inv_payment_line4_value'] ?? '')))
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_line4_name'] ?? '' }} {{ $preference['pref_inv_payment_line4_value'] ?? '' }}</td>
	</tr>
	@endif
	@if(!empty(($preference['pref_inv_payment_line5_value'] ?? '')))
	<tr>
		<td colspan="6">{{ $preference['pref_inv_payment_line5_name'] ?? '' }} {{ $preference['pref_inv_payment_line5_value'] ?? '' }}</td>
	</tr>
	@endif
	<tr>
		<td><br /></td>
	</tr>
	<tr>
		<td colspan="6"><div style="font-size:8pt;" align="center">{{ $biller['footer'] ?? '' | outhtml }}</div></td>
	</tr>
</table>

<div id="footer"></div>

</div>

</body>
</html>
