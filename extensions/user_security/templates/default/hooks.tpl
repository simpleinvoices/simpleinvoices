{capture name="hook_topmenu_section01_replace"}
  <div class="si_wrap">
    <!-- SECTION:help -->
    {$LANG.hello} {$smarty.session.Zend_Auth.username|htmlsafe} |
    <a href="https://simpleinvoices.group/doku.php?id=si_wiki:menu target="blank">{$LANG.help}</a>
    <!-- SECTION:auth -->
    {if $config->authentication->enabled == 1} |
      {if $smarty.session.Zend_Auth.id == null}
        <a href="index.php?module=auth&amp;view=login">{$LANG.login}</a>
      {else}
        <a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a>
        {if $smarty.session.Zend_Auth.domain_id != 1} | Domain: {$smarty.session.Zend_Auth.domain_id}{/if}
      {/if}
    {/if}
  </div>
{/capture}
