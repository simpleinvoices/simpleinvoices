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
	<p><em>{$LANG.no_invoices}</em></p>
{else}

<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=invoices&view=itemised" class="positive">
                <img src="./images/common/add.png" alt=""/>
                Add a new Invoice {* TODO $LANG  *}
            </a>

        </td>
    </tr>
</table>

<table id="manageGrid" style="display:none"></table>

 {include file='../modules/invoices/manage.js.php'}


<div id="export_dialog" class="flora" title="Export">

	<table class="buttons" >
		<tr>
			<td>

				<a
			{*     	title='".$LANG['export_tooltip']." ".$invoice['preference.pref_inv_wording']." ".$row['id']." ".$LANG['export_pdf_tooltip']."' *}
					class='export_pdf export_window' 
				>
					<img src="./images/common/page_white_acrobat.png" alt=""/>
					Export as PDF {* LANG TODO*}
				</a>
			  </td>
		</tr>
		<tr>
			<td>  
				
				<a 
					title='".$LANG['export_tooltip']." ".$invoice['preference.pref_inv_wording']." ".$row['id']." ".$LANG['export_xls_tooltip'].$spreadsheet." ".$LANG['format_tooltip']."' 
					class='export_xls export_window'
					href=''
			   >
					<img src="./images/common/page_white_excel.png" alt=""/>
					 Export as .XLS {* LANG TODO*}
				</a>
				</td>
		</tr>
		<tr>
			<td>    
		
			   <a 
					title='".$LANG['export_tooltip']." ".$invoice['preference.pref_inv_wording']." ".$row['id']." ".$LANG['export_xls_tooltip'].$spreadsheet." ".$LANG['format_tooltip']."' 
					class='export_doc export_window' 
					href=''         
			   >
					<img src="./images/common/page_white_word.png" alt=""/>
					 Export as .DOC {* LANG TODO*}
				</a>
			</td>
		</tr>
	</table>
</div>
{/if}


