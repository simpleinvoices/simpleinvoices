<html>
<head>
<?xml version="1.0" encoding="utf-8" ?>
	<title>Simple Invoices</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<link rel="Stylesheet" href="templates/mobile/css/Render.css" />
		<script type="text/javascript" src="templates/mobile/WebApp/Action/Logic.js"></script>



<style>{literal}
			body[dir=rtl] #iHeader .iTab {margin-left:40px;margin-right:15px }
			#iHeader .iTab {margin-right:55px}

			.iTab li { width:33%}
			.iTab li:first-child { width:34%}

			.msg {
				background-color:#080;
				color:#fff;
				font-size:11px;
				padding:5px;
				-webkit-border-radius:4px;
				margin:8px;
			}
			.err {
				background-color:#800;
				color:#fff;
				font-size:11px;
				padding:5px;
				-webkit-border-radius:4px;
				margin:8px;
			}
			
		</style>
		<script type="text/javascript">

			function tabs(s) {
				WA.Header(!s, "tab1");
				return false;
			}
{/literal}
		</script>
</head>

<body -dir="rtl"><div id="WebApp">
<div id="loader" class="iItem" style="padding:10px 5px;font-weight:bold;font-size:12px;text-align:center;">
	<div style="font-size:20px">
		<a href="#" style="display:block;border-width: 0 12px;line-height:45px;-webkit-border-image: url(templates/mobile/WebApp/Design/Img/button-b-black.png) 0 12 0 12;margin:10px;color:white;text-decoration:none;text-align:center;text-shadow:#000 1px -1px 0;font-weight:bold">Test</a>
		<a href="#" style="display:block;border-width: 0 12px;line-height:45px;-webkit-border-image: url(templates/mobile/WebApp/Design/Img/button-b-white.png) 0 12 0 12;margin:10px;color:black;text-decoration:none;text-align:center;text-shadow:#fff 1px 1px 0;font-weight:bold">Test</a>
		<a href="#" style="display:block;border-width: 0 12px;line-height:45px;-webkit-border-image: url(templates/mobile/WebApp/Design/Img/button-b-red.png) 0 12 0 12;margin:10px;color:white;text-decoration:none;text-align:center;text-shadow:rgba(0,0,0,0.2) 1px -1px 0;font-weight:bold">Test</a>
	</div>
</div>
<div id="iHeader">
	<div class="iItem" id="tab1">
		<div class="iTab">
			<ul id="list">
				<li><a href="#">Simple</a></li>
				<li><a href="#">Image</a></li>
				<li><a href="./templates/mobile/Layer/async-tab.xml" rev="async"><span>Async</span></a></li>
			</ul>
		</div>
		<a href="#" class="iBAction iRightButton" onclick="return tabs(0)"><img src="templates/mobile/WebApp/Img/less.png" alt="Hide" /></a>
	</div>

	<a href="#" id="waBackButton">Back</a>
	<a href="#" id="waHomeButton">Home</a>
	<a href="#" onclick="return WA.HideBar()"><span id="waHeadTitle">Simple Invoices</span></a>

	
</div>