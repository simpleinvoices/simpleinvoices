	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="./include/css/tabs-ie.css" type="text/css" media="projection, screen" />
	<![endif]-->
	<style type="text/css" media="screen, projection">
	    /* just to make this demo look a bit better */
h4 {
	margin:0;
	padding:0px;
}
	    ul {
		list-style: none;
		
	    }
	    body>ul>li {
		display: inline;
	    }
	    body>ul>li:before {
		content: ", ";
	    }
	    body>ul>li:first-child:before {
		content: "";
	    }
	</style>
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