{*
/*
* Script: manage.tpl
* 	 Invoice Preferences manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
	<div class="si_toolbar si_toolbar_top">
            <a href="./index.php?module=preferences&amp;view=add" class="">
                <img src="./images/famfam/add.png" alt="" />
                {$LANG.add_new_preference}
            </a>
	</div>

{if $preferences == null}
	
	<div class="si_message">{$LANG.no_preferences}</div>
	
{else}

	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/preferences/manage.js.php'}

{/if}


<div class="si_help_div">
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{$LANG.whats_all_this_inv_pref}"><img src="{$help_image_path}help-small.png" alt="" /> {$LANG.whats_all_this_inv_pref} </a>
</div>
