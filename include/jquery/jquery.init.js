$(document).ready(function(){

	/*$("#tabmenu").removeClass('ui-tabs-hide');*/
	$("#tabmenu > ul").tabs();

	/*hide the text for the export dialog on the manage invoices page*/
	$('#export_dialog').hide();

	$('.show-summary').hide();
	$('.biller').hide();
	$('.customer').hide();
	$('.consulting').hide();
	$('.itemised').hide();
	$('.note').hide();

    $("#dialog").hide();
    $('#invoice_dialog').click(function() 
		{ 
				 $("#dialog").show();  								
				 $("#dialog").dialog({ 
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
				    close:  function() { $(this).dialog("destroy")}
				});
				
		}); 

});
