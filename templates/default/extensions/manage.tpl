{*
/*
* Script: manage.tpl
* 	 Extensions manage template
*
* Authors:
*	 Justin Kelly, Ben Brown, Marcel van Dorp
*
* Last edited:
* 	 2009-02-12
*
* License:
*	 GPL v2 or above
*/
*}
    <div class="si_message">
        Note: Manage extensions is still a work-in-progress
    </div>

{if $exts == null}

	<p><em>No extensions registered</em></p>

{else}

	<table id="manageGrid" style="display:none"></table>

	{include file='../templates/default/extensions/manage.js.tpl'}
 
{/if}
