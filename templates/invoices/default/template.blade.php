<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
@if(!empty($css_inline))
<style type="text/css">{{ $css_inline }}</style>
@else
<link rel="stylesheet" type="text/css" href="{{ $css|urlsafe }}" media="all">
@endif
<title>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['number_short'] ?? '' }}: {{ $invoice['index_id'] ?? '' }}</title>
</head>
<body>
<br />
<div id="container">
	<div id="header">
	</div>

	<table width="100%" align="center">
		<tr>
			<td colspan="5"><img src="{{ $logo|urlsafe }}" alt="" class="si-invoice-logo"></td>
			<th align="right"><span class="font1">{{ $preference['pref_inv_heading'] ?? '' }}</span></th>
		</tr>
		<tr>
			<td colspan="6" class="tbl1-top">&nbsp;</td>
		</tr>
	</table>

	<!-- Two-column layout: biller/customer left, summary right (table layout works in web and PDF) -->
	<table width="100%" cellspacing="0" cellpadding="0" class="summary-biller-layout">
		<tr>
			<td class="column-left" width="62%" valign="top">
	<table class="left">

    <!-- Biller section - start -->
        <tr>
                <td class="tbl1-bottom col1"><b>{{ $LANG['biller'] ?? '' }}:</b></td>
				<td class="col1 tbl1-bottom" colspan="3">{{ $biller['name'] ?? '' }}</td>
        </tr>

        @if(($biller['street_address'] ?? null) != null)
		<tr>
                <td class=''>{{ $LANG['address'] ?? '' }}:</td>
				<td class='' align=left colspan="3">{{ $biller['street_address'] ?? '' }}</td>
		</tr>
        @endif
        @if(($biller['street_address2'] ?? null) != null )
			@if(($biller['street_address'] ?? null) == null )
		<tr>
                <td class=''>{{ $LANG['address'] ?? '' }}:</td>
				<td class='' align=left colspan="3">{{ $biller['street_address2'] ?? '' }}</td>
		</tr>
			@endif
			@if(($biller['street_address'] ?? null) != null )
		<tr>
                <td class=''></td>
				<td class='' align=left colspan="3">{{ $biller['street_address2'] ?? '' }}</td>
        </tr>
			@endif
        @endif

		{merge_address field1=$biller['city'] field2=$biller['state'] field3=$biller['zip_code'] street1=$biller['street_address'] street2=$biller['street_address2'] class1="" class2="" colspan="3"}

		@if(($biller['country'] ?? null) != null )
		<tr>
				<td class=''></td>
				<td class='' colspan="3">{{ $biller['country'] ?? '' }}</td>
		</tr>
       	@endif

	{print_if_not_null label=$LANG['phone_short'] field=$biller['phone'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG['fax'] field=$biller['fax'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG['mobile_short'] field=$biller['mobile_phone'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG['email'] field=$biller['email'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['biller_cf1'] field=$biller['custom_field1'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['biller_cf2'] field=$biller['custom_field2'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['biller_cf3'] field=$biller['custom_field3'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['biller_cf4'] field=$biller['custom_field4'] class1='' class2='' colspan="3"}

		<tr>
				<td class="" colspan="4"> </td>
		</tr>

	<!-- Biller section - end -->

		<tr>
			<td colspan="4"><br /></td>
		</tr>

	<!-- Customer section - start -->
	<tr>
			<td class="tbl1-bottom col1" ><b>{{ $LANG['customer'] ?? '' }}:</b></td>
			<td class="tbl1-bottom col1" colspan="3">{{ $customer['name'] ?? '' }}</td>
	</tr>

        @if(($customer['department'] ?? null) != null )
    <tr>
            <td class=''>{{ $LANG['customer_department'] ?? '' }}:</td>
			<td align=left class='' colspan="3" >{{ $customer['department'] ?? '' }}</td>
    </tr>
       @endif

        @if(($customer['attention'] ?? null) != null )
    <tr>
            <td class=''>{{ $LANG['attention_short'] ?? '' }}:</td>
			<td align=left class='' colspan="3" >{{ $customer['attention'] ?? '' }}</td>
    </tr>
       @endif

        @if(($customer['street_address'] ?? null) != null )
    <tr >
            <td class=''>{{ $LANG['address'] ?? '' }}:</td>
			<td class='' align=left colspan="3">{{ $customer['street_address'] ?? '' }}</td>
    </tr>
        @endif

        @if(($customer['street_address2'] ?? null) != null)
                @if(($customer['street_address'] ?? null) == null)
    <tr>
            <td class=''>{{ $LANG['address'] ?? '' }}:</td>
			<td class='' align=left colspan="3">{{ $customer['street_address2'] ?? '' }}</td>
    </tr>
                @endif

                @if(($customer['street_address'] ?? null) != null)
    <tr>
			<td class=''></td>
			<td class='' align=left colspan="3">{{ $customer['street_address2'] ?? '' }}</td>
    </tr>
                @endif
        @endif

		{merge_address field1=$customer['city'] field2=$customer['state'] field3=$customer['zip_code'] street1=$customer['street_address'] street2=$customer['street_address2'] class1="" class2="" colspan="3"}

         @if(($customer['country'] ?? null) != null)
    <tr>
            <td class=''></td>
			<td class='' colspan="3">{{ $customer['country'] ?? '' }}</td>
    </tr>
        @endif

	{print_if_not_null label=$LANG['phone_short'] field=$customer['phone'] class1='' class2='t' colspan="3"}
	{print_if_not_null label=$LANG['fax'] field=$customer['fax'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG['mobile_short'] field=$customer['mobile_phone'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$LANG['email'] field=$customer['email'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['customer_cf1'] field=$customer['custom_field1'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['customer_cf2'] field=$customer['custom_field2'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['customer_cf3'] field=$customer['custom_field3'] class1='' class2='' colspan="3"}
	{print_if_not_null label=$customFieldLabels['customer_cf4'] field=$customer['custom_field4'] class1='' class2='' colspan="3"}

		<tr>
			<td class="" colspan="4"></td>
		</tr>
	</table>
			</td>
			<td class="column-right" width="38%" valign="top" align="right">
	<!-- Summary - start -->
	<table class="right invoice-summary-table">
		<tr>
				<td class="col1 tbl1-bottom" colspan="4" ><b>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['summary'] ?? '' }}</b></td>
		</tr>
		<tr>
				<td class="">{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['number_short'] ?? '' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['index_id'] }}</td>
		</tr>
		<tr>
				<td nowrap class="">{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['date'] ?? '' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['date'] }}</td>
		</tr>
		@if(!empty($invoice['payment_term_id']) || !empty($invoice['payment_term_code']) || !empty($invoice['payment_term_label']))
		<tr>
				<td nowrap class="">{{ $LANG['payment_term_code'] ?? 'Payment term code' }}:</td>
				<td class="" align="right" colspan="3">{{ !empty($invoice['payment_term_code']) ? $invoice['payment_term_code'] : ($invoice['payment_term_label'] ?? '') }}</td>
		</tr>
		@endif
		@if(!empty($invoice['payment_term_code']) && !empty($invoice['payment_term_label']))
		<tr>
				<td nowrap class="">{{ $LANG['payment_terms'] ?? 'Payment terms' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['payment_term_label'] }}</td>
		</tr>
		@endif
		@if(!empty($invoice['calc_due_date']))
		<tr>
				<td nowrap class="">{{ $LANG['due_date'] ?? 'Due date' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['due_date'] ?? '' }}</td>
		</tr>
		@endif
	<!-- Show the Invoice Custom Fields if valid -->
		@if(($invoice['custom_field1'] ?? null) != null)
		<tr>
				<td nowrap class="">{{ $customFieldLabels['invoice_cf1'] ?? '' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['custom_field1'] ?? '' }}</td>
		</tr>
		@endif
		@if(($invoice['custom_field2'] ?? null) != null)
		<tr>
				<td nowrap class="">{{ $customFieldLabels['invoice_cf2'] ?? '' }}:</td>
				<td class="" align="right"  colspan="3">{{ $invoice['custom_field2'] ?? '' }}</td>
		</tr>
		@endif
		@if(($invoice['custom_field3'] ?? null) != null)
		<tr>
				<td nowrap class="">{{ $customFieldLabels['invoice_cf3'] ?? '' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['custom_field3'] ?? '' }}</td>
		</tr>
		@endif
		@if(($invoice['custom_field4'] ?? null) != null)
		<tr>
				<td nowrap class="">{{ $customFieldLabels['invoice_cf4'] ?? '' }}:</td>
				<td class="" align="right" colspan="3">{{ $invoice['custom_field4'] ?? '' }}</td>
		</tr>
		@endif

		<tr>
				<td class="" >{{ $LANG['total'] ?? '' }}: </td>
				<td class="" align="right" colspan="3">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['total'] ?? '')|siLocal_number }}</td>
		</tr>
		<tr>
				<td class="">{{ $LANG['paid'] ?? '' }}:</td>
				<td class="" align="right" colspan="3" >{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['paid'] ?? '')|siLocal_number }}</td>
		</tr>
		<tr>
				<td nowrap class="">{{ $LANG['owing'] ?? '' }}:</td>
				<td class="" align="right" colspan="3" >{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['owing'] ?? '')|siLocal_number }}</td>
		</tr>

	</table>
	<!-- Summary - end -->
			</td>
		</tr>
	</table>

	<table class="left invoice-items-table" width="100%">
		<tr>
			<td colspan="6"><br /></td>
		</tr>

	@if(($invoice['type_id'] ?? null) == 2 )
					<tr>
				<td class="tbl1-bottom col1 col-qty"><b>{{ $LANG['quantity_short'] ?? '' }}</b></td>
				<td class="tbl1-bottom col1" colspan="3"><b>{{ $LANG['item'] ?? '' }}</b></td>
				<td class="tbl1-bottom col1" align="right"><b>{{ $LANG['unit_cost'] ?? '' }}</b></td>
				<td class="tbl1-bottom col1" align="right"><b>{{ $LANG['price'] ?? '' }}</b></td>
			</tr>

				@foreach(($invoiceItems ?? []) as $invoiceItem)

			<tr class="" >
				<td class="col-qty">{{ ($invoiceItem['quantity'] ?? '')|siLocal_number_trim }}</td>
				<td class="" colspan="3">{!! outhtml($invoiceItem['product']['description'] ?? '') !!}</td>
				<td class="" align="right">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoiceItem['unit_price'] ?? '')|siLocal_number }}</td>
				<td class="" align="right">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoiceItem['gross_total'] ?? '')|siLocal_number }}</td>
			</tr>
					@if(($invoiceItem['attribute'] ?? null) != null)
                            <tr class="si_product_attribute">
                                <td></td>
                                <td>
                                <table>
                                    <tr class="si_product_attribute">
                                    @foreach(($invoiceItem['attribute_json'] ?? []) as $k => $v)
                                       @if(($v['visible'] ?? null) == true )
                                        <td class="si_product_attribute">
                                            @if(($v['type'] ?? null) == 'decimal')
                                              {{ $v['name'] }}: {{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($v['value'] ?? '')|siLocal_number }};
                                             @elseif(($v['value'] ?? '') != '')
                                               {{ $v['name'] }}: {{ $v['value'] }};
                                            @endif
                                        </td>
                                        @endif
                                    @endforeach
                                    </tr>
                                </table>
                                </td>
                            </tr>
					@endif
			@if(($invoiceItem['description'] ?? null) != null)
			<tr class="">
				<td class=""></td>
				<td class="" colspan="5">{{ $LANG['description'] ?? '' }}: {!! outhtml($invoiceItem['description'] ?? '') !!}</td>
			</tr>
			@endif

            <tr class="tbl1-bottom">
                <td class=""></td>
				<td class="" colspan="5">
					<table width="100%">
						<tr>

					{inv_itemised_cf label=$customFieldLabels['product_cf1'] field=$invoiceItem['product']['custom_field1']}
					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf2'] field=$invoiceItem['product']['custom_field2']}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf3'] field=$invoiceItem['product']['custom_field3']}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf4'] field=$invoiceItem['product']['custom_field4']}
					{do_tr number=4 class="blank-class"}

						</tr>
					</table>
                </td>
            </tr>
             	@endforeach
	@endif

	@if(($invoice['type_id'] ?? null) == 3 )
			<tr class="tbl1-bottom col1">
				<td class="tbl1-bottom col-qty"><b>{{ $LANG['quantity_short'] ?? '' }}</b></td>
				<td colspan="3" class=" tbl1-bottom"><b>{{ $LANG['item'] ?? '' }}</b></td>
				<td align="right" class=" tbl1-bottom"><b>{{ $LANG['unit_cost'] ?? '' }}</b></td>
				<td align="right" class=" tbl1-bottom  "><b>{{ $LANG['price'] ?? '' }}</b></td>
			</tr>

			@foreach(($invoiceItems ?? []) as $invoiceItem)
	
			<tr class=" ">
				<td class="col-qty">{{ ($invoiceItem['quantity'] ?? '')|siLocal_number }}</td>
				<td>{!! outhtml($invoiceItem['product']['description'] ?? '') !!}</td>
				<td class="" colspan="4"></td>
			</tr>
            <tr>
                <td class=""></td>
				<td class="" colspan="5">
                    <table width="100%">
                        <tr>

					{inv_itemised_cf label=$customFieldLabels['product_cf1'] field=$invoiceItem['product']['custom_field1']}
					{do_tr number=1 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf2'] field=$invoiceItem['product']['custom_field2']}
					{do_tr number=2 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf3'] field=$invoiceItem['product']['custom_field3']}
					{do_tr number=3 class="blank-class"}
					{inv_itemised_cf label=$customFieldLabels['product_cf4'] field=$invoiceItem['product']['custom_field4']}
					{do_tr number=4 class="blank-class"}

                        </tr>
                    </table>
                </td>
            </tr>

			<tr class="">
				<td class=""></td>
				<td class="" colspan="5"><i>{{ $LANG['description'] ?? '' }}: </i>{!! outhtml($invoiceItem['description'] ?? '') !!}</td>
			</tr>
			<tr class="">
				<td class="" ></td>
				<td class=""></td>
				<td class=""></td>
				<td class=""></td>
				<td align="right" class="">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoiceItem['unit_price'] ?? '')|siLocal_number }}</td>
				<td align="right" class="">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoiceItem['total'] ?? '')|siLocal_number }}</td>
			</tr>
			@endforeach
	@endif

	@if(($invoice['type_id'] ?? null) == 1 )
		    <table class="left" width="100%">

                <tr class="col1" >
                    <td class="tbl1-bottom col1" colspan="6"><b>{{ $LANG['description'] ?? '' }}</b></td>
                </tr>

          @foreach(($invoiceItems ?? []) as $invoiceItem)

			    <tr class="">
                    <td class="t" colspan="6">{!! outhtml($invoiceItem['description'] ?? '') !!}</td>
                </tr>

		@endforeach
	@endif

@if((($invoice['type_id'] ?? null) == 2 && ($invoice['note'] ?? '') != "") || (($invoice['type_id'] ?? null) == 3 && ($invoice['note'] ?? '') != "" )  )

		<tr>
			<td class="" colspan="6"><br /></td>
		</tr>
		<tr>
			<td class="" colspan="6" align="left"><b>{{ $LANG['notes'] ?? '' }}:</b></td>
		</tr>
		<tr>
			<td class="" colspan="6">{{ $invoice['note'] ?? '' | outhtml }}</td>
		</tr>

@endif

	<tr class="">
		<td class="" colspan="6" ><br /></td>
	</tr>

    {{-- tax section - start --}}
	@if($invoice_number_of_taxes > 0)
	<tr>
        <td colspan="2"></td>
		<td colspan="3" align="right">{{ $LANG['sub_total'] ?? '' }}&nbsp;</td>
		<td colspan="1" align="right">@if($invoice_number_of_taxes > 1)<u>@endif{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['gross'] ?? '')|siLocal_number }}@if($invoice_number_of_taxes > 1)</u>@endif</td>
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
			<td colspan="1" align="right">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ siLocal::number($line['tax_amount'] ?? 0) }}</td>
	    </tr>
	    @endif
	@endforeach

	@if($invoice_number_of_taxes > 1)
	<tr>
        <td colspan="2"></td>
		<td colspan="3" align="right">{{ $LANG['tax_total'] ?? '' }}&nbsp;</td>
		<td colspan="1" align="right"><u>{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['total_tax'] ?? '')|siLocal_number }}</u></td>
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
		<td colspan="1" align="right"><span class="double_underline"><u>{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['total'] ?? '')|siLocal_number }}</u></span></td>
    </tr>
    {{-- tax section - end --}}
{{-- <tr>
			<td class="" colspan="2"></td>
			<td align="right" colspan="3">{{ $LANG['sub_total'] ?? '' }}</td>
			<td align="right" class="">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['gross'] ?? '')|siLocal_number }}</td>
		</tr>

    @foreach(($invoice['tax_grouped'] ?? []) as $line)

		@if(($line['tax_amount'] ?? 0) != "0")  

		<tr class=''>
	        <td colspan="2"></td>
			<td colspan="3" align="right">{{ $line['tax_name'] ?? '' }}</td>
			<td colspan="1" align="right">{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ siLocal::number($line['tax_amount'] ?? 0) }}</td>
	    </tr>

	    @endif

	

	<tr class=''>
        <td colspan="2"></td>
		<td colspan="3" align="right">{{ $LANG['tax_total'] ?? '' }}</td>
		<td colspan="1" align="right"><u>{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['total_tax'] ?? '')|siLocal_number }}</u></td>
    </tr>

	<tr class="">
		<td class="" colspan="6" ><br /></td>
	</tr>
	<tr class="">
		<td class="" colspan="2"></td>
		<td class="" align="right" colspan="3"><b>{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $LANG['amount'] ?? '' }}</b></td>
		<td  class="" align="right"><span class="double_underline" >{{ ($invoice['currency_sign'] ?? $preference['pref_currency_sign'] ?? '')|si_currency_display }} {{ ($invoice['total'] ?? '')|siLocal_number }}</span></td>
	</tr> --}}
	<tr>
		<td colspan="6"><br /><br /></td>
	</tr>

	<!-- invoice details section - start -->

	<tr>
		<td class="tbl1-bottom col1" colspan="6"><b>{{ $preference['pref_inv_detail_heading'] ?? '' }}</b></td>
	</tr>
	<tr>
		<td class="" colspan="6"><i>{{ $preference['pref_inv_detail_line'] ?? '' | outhtml }}</i></td>
	</tr>
	<tr>
		<td class="" colspan="6">{{ $preference['pref_inv_payment_method'] ?? '' }}</td>
	</tr>
	<tr>
		<td class="" colspan="6">{{ $preference['pref_inv_payment_line1_name'] ?? '' }} {{ $preference['pref_inv_payment_line1_value'] ?? '' }}</td>
	</tr>
	<tr>
		<td class="" colspan="6">{{ $preference['pref_inv_payment_line2_name'] ?? '' }} {{ $preference['pref_inv_payment_line2_value'] ?? '' }}</td>
	</tr>
	<tr>
		<td><br /></td>
	</tr>
	<tr>
		<td colspan="6"><div style="font-size:8pt;" align="center">{{ $biller['footer'] ?? '' | outhtml }}</div></td>
	</tr>
	<tr>
		<td colspan="6">
			{online_payment_link
				type=$preference['include_online_payment']
				invoice=$invoice['id']
				amount=$invoice['owing'] currency_code=($invoice['currency_code'] ?? $preference['currency_code'] ?? '')
				domain_id=$invoice['domain_id']
			}
		</td>
	</tr>

	<!-- invoice details section - end -->

</table>

<div id="footer"></div>

</div>

</body>
</html>
