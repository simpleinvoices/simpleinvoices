$(document).ready(init);

function init(){

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
	/*textarea - rte - make all textarea with class="editor" a html editor box*/
	$('.editor').rte('include/jquery/rte/rte.css', 'include/jquery/rte/');
	
	

}

function selectItem(li) {
	if (li.extra)
        document.getElementById("js_total").innerHTML= " " + li.extra[0] + " "
}

function formatItem(row) {
	return row[0] + "<br><i>" + row[1] + "</i>";
}

	

function delete_line_item(row_number)
{
	$('.row'+row_number).hide(); 
	$('#quantity'+row_number).attr('value','0');
	$('#delete'+row_number).attr('value','yes');
}

function invoice_product_change_price(si_product,row_number, quantity)
{
	$('#gmail_loading').show();
	$.ajax({
		type: 'GET',
		url: './index.php?module=invoices&view=product_ajax&id='+si_product,
		data: "id: "+si_product,
		success: function(data){
			$('#gmail_loading').hide();
			/*$('#state').html(data);*/
			/*if ( (quantity.length==0) || (quantity.value==null) ) */
			if (quantity=="") 
			{	
				$("#quantity"+row_number).attr("value","1");
			}
			$("#unit_price"+row_number).attr("value",data);
		}
	});
}

function add_line_item(row_number)
{
/*    $("#line tr:last").clone().append("#line tr:last");*/
    $("#line"+row_number).clone().append("#line"+row_number);
/*    $("#line :last").hide();*/
    /*
    $('#line'+row_number).after('<tr id><td>THIS IS A TEST<td><tr>');
    */
/*    $('#line'+row_number).hide();
    $('#line'+row_number).append('<tr><td>THIS IS A TEST<td><tr>');
  */

}






	



