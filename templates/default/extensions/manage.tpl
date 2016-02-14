{*
 * Script: manage.tpl
 *   Extensions manage template
 *
 * Authors:
 *   Justin Kelly, Ben Brown, Marcel van Dorp
 *
 * Last edited:
 *   2009-02-12
 *
 * License:
 *   GPL v2 or above
 *}
<div class="si_message">
    Note: Manage extensions is still a work-in-progress
</div>
error_log("manage.tpl line 19");
{if $exts == null}
error_log("manage.tpl line 21");
  <p><em>No extensions registered</em></p>
{else}
error_log("manage.tpl line 25");
  <table id="manageGrid" style="display:none"></table>
error_log("manage.tpl line 27");
  {include file='../modules/extensions/manage.js.php'}
{/if}
