<div>

<!-- Welcome message - start -->

<h2>Sysmte preferences welcome message</h2>

Thank you for choosing Simple Invoices! There are just a couple of things to do before you can start invoicing<br><br>
1 - Setup yourself up as a biller - <a href="index.php?module=biller&view=add">click here</a><br>

<br><br>
Already know Simple Invoices by heart? You can <a href="">hide this text</a> forever then  <a href="">click here</a>



<!-- Welcome message - end -->
<!-- Do stuff menu  - start -->
<h2>Start working</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=system_defaults&view=manage" class="positive">
                <img src="./images/common/add.png" alt=""/>
                {$LANG.system_preferences}
            </a>
            

        </td>
    </tr>
</table>
<br>

<!-- Do stuff menu  - end -->

<!-- Don't forget to menu - start -->
<h2>Don't forget to</h2>
<table class="buttons" >
    <tr>
        <td>
             <a href="index.php?module=custom_fields&view=manage" class="positive">
                <img src="./images/common/add.png" alt=""/>
                {$LANG.custom_fields_upper}
            </a>
    
    
           <a href="index.php?module=tax_rates&view=manage" class="positive">
                <img src="./images/common/add.png" alt=""/>
                 {$LANG.tax_rates}
            </a>
            
             <a href="index.php?module=preferences&view=manage" class="">
                <img src="./images/common/cog_edit.png" alt=""/>
                 {$LANG.invoice_preferences}
            </a>
    
    
           <a href="index.php?module=payment_types&view=manage" class="">
                <img src="./images/common/database_save.png" alt=""/>
                 {$LANG.payment_types}
            </a>
        </td>
    </tr>

 
</table>



<!-- Don't forget to menu - start -->
<h2>Database stuff</h2>
<table class="buttons" >

    <tr>
        <td>

            
             <a href="index.php?module=options&view=backup_database" class="">
                <img src="./images/common/cog_edit.png" alt=""/>
                {$LANG.backup_database}
            </a>
    
    
           <a href="index.php?module=options&view=manage_sqlpatches" class="">
                <img src="./images/common/database_save.png" alt=""/>
                 {$LANG.database_upgrade_manager}
            </a>
        </td>
    </tr>    
</table>


</div>