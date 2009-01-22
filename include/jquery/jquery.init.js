$(document).ready(function(){

	$("#tabmenu > ul").tabs();

	/*hide the text for the export dialog on the manage invoices page*/
	$('#export_dialog').hide();

	$('.show-summary').hide();
	$('.biller').hide();
	$('.customer').hide();
	$('.consulting').hide();
	$('.itemised').hide();
	$('.note').hide();


});
