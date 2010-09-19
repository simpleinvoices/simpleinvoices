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
{literal}
<style>
                                                                                            
.flexigrid div.fbutton .filter_all
{
/*     background: url(sys/images/common/tag-right.png) no-repeat center left;*/
}

                                                                                                                                                                       </style>
                                                                                                                                                                       {/literal}
{if ($userRole != "customer")} 
        <table class="buttons" align="center">
        <tr>
            <td>
            <a href="index.php?module=invoices&amp;view=itemised" class="positive">
            <img src="{$include_dir}sys/images/common/add.png" alt=""/>
            {$LANG.New_Invoice}
            </a>
        </td>
        <td>
            <a href="index.php?module=customers&amp;view=add" class="">
            <img src="{$include_dir}sys/images/common/vcard_add.png" alt=""/>
            {$LANG.add_customer}
            </a>
        </td>
        <td>
            <a href="index.php?module=products&amp;view=add" class="">
            <img src="{$include_dir}sys/images/common/cart_add.png" alt=""/>
            {$LANG.add_product}
            </a>
            </td>
        </tr>
        </table>
    <br />

{/if}

{if $number_of_invoices.count == 0}
	
	<br />
	<br />
	<span class="welcome">{$LANG.no_invoices}</span>
	<br />
	<br />
	<br />
	<br />
{else}

	<table id="manageGrid" style="display:none"></table>
	{include file="$smarty_embed_path/sys/modules/invoices/manage.js.php"}


	<div id="export_dialog" class="flora" title="Export">

		<table class="buttons">
			<tr>
				<td>

					<a
				     	title='{$LANG.export_tooltip} {$LANG.export_pdf_tooltip}'
						class='export_pdf export_window' 
					>
						<img src="{$include_dir}sys/images/common/page_white_acrobat.png" alt="" />
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
						<img src="{$include_dir}sys/images/common/page_white_excel.png" alt="" />
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
						<img src="{$include_dir}sys/images/common/page_white_word.png" alt="" />
						{$LANG.export_doc}
					</a>
				</td>
			</tr>
		</table>
	</div>
{/if}

