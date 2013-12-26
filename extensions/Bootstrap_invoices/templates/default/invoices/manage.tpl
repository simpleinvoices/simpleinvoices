{*

/*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/

*}





{if $number_of_invoices.count == 0}
	<h1 class="title">{$LANG.invoices}
		<a class="btn btn-default" href="index.php?module=invoices&amp;view=itemised" class=""><span class="glyphicon glyphicon-plus"></span>{$LANG.new_invoice}</a>
    </h1>
	<div class="si_message">
		{$LANG.no_invoices}
	</div>

{else}
	<div class="clearfix">
	<h1 class="pull-left title">{$LANG.invoices}
		<a class="btn btn-default" href="index.php?module=invoices&amp;view=itemised" class=""><span class="glyphicon glyphicon-plus"></span>{$LANG.new_invoice}</a>
    </h1>
	<ul class="pull-right pagination">
		<li class="disabled">
			<span>{$LANG.filters}:</span>
		</li>
	  	<li class="{if $smarty.get.having==''}active{/if}">
	  		<a href="index.php?module=invoices&amp;view=manage">
	  			{$LANG.all} <span class="sr-only">(current)</span>
	  		</a>
	 	</li>
	  	<li class="{if $smarty.get.having=='money_owed'}active{/if}">
	  		<a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed">
	  			{$LANG.due} <span class="sr-only">(current)</span>
	  		</a>
	 	</li>
	 	<li class="{if $smarty.get.having=='paid'}active{/if}">
			<a href="index.php?module=invoices&amp;view=manage&amp;having=paid">{$LANG.paid} <span class="sr-only">(current)</span></a>
	 	</li>
	 	<li class="{if $smarty.get.having=='draft'}active{/if}">
			<a href="index.php?module=invoices&amp;view=manage&amp;having=draft">{$LANG.draft} <span class="sr-only">(current)</span></a>
	 	</li>
	 	<li class="{if $smarty.get.having=='real'}active{/if}">
			<a href="index.php?module=invoices&amp;view=manage&amp;having=real">{$LANG.real} <span class="sr-only">(current)</span></a>
	 	</li>
	</ul>
	</div>


	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/invoices/manage.js.php'}


	<div id="export_dialog" class="flora" title="Export">
		<div class="si_toolbar si_toolbar_dialog modal-body list-group">
					<a title='{$LANG.export_tooltip} {$LANG.export_pdf_tooltip}' class='list-group-item export_pdf export_window'><img src="./images/common/page_white_acrobat.png" alt="" />
						{$LANG.export_pdf}
					</a>

					<a title='{$LANG.export_tooltip} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet}' class='list-group-item export_xls export_window'><img src="./images/common/page_white_excel.png" alt="" />
						{$LANG.export_xls}
					</a>

				   <a title='{$LANG.export_tooltip} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor}' class='list-group-item export_doc export_window' ><img src="./images/common/page_white_word.png" alt="" />
						{$LANG.export_doc}
					</a>
		</div>
	</div>
{/if}

