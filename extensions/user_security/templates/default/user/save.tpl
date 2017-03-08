{*
 * Script: save.tpl
 *   User save template
 *
 * Authors:
 *  Justin Kelly, Nicolas Ruflin, Soif
 *  Rich Rowley
 *
 * Last edited:
 *    2016-05-28
 *
 * License:
 *  GPL v3 or above
 *}
{if $saved == true }
  <div class="si_message_ok">{$LANG.save_user_success}</div>
{elseif $confirm_error == null}
  <div class="si_message_error">{$LANG.save_user_failure}</div>
{else}
  <div class="si_message_error">{$confirm_error}</div>
{/if}

{if $smarty.post.cancel == null }
  <meta http-equiv="refresh" content="2;URL=index.php?module=user&view=manage" />
{else}
  <meta http-equiv="refresh" content="0;URL=index.php?module=user&view=manage" />
{/if}
