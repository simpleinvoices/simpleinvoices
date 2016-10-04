{capture name="hook_topmenu_section01_replace"}
  <div class="si_wrap">
    {$LANG.hello} {$smarty.session.Zend_Auth.username|htmlsafe} |
    <a href="http://www.simpleinvoices.org/help" target="blank">{$LANG.help}</a>
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
