<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<title>Simple Invoices</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link REL="SHORTCUT ICON" HREF="./images/common/favicon.ico">
	<!-- extJs2 Files 
	<link rel="stylesheet" type="text/css" href="./include/ext2/resources/css/ext-all.css" />
 	<script type="text/javascript" src="./include/ext2/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="./include/ext2/ext-all.js"></script>
	<link rel="stylesheet" type="text/css" href="./include/ext2/grid/grid-examples.css" />
	
	<link rel="stylesheet" type="text/css" href="./include/css/simpleInvoicesStyle.css" />
	-->

{literal}
	<link rel="stylesheet" type="text/css" href="./extensions/tab_menu/templates/default/css/tab-screen.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="./extensions/tab_menu/templates/default/css/default/default.dialog.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./templates/default/css/print.css" media="print"/>
	<!-- jQuery Files -->
	<script type="text/javascript" src="./include/jquery/jquery-1.2.6.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./extensions/tab_menu/templates/default/css/tab_menu.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./extensions/tab_menu/templates/default/css/tab.css" media="all"/>
	<script type="text/javascript" src="./include/jquery/jquery-ui-personalized-1.6rc2.packed.js"></script>
	<script type="text/javascript" src="./include/jquery/cluetip/jquery.hoverIntent.minified.js"></script>
	<script type="text/javascript" src="./include/jquery/cluetip/jquery.cluetip.js"></script>
	<script type="text/javascript" src="./include/jquery/jquery.flexigrid.1.0b3.pack.js"></script>
	{/literal}{$extension_jquery_files }{literal}
	<script type="text/javascript" src="./include/jquery/jquery.plugins.js"></script>
	<script type="text/javascript" src="./include/jquery/rte/jquery.rte.js"></script>
	<script type="text/javascript" src="./include/jquery/jquery.conf.js"></script>
	
	<link rel="stylesheet" type="text/css" href="./templates/default/css/flexigrid.css">
	<link rel="stylesheet" type="text/css" href="./include/jquery/jquery.plugins.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="./include/jquery/rte/rte.css" />	
	<link rel="stylesheet" type="text/css" href="./include/jquery/cluetip/jquery.cluetip.css" />


	
<script type="text/javascript" src="jquery.validate.js">
</script>
<script language="javascript">
	<!--

var demo_mode=false; // set to false to allow form submit

function checkFieldTask (aFieldName) {
	var bool = true;
	
	switch (aFieldName) 
	{
		case "name":
			if ($("#"+aFieldName).val()=="") {
				failField(aFieldName,"Please enter this Required field!");
				bool = false;
			} else {
				passField(aFieldName);
			}
			$(document).ready(function()
			{
   				document.frmpost.name.focus();
			}
			);
			break;
		case "description":
			if ($("#"+aFieldName).val()=="") {
				failField(aFieldName,"Please enter this Required field!");
				bool = false;
			} else {
				passField(aFieldName);
			}
			$(document).ready(function()
			{
   				document.frmpost.description.focus();
			}
			);
			break;	
		case "":
			
			break;
		
	}
	return bool;
}
function checkField (aField) {
	return checkFieldTask(aField.name);
}
function checkForm (aForm) {
	var bool = true;
	for (var i=0; i < aForm.elements.length; i++) {
		if (!checkFieldTask(aForm.elements[i].name)) {
			bool = false;
		}
	}
	if (bool) {
		passField("subbtn");
		if (demo_mode) {
			$("#frmpost").hide(250);
			$("#instructions").html('Good job. <a href="#" onclick="demoShowForm();return false;">Show Form Again</a>');
			return false;
		}
	} else {
		failField("subbtn","Please resolve issues first.");
	}
	return bool;
}
function passField (aFieldName) {
	$("#form_alert_"+aFieldName+"_msg").remove();
}
function failField (aFieldName,msg) {
	$("#form_alert_"+aFieldName+"_msg").remove(); // in case there are any from last time
	$("#"+aFieldName).after(alertMsgHTML(aFieldName,msg));
	offsetAlertDiv(aFieldName);
}
function alertMsgHTML (aFieldName, msg) {
	return '<div id="form_alert_'+aFieldName+'_msg" class="form_alert_msg">'+msg+'</div>';
}
function demoShowForm () {
	$("#instructions").text("Leave fields blank, then tab or submit, to see the validation in action.");
	$("#frmpost").show(250);
}

jQuery.fn._offset = jQuery.fn.offset;
jQuery.fn.extend({
    offset: function() {
        var a = arguments;
        return (a.length) ? this.animate({ top: a[0].top || a[0], left: a[0].left || a[1] }, (a[0].top ? a[1] : a[2]) || 1) : this._offset();
    }
});

function offsetAlertDiv (aFieldName) {
	//alert($("#"+aFieldName).offset().top);
	//alert($("#"+aFieldName).offset().left + $("#"+aFieldName).width()+10);
	//alert($("#form_alert_"+aFieldName+"_msg").offset().top);
	//alert($("#"+aFieldName).x);
	//$("#form_alert_"+aFieldName+"_msg").offset(50, 50);
	//$("#form_alert_"+aFieldName+"_msg").offset($("#"+aFieldName).offset());
	//$("#form_alert_"+aFieldName+"_msg").offset({top:$("#"+aFieldName).offset().top,left:$("#"+aFieldName).offset().left});
	//$("#form_alert_"+aFieldName+"_msg").offset($("#"+aFieldName).offset());
}


// -->
</script>

<style type="text/css">
<!--
.form_alert_msg {
	font-size: 14px;
	font-weight: bold;
	color: #ff4455;
	background-color: #ffffff;
	display:inline;
	padding: 2px 5px;
	border-top: 1px solid #000000;
	border-right: 2px solid #000000;
	border-bottom: 1px solid #000000;
	border-left: 2px solid #000000;
	margin-left: 10px;
}
-->
</style>


{/literal}

</head>
<body>
<div class="si_grey_background"></div>

