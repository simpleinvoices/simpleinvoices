<!-- BEFORE:recurrence -->
<li><a {if $pageActive== "expense"}class="active" {/if}href="index.php?module=expense&amp;view=manage">{$LANG.expenses}</a></li>
{ if $pageActive == "expense"}
  { if $subPageActive == "edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
  { if $subPageActive == "view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
  { if $subPageActive == "add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
{/if}
<li><a {if $pageActive== "expense_account"}class="active" {/if}href="index.php?module=expense_account&amp;view=manage">{$LANG.expense_accounts}</a></li>
{ if $pageActive == "expense_account"}
  { if $subPageActive == "edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
  { if $subPageActive == "view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
  { if $subPageActive == "add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
{ /if }
