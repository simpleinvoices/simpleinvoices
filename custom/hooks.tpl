{strip}

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
	<!-- HOOK_head_end -->
{/capture}


{* Inside <BODY></BODY> --------------------------------------------------------------- *}

{capture name=hook_body_start}
	<!-- HOOK_body_start -->
{/capture}

{capture name=hook_body_end}
	<!-- HOOK_body_end -->
{/capture}


{* Inside Top Menu DIV ---------------------------------------------------------------- *}

{capture name=hook_topmenu_start}
	<!-- HOOK_topmenu_start -->
{/capture}

{capture name=hook_topmenu_end}
	<!-- HOOK_topmenu_end -->
{/capture}

{* Inside Tabs Menu DIV --------------------------------------------------------------- *}
{capture name=hook_tabmenu_start}
	<!-- HOOK_tabmenu_start -->
{/capture}

{capture name=hook_tabmenu_end}
	<!-- HOOK_tabmenu_end -->
{/capture}


{* Inside Main Tabs Menu UL  ---------------------------------------------------------- *}

{capture name=hook_tabmenu_main_start}
	<!-- HOOK_tabmenu_main_start -->
{/capture}

{capture name=hook_tabmenu_main_end}
	<!-- HOOK_tabmenu_main_end -->
{/capture}


{/strip}