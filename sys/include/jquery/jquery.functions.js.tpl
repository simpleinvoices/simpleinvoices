{literal}
<script type="text/javascript">
	/*
	* Script: jquery.functions.js
	* Purpose: jquery/javascript functions for Simple Invoices 
	*/
	
	// for autocomplete in papyment page
	function selectItem(li) {
		if (li.extra)
	        document.getElementById("js_total").innerHTML= " " + li.extra[0] + " "
	}
	
	// for autocomplete in papyment page
	function formatItem(row) {
		return row[0] + "<br><i>" + row[1] + "</i>";
	}
	
	//delete line item in new invoice page
	function delete_row(row_number)
	{
	//	$('#row'+row_number).hide(); 
		$('#row'+row_number).remove(); 
	}
	
	//dlete line item in EDIT page
	function delete_line_item(row_number)
	{
		$('#row'+row_number).hide(); 
		$('#quantity'+row_number).removeAttr('value');
		$('#delete'+row_number).attr('value','yes');
	}
	

	/*
	* Product Change - updates line item with product price info
	*/
	function invoice_product_change(product,row_number, quantity){
	
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
	* Product Change -Inventory  - updates cost from  product info
	*/
	function product_inventory_change(product,existing_cost){
	
      	$('#gmail_loading').show();
		$.ajax({
			type: 'GET',
			url: './index.php?module=invoices&view=product_inventory_ajax&id='+product,
			data: "id: "+product,
			dataType: "json",
			success: function(data){
				$('#gmail_loading').hide();
                if(existing_cost !==null)
                {
				    $("#cost").attr("value",data['cost']);
                }
			}
	
   		 });
     };

	/*
	 * Function: count_invoice_line_items
	 * Purpose: find the last line item and update max_items so /modules/invoice/save.php can access it
	 */
	function count_invoice_line_items()
	{
		var lastRow = $('#itemtable tbody.line_item:last'); 
		var rowID_last = $("input[@id^='quantity']",lastRow).attr("id");
		rowID_last = parseInt(rowID_last.slice(8)); //using 8 as 'quantity' has eight letters and want to get the number thats after that
		/*$("#max_items").val(rowID_last);*/
		$("#max_items").attr('value',rowID_last);
		siLog('debug', 'Max Items = '+rowID_last );
		
	}

	/*
	* function: siLog
	* purpose: wrapper function for blackbirdjs logging
	* if debugging is OFF in config.ini - then blackbirdjs.js wont be loaded in header.tpl and normal call to log.debug would fail and cause problems
	*/
	function siLog(level,message)
	{
		log_level = "log." + level + "('" + message + "')";
		
		//if blackbirdjs is loaded (ie. debug in config.ini is on) run - else do nothing
		if(window.log)
		{
			eval(log_level);
		}
	}
	
     /*
	 * function: add_line_item
	 * purpose: to add a new line item in invoice creation page
	 * */
	function add_line_item()
	{
		$('#gmail_loading').show();
		
		//clone the last tr in the item table
		var clonedRow = $('#itemtable tbody.line_item:first').clone(); 
		var lastRow = $('#itemtable tbody.line_item:last').clone(); 
	
		//find the Id for the row from the quantity if
		var rowID_old = $("input[@id^='quantity']",clonedRow).attr("id");
		var rowID_last = $("input[@id^='quantity']",lastRow).attr("id");
		rowID_old = parseInt(rowID_old.slice(8)); //using 8 as 'quantity' has eight letters and want to get the number thats after that
		rowID_last = parseInt(rowID_last.slice(8)); //using 8 as 'quantity' has eight letters and want to get the number thats after that
	
		//create next row id
		var rowID_new = rowID_last + 1;
		
		siLog('debug','Line item '+rowID_new+'added');
		//log.debug( 'Line item '+rowID_new+'added');
	
		//console.log("Old row ID: "+rowID_old);
		//console.log("New row ID:"+rowID_new);
		//console.log("Last row ID:"+rowID_last);
	
		//update all the row items
		//
	
		clonedRow.attr("id","row"+rowID_new);
		//trash image
		clonedRow.find("#trash_link"+rowID_old).attr("id", "trash_link"+rowID_new);
		clonedRow.find("#trash_link"+rowID_new).attr("name", "trash_link"+rowID_new);
		clonedRow.find("#trash_link_edit"+rowID_old).attr("id", "trash_link_edit"+rowID_new);
		clonedRow.find("#trash_link_edit"+rowID_new).attr("name", "trash_link_edit"+rowID_new);

		//update teh hidden delete field
		clonedRow.find("#delete"+rowID_old).attr("id", "delete"+rowID_new);
		clonedRow.find("#delete"+rowID_new).attr("name", "delete"+rowID_new);
		//update the delete icon
		clonedRow.find("#delete_image"+rowID_old).attr("id", "delete_image"+rowID_new);
		clonedRow.find("#delete_image"+rowID_new).attr("name", "delete_image"+rowID_new);
		clonedRow.find("#delete_image"+rowID_new).attr("src", "./images/common/delete_item.png");

		clonedRow.find("#trash_link"+rowID_new).attr("href", "#");
		clonedRow.find("#trash_link"+rowID_new).attr("rel", rowID_new);
		clonedRow.find("#trash_link_edit"+rowID_new).attr("href", "#");
		clonedRow.find("#trash_link_edit"+rowID_new).attr("rel", rowID_new);
	
		clonedRow.find("#trash_image"+rowID_old).attr("src", "./images/common/delete_item.png");
		clonedRow.find("#trash_image"+rowID_old).attr("title", "Delete this row");
	
		//edit invoice - newly added line item
		clonedRow.find("#line_item"+rowID_old).attr("id", "line_item"+rowID_new);
		clonedRow.find("#line_item"+rowID_new).attr("name", "line_item"+rowID_new);
		clonedRow.find("#line_item"+rowID_new).val('');


		$("#quantity"+rowID_old, clonedRow).attr("id", "quantity"+rowID_new);
		$("#quantity"+rowID_new, clonedRow).attr("name", "quantity"+rowID_new);
		clonedRow.find("#quantity"+rowID_new).removeAttr("value");
		clonedRow.find("#quantity"+rowID_new).removeClass("validate[required]");
	
		//clonedRow.find("#products"+rowID_old).removeAttr("onchange");
		clonedRow.find("#products"+rowID_old).attr("rel", rowID_new);
		clonedRow.find("#products"+rowID_old).attr("id", "products"+rowID_new);
		clonedRow.find("#products"+rowID_new).attr("name", "products"+rowID_new);
		clonedRow.find("#products"+rowID_new).removeClass("validate[required]");

		//clonedRow.find("#products"+rowID_new).attr("onChange", "invoice_product_change_price($(this).val(), "+rowID_new+", jQuery('#quantity"+rowID_new+"').val() )");
	
		$("#unit_price"+rowID_old, clonedRow).attr("id", "unit_price"+rowID_new);
		$("#unit_price"+rowID_new, clonedRow).attr("name", "unit_price"+rowID_new);
		$("#unit_price"+rowID_new, clonedRow).val("");
		$("#unit_price"+rowID_new, clonedRow).removeClass("validate[required]");
	
		$("#description"+rowID_old, clonedRow).attr("id", "description"+rowID_new);
		$("#description"+rowID_new, clonedRow).attr("name", "description"+rowID_new);
		//$("#description"+rowID_new, clonedRow).attr("value","{/literal}{$LANG.description}{literal}");
		//$("#description"+rowID_new, clonedRow).css({ color: "#b2adad" });
	
		$("#tax_id\\["+rowID_old+"\\]\\[0\\]", clonedRow).attr("id", "tax_id["+rowID_new+"][0]");
		$("#tax_id\\["+rowID_new+"\\]\\[0\\]", clonedRow).attr("name", "tax_id["+rowID_new+"][0]");
		$("#tax_id\\["+rowID_old+"\\]\\[1\\]", clonedRow).attr("id", "tax_id["+rowID_new+"][1]");
		$("#tax_id\\["+rowID_new+"\\]\\[1\\]", clonedRow).attr("name", "tax_id["+rowID_new+"][1]");
	
		$('#itemtable').append(clonedRow);
		
		$('#gmail_loading').hide();
	
	}
	
	//the export dialog in the manage invoices page
	function export_invoice(row_number,spreadsheet,wordprocessor){
	
	
		 $("#export_dialog").show();
			siLog('debug','export_dialog_show');
		 $(".export_pdf").attr({ 
	          //href: "index.php?module=export&view=pdf&id="+row_number
			  href: "index.php?module=export&view=invoice&id="+row_number+"&format=pdf"
	        });
		 $(".export_doc").attr({ 
			  href: "index.php?module=export&view=invoice&id="+row_number+"&format=file&filetype="+wordprocessor
	        });	 
	      $(".export_xls").attr({ 
	          href: "index.php?module=export&view=invoice&id="+row_number+"&format=file&filetype="+spreadsheet
	        });							
		 $("#export_dialog").dialog({ 
		   modal: true, 
		   buttons: { 
	        "Cancel": function() { 
	            $(this).dialog("destroy"); 
	        }
	        },
		    overlay: { 
		        opacity: 0.5, 
		        background: "black" 
		    },
			close: function() { $(this).dialog("destroy")}
		});
	
	}

	
	/*
	 * Function: invoice_save_remove_autofill
	 * Purpose: remove the autofilled text in the line items notes box
	 * NOTE - might remove this as php can handle this
	 */
	//delete - not used anymore
	function invoice_save_remove_autofill()
	{
		siLog('debug','exectued invoice save remove');
		
		var description = $("textarea[@id^='description']").attr("value");

		if (description =='{/literal}{$LANG.description}{literal}')
		{
			siLog('info','autofill value of '+description+' to be removed removed');
			$("textarea[@id^='description']").val('');
			siLog('info','autofill value was removed');
		}
		
	}

</script>
{/literal}
