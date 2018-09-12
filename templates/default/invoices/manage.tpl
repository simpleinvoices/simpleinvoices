{*
 * Script: manage.tpl
 *      Manage invoices template
 *
 * License:
 *     GPL v2 or above
 *
 * Website:
 *    https://simpleinvoices.group
 *}

{if $number_of_invoices == 0}
  <div class="si_message">{$LANG.no_invoices}</div>
{else}
  <div class="si_filters_invoices si_buttons_manage_invoices">
    <span class='si_filters_title'>{$LANG.filters}:</span>
    <span class='si_filters_links'>
    <a href="index.php?module=invoices&amp;view=manage" 
       class="first{if $smarty.get.having==''} selected{/if}">
      {$LANG.all}
    </a>
    <a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed"
       class="{if $smarty.get.having=='money_owed'}selected{/if}">
      {$LANG.due}
    </a>
    <a href="index.php?module=invoices&amp;view=manage&amp;having=paid"
       class="{if $smarty.get.having=='paid'}selected{/if}">
      {$LANG.paid}
    </a>
    <a href="index.php?module=invoices&amp;view=manage&amp;having=draft"
       class="{if $smarty.get.having=='draft'}selected{/if}">
      {$LANG.draft}
    </a>
    <a href="index.php?module=invoices&amp;view=manage&amp;having=real" 
       class="{if $smarty.get.having=='real'}selected{/if}">
      {$LANG.real}
    </a>
  </span>
</div>
<div class="si_toolbar si_toolbar_top si_toolbar_top_left">
  <a href="index.php?module=invoices&amp;view=itemised" class="">
    <img src="images/common/add.png" alt="" />
    {$LANG.new_invoice}
  </a>
</div>
<table id="manageGrid" style="display:none"></table>
{include file='modules/invoices/manage.js.php'}
<div id="export_dialog" class="flora" title="Export">
  <div class="si_toolbar si_toolbar_dialog">
    <a title='{$LANG.export_tooltip} {$LANG.export_pdf_tooltip}'
       class='export_pdf export_window'><img src="images/common/page_white_acrobat.png" alt="" />
      {$LANG.export_pdf}
    </a>
    <a title='{$LANG.export_tooltip} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet}'
       class='export_xls export_window'><img src="images/common/page_white_excel.png" alt="" />
      {$LANG.export_xls}
    </a>
    <a title='{$LANG.export_tooltip} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor}'
       class='export_doc export_window' ><img src="images/common/page_white_word.png" alt="" />
      {$LANG.export_doc}
    </a>
  </div>
</div>
{/if}
