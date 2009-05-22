<br />
<table align="center">
<tr>
<td>

<h2>System settings</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=system_defaults&amp;view=manage" class="">
                <img src="./images/common/cog_edit.png" alt="" />
                {$LANG.system_preferences}
            </a>
	
		</td>
		<td>
            <a href="index.php?module=custom_fields&amp;view=manage" class="">
                <img src="./images/common/brick_edit.png" alt="" />
                {$LANG.custom_fields_upper}
            </a>
        </td>
		<td>
             <a href="index.php?module=extensions&view=manage" class="">
                <img src="./images/common/brick_edit.png" alt=""/>
                {$LANG.extensions}
            </a>
        </td>
    </tr>
</table>
<br />

<!-- Do stuff menu  - end -->

<!-- Don't forget to menu - start -->
<h2>Invoice settings</h2>
<table class="buttons" >
      <tr>
        <td>
    
           <a href="index.php?module=tax_rates&amp;view=manage" class="">
                <img src="./images/common/money_delete.png" alt="" />
                 {$LANG.tax_rates}
            </a>

		</td>
		<td>
            
             <a href="index.php?module=preferences&amp;view=manage" class="">
                <img src="./images/common/page_white_edit.png" alt="" />
                 {$LANG.invoice_preferences}
            </a>
    	</td>
		<td>
    
           <a href="index.php?module=payment_types&amp;view=manage" class="">
                <img src="./images/common/creditcards.png" alt="" />
                 {$LANG.payment_types}
            </a>
        </td>
    </tr>

</table>
<br />

<!-- Don't forget to menu - start -->
<h2>Database stuff</h2>
<table class="buttons" >

    <tr>
        <td>

            
             <a href="index.php?module=options&amp;view=backup_database" class="">
                <img src="./images/common/database_save.png" alt="" />
                {$LANG.backup_database}
            </a>
    	</td>
		<td>
    
           <a href="index.php?module=options&amp;view=manage_sqlpatches" class="">
                <img src="./images/common/database.png" alt="" />
                 {$LANG.database_upgrade_manager}
            </a>
        </td>
    </tr>    
</table>

</td>
</tr>
</table>
<br />
