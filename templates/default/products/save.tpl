{*
 * Script: save.tpl
 *   Biller save template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *    2016-08-10
 *
 * License:
 *   GPL v3 or above
 *}
{if $saved == true }
  <div class="si_message_ok">{$LANG.save_product_success}</div>
{else}
  <div class="si_message_error">{$LANG.save_product_failure}</div>
{/if}

{if $smarty.post.cancel == null }
  <meta http-equiv="refresh" content="2;URL=index.php?module=products&view=manage" />
{else}
  <meta http-equiv="refresh" content="0;URL=index.php?module=products&view=manage" />
{/if}
