
{* 	######################################################################################################################################
	You can use these "hooks" to add your custom code in some strategic places, without having to override any template file.
	HowTo: Just replace the content of each capture tag with your own HTML parts, ie: {capture nane=xxxx}<!-- Your html code here -->{/capture}
	Tip: Search the following  <!-- HOOK_*** -->  comments in the HTML source code or any page to discover where these hooks are placed.
	######################################################################################################################################  *}


{* Inside <HEAD></HEAD> --------------------------------------------------------------- *}

{capture name=hook_head_start}
	<!-- HOOK_head_start -->
{/capture}

{capture name=hook_head_end}

	<link rel="stylesheet" type="text/css" href="custom/my_medias/my.css" />

{/capture}


{* Inside <BODY></BODY> --------------------------------------------------------------- *}

{capture name=hook_body_start}

	<div id='my_header'><div id='my_logo'></div>My Header</div>

{/capture}


{capture name=hook_body_end}

		<div id='my_footer'>My Footer</div>

{/capture}


{* Inside Top Menu DIV ---------------------------------------------------------------- *}

{capture name=hook_topmenu_start}

	<div id="my_topmenu">
		<div class="my_topmenu my_topmenu_left">
			<a href="http://www.linux.org">My TopMenu</a>				
		</div>
		<div class="my_topmenu my_topmenu_right2">
			<a href="http://www.linux.org"> And Also Here</a>				
		</div>

{/capture}

{capture name=hook_topmenu_end}

		<div class="my_topmenu my_topmenu_right1">
			<a href="http://www.linux.org">Also Here</a>				
		</div>
	</div>

	<!-- HOOK_topmenu_end -->
{/capture}

{* Inside Tabs Menu DIV --------------------------------------------------------------- *}
{capture name=hook_tabmenu_start}

{/capture}

{capture name=hook_tabmenu_end}

	<div id="my_menu2">
		<ul class="subnav">
			<li><a  href="http://kernel.org">Kernel</a></li>
			<li><a  href="http://ubuntu.org">ubuntu</a></li>
		</ul>
	</div>
	<div id="my_settings">
		<ul class="subnav">
			<li><a  href="http://kernel.org">My setting link</a></li>
		</ul>
	</div>

{/capture}


{* Inside Main Tabs Menu UL  ---------------------------------------------------------- *}

{capture name=hook_tabmenu_main_start}
{/capture}

{capture name=hook_tabmenu_main_end}
		<li><a href="#my_menu2"><span>MyLastTab</span></a></li>

		<li style="float:right" class="menu_setting"><a href="#my_settings"><span>MySettings</span></a></li>
{/capture}
