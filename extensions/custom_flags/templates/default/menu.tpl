<!-- BEFORE:tax_rates -->
<li>
  <a {if $pageActive == "custom_flags"} class="active"{/if} href="index.php?module=custom_flags&amp;view=manage">
    {$LANG.custom_flags_upper}
  </a>
</li>
{if $subPageActive == "custom_flags_view"}
  <li>
    <a class="active active_subpage" href="#">
      {$LANG.view}
    </a>
  </li>
{/if}
{if $subPageActive == "custom_flags_edit"}
  <li>
    <a class="active active_subpage" href="#">
      {$LANG.edit}
    </a>
  </li>
{/if}
