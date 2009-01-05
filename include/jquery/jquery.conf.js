$(document).ready(function(){

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
		$("#ac_me").autocomplete("auto_complete_search.php", { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
	}
	
	if ($('#container-1'))
		$('#container-1').tabs();
			
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
     });
     
	//delete line in invoice
	$(".trash_link").livequery('click',function () { 
      id = $(this).attr("rel");
      delete_row(id);
    });
	
	//add new lien item in invoices
	$("a.add_line_item").click(function () { 
		add_line_item();
    });



});
