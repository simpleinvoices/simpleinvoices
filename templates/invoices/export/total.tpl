
		                <table class="left" width="100%">

				<tr class="tbl1 col1" >
                        <td class="tbl1 col1 tbl1-right" colspan="6"><b>{$LANG.description}</b></td>
                </tr>

	
	{*foreach from=$invoiceItems item=invoiceItem*}

			    <tr class="tbl1-left tbl1-right">
                        <td class="tbl1-left tbl1-right" colspan="6">{$invoiceItems[0].description|htmlsafe}</td>
                </tr>
                <tr class="tbl1-left tbl1-right">
                        <td class="tbl1-left tbl1-right" colspan="6"><br /></td>
                </tr>

	{*/foreach*}
         
	
	<tr class="tbl1-left tbl1-right">
		<td class="tbl1-left tbl1-right" colspan="6" ><br /></td>
	</tr>
