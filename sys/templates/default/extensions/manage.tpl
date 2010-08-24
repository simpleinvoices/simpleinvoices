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
<br />
    <span class="welcome">
        Note: Manage extensions is still a work-in-progress
    </span>
<br />
<br />
{if $exts == null}
<p><em>No extensions registered</em></p>
{else}
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/extensions/manage.js.php'}
 
{/if}
