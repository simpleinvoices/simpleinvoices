/*
 * Product Change - updates line item with product price info
 */
function invoice_payment_type_change(row_number, tax){

	$('#gmail_loading').show();
	$.ajax({
		type: 'GET',
		url: './index.php?module=invoices&view=tax_ajax&id='+tax,
	//	data: "id: "+tax,
		dataType: "json",
		success: function(data){
			$('#gmail_loading').hide();
			$price = $("#unit_price"+row_number).val();
			$total = $price * ( data['tax_percentage'] /100 + 1);
			$total =$total.toFixed(2);
			$("#total"+row_number).html($total);
		}

	});
};

/*
 * Product Change - updates line item with product price info
 */
$(".tax_change").livequery('change',function () { 
	var $row_number = $(this).attr("rel");
	var $tax = $(this).val();
	var $quantity = $("#quantity"+$row_number).attr("value");
	invoice_payment_type_change($row_number, $tax);
});
