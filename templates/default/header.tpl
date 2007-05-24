<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<title>Simple Invoices</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">
    var GB_ROOT_DIR = "./modules/include/js/";
</script>

	<link rel="stylesheet" type="text/css" href="include/jquery/jquery.autocomplete.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="include/jquery/jquery.datePicker.css" title="default" media="screen" />
	<link rel="stylesheet" type="text/css" href="./templates/default/css/header.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./templates/default/css/screen.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="./templates/default/css/print.css" media="print"/>


<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
<script type="text/javascript" src="include/jquery/jquery.js"></script>
<script type="text/javascript" src="include/jquery/jquery.dom_creator.js"></script>
<script type="text/javascript" src="include/jquery/jquery.datePicker.js"></script>
<script type="text/javascript" src="include/jquery/jquery.datePicker.conf.js"></script>
<script type='text/javascript' src='include/jquery/jquery.autocomplete.js'></script>
<script type='text/javascript' src='include/jquery/jquery.autocomplete.conf.js'></script>
<script type="text/javascript" src="./include/jquery/jquery.accordian.js"></script>
<script type="text/javascript" src="./include/jquery/jquery.tabs.js"></script>

{literal}
	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="./temlates/default/css/tabs-ie.css" type="text/css" media="projection, screen" />
	<![endif]-->

	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<style type="text/css" media="screen, projection">
	    body {
		font-size: 100%; /* resizable fonts */
	    }
	</style>
	<![endif]-->

	<script type="text/javascript">//<![CDATA[
	    $(document).ready(function() {
		$('#container-1').tabs();
		$('#trigger-tab').after('<p><a href="#" onclick="$(\'#container-1\').triggerTab(3); return false;">Activate third tab</a></p>');
		$('#custom-tab-by-hash').title('New window').click(function() {
		    var win = window.open(this.href, '', 'directories,location,menubar,resizable,scrollbars,status,toolbar');
		    win.focus();
		});
	    });
	//]]></script>
	
{/literal}


<!-- customer-details -->
<link rel="stylesheet" href="./templates/default/css/tabs.css" type="text/css" media="print, projection, screen" />


<script type="text/javascript" src="./modules/include/js/AJS.js"></script>
<script type="text/javascript" src="./modules/include/js/AJS_fx.js"></script>
<script type="text/javascript" src="./modules/include/js/gb_scripts.js"></script>
<link href="./templates/default/css/gb_styles.css" rel="stylesheet" type="text/css" />

<!--[if gte IE 5.5]>
<script language="JavaScript" src="./modules/include/js/dhtml.js" type="text/JavaScript"></script>
<link rel="stylesheet" href="./templates/default/css/iehacks.css" type="text/css" />
<![endif]-->



<body>
