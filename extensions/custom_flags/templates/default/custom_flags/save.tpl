{*
/*
* Script: save.tpl
* 	Custom flags save page
*
* Authors:
*  Richard Rowley
*
* Last edited:
*    2015-09-23
*
* License:
*  GPL v3 or above
*/
*}

{if $saved == true }
  <br />
   {$LANG.save_custom_flags_success}
  <br />
  <br />
{else}
  <br />
   {$LANG.save_custom_flags_failure}
  <br />
  <br />
{/if}

{if $smarty.post.cancel == null }
  <meta http-equiv="refresh" content="2;URL=index.php?module=custom_flags&view=manage" />
{else}
  <meta http-equiv="refresh" content="0;URL=index.php?module=custom_flags&view=manage" />
{/if}
