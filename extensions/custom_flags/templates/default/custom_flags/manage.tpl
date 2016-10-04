{*
 *  Script: manage.php
 *      Custom flags manage page
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2016-08-04
 *
 *  License:
 *      GPL v3 or above
 *}
 <br/>
 <br/>
{if $cflgs == null}
<div class="si_message">{$LANG.no_custom_flags}</div>
{else}
<table id="manageGrid" style="display: none"></table>
{include file='../extensions/custom_flags/modules/custom_flags/manage.js.php'}
<div class="si_help_div">
  <a class="cluetip" href="#"
    rel="index.php?module=documentation&amp;view=view&amp;page=help_what_are_custom_flags"
    title="{$LANG.what_are_custom_flags}">{$LANG.what_are_custom_flags}
    <img src="{$help_image_path}help-small.png" alt="" />
  </a> :: <a class="cluetip" href="#"
    rel="index.php?module=documentation&amp;view=view&amp;page=help_manage_custom_flags"
    title="{$LANG.whats_this_page_about}">{$LANG.whats_this_page_about}
    <img src="{$help_image_path}help-small.png" alt="" />
  </a>
</div>
{/if}
