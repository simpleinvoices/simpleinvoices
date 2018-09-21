{strip}
{* ************************************************************************
  SI "hooks" allow content to be inserted into or to replace predefined
  locations in the default header.tpl, menu.tpl and footer.tpl files.
  
  Before you consider using hooks, make sure other options such as tpl file
  content insertion be used before resorting to hooks (see Custom Flags
  extension menu.tpl file or Past Due Report extension index.tpl file for
  examples). The reason for this is that a hook can only be used once since
  the Smarty {capture name="hook_name"}{/capture} function is in essence a
  variable in which values to be inserted are saved. So if the same hook
  (capture name value) is defined multiple times then only the value of the
  last one set will be used.
  
  This file contains the list of all defined hooks. Note the name of the hook
  as it will tell you if its value will be inserted in the code or if it will
  replace a section of code. If the hook name contains the word, "replace",
  defining it will replace the named section of the tpl file it is defined
  in. All other hooks are insertion points and have names that indicate
  "start" or "end" of the named location.
  
  All hooks should be defined in this file to document their existence. They
  should be surrounded by a test to see if they have previously been set;
  preventing them from being inadvertantly redefined in this file.
  
  Note that this file is loaded after the extension hooks have been loaded.
  This allows hooks to be conditionally used by extensions that a user has
  enabled. Hooks in this file should only be used if needed all the time.
  But consider, if it is needed all the time, it should probably be put in
  the file where the hook is loaded rather than being here.
  ************************************************************************ *}

{* In header.tpl - Immediately after the <head> tag ********************** *}
{if $smarty.capture.hook_head_start eq ""}
  {capture name=hook_head_start}
    {* Insert code here *}
  {/capture}
{/if}

{* In header.tpl - Immediately before the </head> tag ******************** *}
{if $smarty.capture.hook_head_end eq ""}
  {capture name=hook_head_end}
    {* Insert code here *}
  {/capture}
{/if}

{* In header.tpl - Immediately after the <body> tag ********************** *}
{if $smarty.capture.hook_body_start eq ""}
  {capture name=hook_body_start}
    {* Insert code here *}
  {/capture}
{/if}

{* In menu.tpl - Immediately after the <div id="si_header"> ************** *}
{if $smarty.capture.hook_topmenu_start eq ""}
  {capture name=hook_topmenu_start}
    {* Insert code here *}
  {/capture}
{/if}

{* In menu.tpl - Immediately after the previous topmenu_start hook ******* *}
{if $smarty.capture.hook_topmenu_section01_replace eq ""}
  {capture name=hook_topmenu_section01_replace}
    {* Insert code here *}
  {/capture}
{/if}
 
{* In menu.tpl - Immediately before the </div> for this section ********** *}
{if $smarty.capture.hook_topmenu_end eq ""}
  {capture name=hook_topmenu_end}
    {* Insert code here  *}
  {/capture}
{/if}

{* In menu.tpl - Immediately after the
                   <div id="tabmenu" class="flora si_wrap" > tag ********* *}
{if $smarty.capture.hook_tabmenu_start eq ""}
  {capture name=hook_tabmenu_start}
    {* Insert code here *}
  {/capture}
{/if}

{* In menu.tpl - Immediately before the </div> tag that closes the
                   <div id="tabmenu" class="flora si_wrap" > tag ********* *}
{if $smarty.capture.hook_tabmenu_end eq ""}
  {capture name=hook_tabmenu_end}
    {* Insert code here *}
  {/capture}
{/if}

{* In menu.tpl - Immediately after the <ul> tag for the main tab menu **** *}
{if $smarty.capture.hook_tabmenu_main_start eq ""}
  {capture name=hook_tabmenu_main_start}
    {* Insert code here *}
  {/capture}
{/if}

{* In menu.tpl - Immediately before the <li id="si_tab_settings"></ul>
                   tags at the end of the main tab menu list. ************ *}
{if $smarty.capture.hook_tabmenu_main_end eq ""}
  {capture name=hook_tabmenu_main_end}
    {* Insert code here *}
  {/capture}
{/if}

{* In footer.tpl - Immediately before the </body> tag ******************** *}
{if $smarty.capture.hook_body_end eq ""}
  {capture name=hook_body_end}
    {* Insert code here *}
  {/capture}
{/if}

{/strip}