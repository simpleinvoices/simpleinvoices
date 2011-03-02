{literal}
<script type="text/javascript">
$(document).ready(function(){

	$("#Container").after(
	'<div id="confirm_delete_line_item" style="display: hidden;" title="Delete this line item?">' +
		'<div style="padding-right: 2em;">If you choose "Delete" the line item will be removed on Save.</div>' +
	'</div>'
	);
	
      	$("#confirm_delete_line_item").dialog({
			autoOpen: false,
			bgiframe: true,
			resizable: false,
			modal: true,
			width:300,
			height:170,
			overlay: {
				backgroundColor: '#000',
				opacity: 0.5
			},
			buttons: {
				Delete: function() {
					var delete_function = $("#confirm_delete_line_item").data('delete_function');
					(delete_function)();
					$(this).dialog('close');
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			}
		});
	/*
	Load the jquery datePicker with out config
	*/
	if($.datePicker){
		$.datePicker.setDateFormat('ymd','-');
		$('input.date-picker').datePicker({startDate:'01/01/1970'});
		$('input#date2').datePicker({endDate:'01/01/1970'});
	}
	if($(".showdownloads")){
		$(".showdownloads").click(function(){
				var offset = $(this).offset();
				$(this)
					.next(".downloads")
						.css("top", offset.top + "px")
						.css("left", offset.left + "px")
						.css("position", "absolute")
						.css("background-color", "#F1F1F1")
						.css("padding", "5px")
						.css("border", "solid 1px #CCC")
						.hover(function(){}, function(){$(this).hide()})
						.show();
				return false;
			})
	}
	if($("#ac_me")){
		$("#ac_me").autocomplete("index.php?module=payments&view=process_ajax", { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
	}
	
	if ($('#tabs_customer'))
		$('#tabs_customer').tabs();
			
	if($('#trigger-tab'))
		$('#trigger-tab').after('<p><a href="#" onclick="$(\'#container-1\').triggerTab(3); return false;">Activate third tab</a></p>');
				
	if($('#custom-tab-by-hash')){
		$('#custom-tab-by-hash').click(function() {
		    var win = window.open(this.href, '', 'directories,location,menubar,resizable,scrollbars,status,toolbar');
		    win.focus();
		});
	}
	
	
	/*Load the cluetip - only if cluetip plugin has been loaded*/
	if(jQuery.cluetip)
	{
		$('a.cluetip').cluetip(
			{
				activation: 'click',
				sticky: true,
				cluetipClass: 'notice',
				fx: {             
                      open:       'fadeIn', // can be 'show' or 'slideDown' or 'fadeIn'
                      openSpeed:  '70'
    			},
  				arrows: true,
  				closePosition: 'title',			
  				closeText: '<img src="./images/common/cross.png" alt="" />'
			}
		);
	}

	//load the configs for the html editor
	//$('.editor').livequery(function(){ $(this).wysiwyg({
	if($('.editor') && $('.editor')[0] && typeof($('.editor')[0].contentEditable) != 'undefined'
	&& !(
	(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i))
	&& navigator.userAgent.match(/CPU\sOS\s[0123]_\d/i))) //only if it is supported | iPhone OS <= 3.2 incorrectly report it
	{
	$('.editor').wysiwyg({
    controls : {
	    html : { visible : true },
	    createLink : { visible : false },
	    insertImage : { visible : false },
		separator00 : { visible : false, separator : false },
		separator01 : { visible : false, separator : false },
		separator02 : { visible : false, separator : false },
		separator03 : { visible : false, separator : false },
		separator04 : { visible : false, separator : false },
		separator05 : { visible : false, separator : false },
		separator06 : { visible : false, separator : false },
		separator07 : { visible : false, separator : false },
		separator08 : { visible : false, separator : false },
		separator09 : { visible : false, separator : false },
		h1mozilla : { visible : false},
		h2mozilla : { visible : false},
		h3mozilla : { visible : false},
		h1 : { visible : false},
		h2 : { visible : false},
		h3 : { visible : false},
		increaseFontSize : { visible : false },
		decreaseFontSize : { visible : false },
        insertOrderedList : { visible : true },
        insertUnorderedList : { visible : true }
    }
	});
	}

	//hide the description field for each line item on invoice creation
	$('.notes').hide();

	/*
	* Product Change - updates line item with product price info
	*/
	$(".product_change").livequery('change',function () { 
      	var $row_number = $(this).attr("rel");
      	var $product = $(this).val();
      	var $quantity = $("#quantity"+$row_number).attr("value");
 		invoice_product_change($product, $row_number, $quantity);
		siLog('debug','{/literal}{$LANG.description}{literal}');
     });
 

	/*
	* Product Inventory Change - updates line item with product price info
	*/
	$(".product_inventory_change").livequery('change',function () { 
      	var $product = $(this).val();
      	var $existing_cost = $("#cost").val();
 		product_inventory_change($product,$existing_cost);
     });
 
    
	//delete line in invoice
	$(".trash_link").livequery('click',function () { 
      id = $(this).attr("rel");
	{/literal}
	{if $config->confirm->deleteLineItem}
	{literal}
		var delete_function = function() { 
			delete_row(id); 
		}
	{/literal}
		$("#confirm_delete_line_item").data('delete_function', delete_function);
		$("#confirm_delete_line_item").dialog('open');
	{else}
		delete_row(id);
	{/if}
	{literal}
    });
	
	//delete line in invoice
	$(".trash_link_edit").livequery('click',function () { 
      id = $(this).attr("rel");

	{/literal}
	{if $config->confirm->deleteLineItem}
	{literal}
		var delete_function = function() {
			delete_line_item(id); 
		}
	{/literal}
		$("#confirm_delete_line_item").data('delete_function', delete_function);
		$("#confirm_delete_line_item").dialog('open');
	{else}
		delete_line_item(id);
	{/if}
	{literal}
    });

	//add new lien item in invoices
	$("a.add_line_item").click(function () { 
		add_line_item();
		//autoFill($(".note"), "Description");
    });


	//calc number of line items 
	$(".invoice_save").click(function () {
		$('#gmail_loading').show();
		siLog('debug','invoice save');
		count_invoice_line_items();
		siLog('debug','invoice save- post count');
		//invoice_save_remove_autofill();
		$('#gmail_loading').hide();
	});

	
	//Autofill "Description" into the invoice items description/notes textarea
	$(".note").livequery(function(){
			
			$description = $(".note").val();
		
			if ($description == "")
			{
				$(".note").val('{/literal}{$LANG.description}{literal}');
				//$(this).val("").css({ color: '#333'});
			}
	
			$(".note").focus(function(){
	            if($(this).val()=="{/literal}{$LANG.description}{literal}"){
	               $(this).val("").css({ color: '#333' });
            }
	});
	});
	$(".note").css({ color: "#b2adad" });



	//Export dialog window - onclick export button close window

	$(".export_window").livequery('click',function () { 
		$('.ui-dialog-titlebar-close').trigger('click');
    });

/*
	$(".export_window").click(function () { 
		$('.ui-dialog-titlebar-close').trigger('click');
    });
*/
	/*
	* Product Change - updates line item with product price info
	*/
	$(".invoice_export_dialog").livequery('click',function () { 
      	var $row_number = $(this).attr("rel");
		siLog('debug',"{/literal}$config->export->spreadsheet{literal}");
		export_invoice($row_number, '{/literal}{$config->export->spreadsheet}{literal}','{/literal}{$config->export->wordprocessor}{literal}');
     });

});

</script>
{/literal}
