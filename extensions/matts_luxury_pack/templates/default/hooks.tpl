{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/hooks.tpl
 * 	Put code into sections via code hooks
 *
 * Authors:
 *	git0matt@gmail.com
 *
 * Last edited:
 * 	2016-09-10
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
{strip}
{assign var=inc value=$smarty.template|dirname}
{assign var=pos value=$inc|strrpos:'/':-4}
{assign var=pth value=$inc|substr:0:$pos}
{assign var=mypos value=$smarty.template|strrpos:'/'}
{assign var=myroot value=$smarty.template|substr:1:$mypos-1}


{*	$pth|cat:"/hooks/head_end.php"	*}
{*	$smarty->fetch("/hooks/head_end.php")	*}
{*	php}file_get_contents('{$inc|cat:"/hooks/head_end.php"}');{/php	*}

{*	hook_head_end	*}
{capture name="hook_head_end"}{literal}
	<script type="text/javascript" src="{/literal}{$myroot|cat:"/../../include/jquery/xml2json.js"}{literal}"></script>
	{/literal}
{if $defaults.use_modal}
<!-- hook_head_end_add_superbox : {$LANG.Modal} -->{literal}
	<link rel="stylesheet" href="{/literal}{$myroot|cat:"/../../include/jquery/jquery.superbox.css"}{literal}" type="text/css" media="all" />
	<script type="text/javascript" src="{/literal}{$myroot|cat:"/../../include/jquery/jquery.superbox-min.js"}{literal}"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$(function(){
			$.superbox.settings = {
				closeTxt: '<img src="{/literal}{$myroot|cat:"/../../images/common/back-arrow24.png"}{literal}" />',
				loadTxt: "{/literal}{if $LANG.loading}{$LANG.loading|htmlsafe}{else}Loading{/if}{literal}..."
			};
			$.superbox();
		});
		function closeModal()
		{
			$("P.close A").click();
		}
	});
	//-->
	</script>
	<script type="text/javascript"><!--
	function inIframe ()
	{
		try {
			return window.self !== window.top;
		} catch (e) {
			return true;
		}
	}
	if (inIframe())
	{
		document.write('<link rel="stylesheet" type="text/css" href="{/literal}{$myroot|cat:"/css/iframe.css"}{literal}">');
	}
	//-->
	</script>{/literal}
<!-- end hook_head_end_add_superbox -->
{/if}

{literal}

<script type="text/javascript"><!--
/* add prototype called indexOf, if not exists */
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function (obj, fromIndex) {
		if (fromIndex == null) {
			fromIndex = 0;
		} else if (fromIndex < 0) {
			fromIndex = Math.max(0, this.length + fromIndex);
		}
		for (var i = fromIndex, j = this.length; i < j; i++) {
			if (this[i] === obj)
				return i;
		}
		return -1;
	};
}

//eg. var myExtScriptArray = [ {"count":"0", "name":"GA", "src":"js/google-analytics.js"}, {"count":"1", "name":"jQuery-plugin-xyz", "src":"js/jquery/xyz.js"} ];
//eg2.	var myExtLinkArray = [ {"count":"0", "name":"-", 			"./noname"} ];
	var myArray = {		/* arrays of external files to load */
		"scripts":	[	{"count":"0", "name":"-", 			"src":"./noname"}
					, {"count":"1", 	"name":"jquery-1.2.6", 		"src":"./include/jquery/jquery-1.2.6.min.js"}
					, {"count":"2", 	"name":"jquery.init", 		"src":"./include/jquery/jquery.init.js"}
					, {"count":"3", 	"name":"jquery-ui-p-1.6", 	"src":"./include/jquery/jquery-ui-personalized-1.6rc2.packed.js"}
					, {"count":"4", 	"name":"cluetip-hi", 		"src":"./include/jquery/cluetip/jquery.hoverIntent.minified.js"}
					, {"count":"5", 	"name":"cluetip", 			"src":"./include/jquery/cluetip/jquery.cluetip.js"}
					, {"count":"6", 	"name":"flexigrid1.0b3", 	"src":"./include/jquery/jquery.flexigrid.1.0b3.pack.js"}
					, {"count":"7", 	"name":"jquery.plugins", 	"src":"./include/jquery/jquery.plugins.js"}
					, {"count":"8", 	"name":"wysiwyg-mod", 		"src":"./include/jquery/wysiwyg/wysiwyg.modified.packed.js"}
					, {"count":"9", 	"name":"livequery-pack",	"src":"./include/jquery/jquery.livequery.pack.js"}

					, {"count":"10", 	"name":"blackbird", 		"src":"./library/blackbirdjs/blackbird.js"}
					, {"count":"11", 	"name":"validationEngine", 	"src":"./include/jquery/jquery.validationEngine.js"}	]
		,"links":	[	{"count":"0", "name":"./noname"}
					, {"count":"1", 	"name":"blackbird", 	"href":"./library/blackbirdjs/blackbird.css"}
		]	};
	var doneArray = {	"scripts":	[],	"links":	[]	};

	function loadMyExtScriptArray(myarray, arraydone)		/* parse the array of scripts, mark done */
	{
		var output = Array();
		for (var i=1; i<myarray.length; i++) {
/*			if (arraydone.indexOf(myarray[i].name) != -1)
				continue;
			else
				arraydone[] = myarray[i].name;	*/
			output[i] = document.createElement("script");
			if (!myarray[i].type || myarray[i].type=="text/javascript" || myarray[i].type=="")
				output[i].type="text/javascript";
			if (myarray[i].title)
				output[i].title = myarray[i].title;
			if (myarray[i].id)
				output[i].id = myarray[i].id;
			if (myarray[i].className)
				output[i].className = myarray[i].className;
			output[i].src = myarray[i].src;
			document.head.appendChild(output[i]);
		}
	}

	function loadMyExtLinkArray(myarray, arraydone)		/* parse the array of links, mark done */
	{
		var output = Array();
		for (var i=1; i<myarray.length; i++) {
/*			if (arraydone.indexOf(myarray[i].name) != -1)
				continue;
			else
				arraydone[] = myarray[i].name;	*/
			output[i] = document.createElement("link");
			if (!myarray[i].rel || myarray[i].rel=="stylesheet" || myarray[i].rel=="")
				output[i].rel = "stylesheet";
			if (!myarray[i].type || myarray[i].type=="text/css" || myarray[i].type=="")
				output[i].type = "text/css";
			if (myarray[i].title)
				output[i].title = myarray[i].title;
			if (myarray[i].media=="screen" || myarray[i].media=="")
				output[i].media = myarray[i].media;
			if (myarray[i].id)
				output[i].id = myarray[i].id;
			if (myarray[i].lang)
				output[i].lang = myarray[i].lang;
			if (myarray[i].className)
				output[i].className = myarray[i].className;
			if (myarray[i].dir)
				output[i].dir = myarray[i].dir;
			output[i].href = myarray[i].href;
			document.head.appendChild(output[i]);
		}
	}

	function downloadJSAtOnload()		/* main - pass arrays to functions */
	{
/*		if (myArray) {
			document.write('<!-- real defered-begin -->');
			if (myArray.scripts.length)
				loadMyExtScriptArray(myArray.scripts, doneArray.scripts);
			if (myArray.links.length)
				loadMyExtLinkArray(myArray.links, doneArray.links);
			document.write('<!-- real defered-end -->');
		}	*/
	}

	if (window.addEventListener)		/* call main function after page load */
		window.addEventListener("load", downloadJSAtOnload, false);
	else if (window.attachEvent)
		window.attachEvent("onload", downloadJSAtOnload);
	else window.onload = downloadJSAtOnload;

/*
	function onReady(callback) {
		var intervalID = window.setInterval(checkReady, 1000);
		function checkReady() {
			if (document.getElementsByTagName('body')[0] !== undefined) {
				window.clearInterval(intervalID);
				callback.call(this);
			}
		}
	}

	function show(id, value) {
		document.getElementById(id).style.display = value ? 'block' : 'none';
	}

	onReady(function () {
		show('Container', true);
		show('pageLoading', false);
	});
*/
//-->{/literal}</script>
{/capture}


{*	capture name="hook_head_end"}
{php}file_get_contents("{$inc|cat:"/hooks/head_end.1.php"}");{/php}
{/capture	*}


{*hook_head_end*}
{*assign var=mypos value=$smarty.template|strrpos:'/'}
{assign var=myroot value=$smarty.template|substr:1:$mypos-1*}
{*	capture name="hook_head_end"}
{php}file_get_contents("{$inc|cat:"/hooks/head_end.2.php"}");{/php}
{if $defaults.use_modal}
<!-- hook_head_end_add_nyroModal : {$LANG.Modal} -->{literal}
	<link rel="stylesheet" href="{/literal}{$myroot|cat:"/../../include/jquery/nyroModal.css"}{literal}" type="text/css" media="all" />
	<script type="text/javascript" src="{/literal}{$myroot|cat:"/../../include/jquery/jquery.nyroModal.custom.min.js"}{literal}"></script>

	<script type="text/javascript"><!--
	$(document).ready(function(){
		$(function() {
			$('.modal').nyroModal();
		});
	});
	//-->
	</script>{/literal}
<!-- end hook_head_end_add_nyroModal -->
{/if}
{/capture	*}


{*assign var=mypos value=$smarty.template|strrpos:'/'}
{assign var=myroot value=$smarty.template|substr:1:$mypos-1*}
{*	capture name="hook_head_link"}
{php}file_get_contents("{$inc|cat:"/hooks/head_link.php"}");{/php}
{if $defaults.use_modal}
{literal}
	<link rel="stylesheet" href="{/literal}{$myroot|cat:"/css/iframe.css"}{literal}" type="text/css" media="all" />
{/literal}
{/if}
{/capture	*}


{capture name="hook_body_end"}
{*	php}file_get_contents("{$inc|cat:"/hooks/body_end.php"}");{/php	*}
{*	hook_body_end	*}
{*	$smarty.capture.hook_body_end	*}{*	append	*}
{if $defaults.use_modal}
{literal}
<!--	<script type="text/javascript"><!- -
		$('.superbox-iframe').livequery('load',function(){
/*			$(iframe.contentWindow.document.body).find("#cancelAddCustomer").livequery('click',function (e) { */
			$(this).contents().find("#cancelAddCustomer").livequery('click',function (e) {\
				if (inIframe())
				{
					e.preventDefault();
					$.superbox.close();
					//$(iframe.contentWindow.document.body).find('#sb-close').trigger('click');
				}
			});
/*			$(iframe.contentWindow.document.body)	*/
			$(this).contents().find("#cancelEditCustomer").livequery('click',function (e) { 
				if (inIframe())
				{
					e.preventDefault();
					//$.superbox.close();
/*				$(iframe.contentWindow.document.body)	*/
					$(this).contents().find('#sb-close').trigger('click');
				}
			});
/*			$(iframe.contentWindow.document.body)	*/
			$(this).contents().find("#cancelAddProduct").livequery('click',function (e) { 
				if (inIframe())
				{
					e.preventDefault();
					$.superbox.close();
					//$(iframe.contentWindow.document.body).find('#sb-close').trigger('click');
				}
			});
		});
/*	if (inIframe())
	{
		var elem = document.getElementById("cancelAddCustomer");//(iframe.contenWindow.document.body).find
		if (elem.addEventListener){
			elem.addEventListener("click", function() { elem.preventDefault(); $.superbox.close(); document.getElementById("sb-close").trigger('click'); }, false);
		} else if (elem.attachEvent){
			elem.attachEvent("onclick", function() { elem.preventDefault(); $.superbox.close(); document.getElementById("sb-close").trigger('click'); });
		}

		var elem = document.getElementById("cancelEditCustomer");//(iframe.contenWindow.document.body).find
		if (elem.addEventListener){
			elem.addEventListener("click", function() { elem.preventDefault(); $.superbox.close(); closeModal();/ *$('#sb-close').trigger('click');* / }, false);
		} else if (elem.attachEvent){
			elem.attachEvent("onclick", function() { elem.preventDefault(); $.superbox.close(); closeModal();/ *$('#sb-close').trigger('click');* / });
		}

		var elem = document.getElementById("cancelAddProduct");//(iframe.contenWindow.document.body).find
		if (elem.addEventListener){
			elem.addEventListener("click", function() { elem.preventDefault(); / *document.getElementById* /$('#sb-close').trigger('click'); }, false);
		} else if (elem.attachEvent){
			elem.attachEvent("onclick", function() { elem.preventDefault(); / *document.getElementById* /$('#sb-close').trigger('click'); });
		}
	}*/
	//- - >
	</script>{/literal}-->
{/if}
{*	/if	*}
{/capture}


{*	capture name="hook_topmenu_section01_replace"}
{php}file_get_contents("menu.tpl");{/php}
{/capture	*}


{*	capture name="hook_body_end"}
{php}file_get_contents("{$inc|cat:"/hooks/body_end.1.php"}");{/php}
{ *	hook_body_end	* }
<script type="text/javascript">
	function downloadJSAtOnload()
	{
		var element = document.createElement("script");
		element.src = "defer.js";
		document.body.appendChild(element);
	}
	if (window.addEventListener)
		window.addEventListener("load", downloadJSAtOnload, false);
	else if (window.attachEvent)
		window.attachEvent("onload", downloadJSAtOnload);
	else window.onload = downloadJSAtOnload;
</script>


<script type="text/javascript">
{php}
	$OUTTime = $_SERVER['REQUEST_TIME'] + 1800;
	//$OUTTime = $TimeOUT + 1800; // calculate timeout from session. $TimeOUT was registered in the session
	$date = date("M d  Y H:i:s",$OUTTime);
{/php}
function getTime() { <!-- counting backwards and reLoad if session has expired -->
	now = new Date();   <!-- current Time -->
	later = new Date("<?=$date?>"); <!-- TimeOUT of session -->

	days = (later - now) / 1000 / 60 / 60 / 24;
	var daysRound = Math.floor(days);
	hours = (later - now) / 1000 / 60 / 60 - (24 * daysRound);
	var hoursRound = Math.floor(hours);

	minutes = (later - now) / 1000 / 60 - (24 * 60 * daysRound) - (60 * hoursRound);
	var minutesRound = Math.floor(minutes);
	if(minutesRound < 10){ minutesRound = "0" + minutesRound; } 
 
	seconds = (later - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound) - 1;
	var secondsRound = Math.round(seconds);
	if(secondsRound == 60){ secondsRound = "00"; }    <!-- Bug -->
	if(secondsRound < 10){ secondsRound = "0" + secondsRound; }  <!-- Bug -->
	if(secondsRound == "000"){ secondsRound = "00"; }   <!-- Bug -->
	if(secondsRound == "0"){ secondsRound = "00"; }   <!-- Bug -->
	if(secondsRound == "0-1"){ <!-- Bug -->
		secondsRound = "59"; 
		minutesRound = minutesRound - 1;
	}
/*
	var Time1 = minutesRound + ":" + secondsRound;
	if (Time1 == "00:00"){ <!-- RELOAD window if session-time has expired -->
		window.parent.location.reload(); 
	}
*/
	window.parent.status = "Time to expire: " + minutesRound + ":" + secondsRound;
	newtime = window.setTimeout("getTime();", 1000);
}
$(document).ready(function(){	getTime()	});
</script>


<!--
<div class="modal fade" id="logout_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div style="width:100%;height:100%;margin: 0px; padding:0px">
					<div style="width:25%;margin: 0px; padding:0px;float:left;">
						<i class="fa fa-warning" style="font-size: 140px;color:#da4f49"></i>
					</div>
					<div style="width:70%;margin: 0px; padding:0px;float:right;padding-top: 10px;padding-left: 3%;">
						<h4>Your session is about to expire!</h4>
						<p style="font-size: 15px;">You will be logged out in <span id="timer" style="display: inline;font-size: 30px;font-style: bold">10</span> seconds.</p>				
						<p style="font-size: 15px;">Do you want to stay signed in?</p>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div style="margin-left: 30%;margin-bottom: 20px;margin-top: 20px;">
				<a href="javascript:;" onclick="resetTimer()" class="btn btn-primary" aria-hidden="true">Yes, Keep me signed in</a>
				<a href="<?php echo BASE_PATH.'logout.php';?>" class="btn btn-danger" aria-hidden="true">No, Sign me out</a>
			</div>
		</div>
	</div>
</div>
-->
{/capture	*}


{capture name="hook_body_start"}
{*	<div id="pageLoading"></div>*}
{*	<a id="dofuncts" onclick="downloadJSAtOnload()"> CLICK ME </a>	*}
{/capture}


{capture name="hook_loading"}
<div id="gmail_loading" class="gmailLoader si_hide" style="display: none;"></div><!-- style="display: none;"-->
{*<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;"><img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." /> {$LANG.loading} ...</div>*}
{/capture}
{/strip}
