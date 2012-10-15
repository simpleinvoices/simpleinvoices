        /*
        * Product Change - updates line item with product price info
        */
        function invoice_payment_type_change(product,row_number, quantity){
        
        $('#gmail_loading').show();
                $.ajax({
                        type: 'GET',
                        url: './index.php?module=invoices&view=product_ajax&id='+product,
                        data: "id: "+product,
                        dataType: "json",
                        success: function(data){
                                $('#gmail_loading').hide();
                                /*$('#state').html(data);*/
                                /*if ( (quantity.length==0) || (quantity.value==null) ) */
                                if (quantity=="") 
                                {       
                                        $("#quantity"+row_number).attr("value","1");
                                }
                                $("#unit_price"+row_number).attr("value",data['unit_price']);
                                $("#tax_id\\["+row_number+"\\]\\[0\\]").val(data['default_tax_id']);
                                if (data['default_tax_id_2']== null)
                                {
                                        $("#tax_id\\["+row_number+"\\]\\[1\\]").val('');
                                }
                                if (data['default_tax_id_2'] !== null)
                                {
                                        $("#tax_id\\["+row_number+"\\]\\[1\\]").val(data['default_tax_id_2']);
                                }
                        }
        
                 });
     };

        /*
        * Product Change - updates line item with product price info
        */
        $(".payment_type_change").livequery('change',function () { 
        var $row_number = $(this).attr("rel");
        var $product = $(this).val();
        var $quantity = $("#quantity"+$row_number).attr("value");
                invoice_payment_type_change($product, $row_number, $quantity);
     });
