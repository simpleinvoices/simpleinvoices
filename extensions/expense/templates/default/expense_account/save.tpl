{*
 * Script: save.tpl
 *  Biller save template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2007-07-29
 *
 * License:
 *   GPL v3 or above
 *}
{if $saved == true }
  <br />
  {$LANG.save_expense_account_success}
  <br />
  <br />
  {else}
  <br />
  {$LANG.save_expense_account_failure}
  <br />
  <br />
{/if}
{if $smarty.post.cancel == null }
  <meta http-equiv="refresh" content="2;URL=index.php?module=expense_account&view=manage" />
{else}
  <meta http-equiv="refresh" content="0;URL=index.php?module=expense_account&view=manage" />
{/if}
