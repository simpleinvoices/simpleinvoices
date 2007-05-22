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