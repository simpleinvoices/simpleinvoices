/*
 * Product Change - updates line item with product price info
 */
$("#customer_id").livequery('change', function() {
    var $customer_id = $(this).val();
    invoice_customer_change($customer_id);
});

/*
 * Product Change - updates line item with product price info
 */
function invoice_customer_change(customer_id) {
    $('#gmail_loading').show();
    $.ajax({
        type : 'GET',
        url : './index.php?module=invoices&view=sub_customer_ajax&id='
                + customer_id,
        // data: "id: "+product_code,
        dataType : "json",
        success : function(data) {
            $('#gmail_loading').hide();

            // document.getElementById('product'+row_number ).innerHTML=data;
            $("#custom_field1").html(data);
        },
        complete : function() {
            $('#gmail_loading').hide();
        }
    });
};

