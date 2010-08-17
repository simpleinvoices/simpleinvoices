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

<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=invoices&amp;view=itemised" class="positive">
                <img src="./images/common/add.png" alt="" />
                {$LANG.new_invoice}
            </a>

        </td>
    </tr>
</table>

{if $number_of_invoices.count == 0}
	
	<br />
	<br />
	<span class="welcome">{$LANG.no_invoices}</span>
	<br />
	<br />
	<br />
	<br />
{else}


    <br />
    <span class="welcome">
       {$LANG.filters}:
    <a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed">{$LANG.due}</a> : 
    <a href="index.php?module=invoices&amp;view=manage&amp;having=paid">{$LANG.paid}</a> : 
    <a href="index.php?module=invoices&amp;view=manage&amp;having=draft">{$LANG.draft}</a> : 
    <a href="index.php?module=invoices&amp;view=manage&amp;having=real">{$LANG.real}</a> : 
    <a href="index.php?module=invoices&amp;view=manage">{$LANG.all}</a> 

   </span>
    <br />
    <br />
	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/invoices/manage.js.php'}


	<div id="export_dialog" class="flora" title="Export">

		<table class="buttons">
			<tr>
				<td>

					<a
				     	title='{$LANG.export_tooltip} {$LANG.export_pdf_tooltip}'
						class='export_pdf export_window' 
					>
						<img src="./images/common/page_white_acrobat.png" alt="" />
						{$LANG.export_pdf}
					</a>
				  </td>
			</tr>
			<tr>
				<td>  
					
					<a 
						title='{$LANG.export_tooltip} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet}' 
						class='export_xls export_window'
				   >
						<img src="./images/common/page_white_excel.png" alt="" />
						{$LANG.export_xls}
					</a>
					</td>
			</tr>
			<tr>
				<td>    
			
				   <a 
						title='{$LANG.export_tooltip} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor}'
						class='export_doc export_window' 
				   >
						<img src="./images/common/page_white_word.png" alt="" />
						{$LANG.export_doc}
					</a>
				</td>
			</tr>
		</table>
	</div>
{/if}

