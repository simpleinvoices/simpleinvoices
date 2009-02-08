<div>

{literal}
<script type="text/javascript">
<!--

//$(document).ready(function(){
//	("#tabmenu > ul").tabs({ selected: null });
//});

//-->
</script>
{/literal} 
<!-- Welcome message - start -->
<div class="welcome">
	<h2>System preferences welcome message</h2>
	
	Thank you for choosing Simple Invoices! There are just a couple of things to do before you can start invoicing<br><br>
	1 - Setup yourself up as a biller - <a href="index.php?module=biller&view=add">click here</a><br>
	
	<br><br>
	Already know Simple Invoices by heart? You can <a href="">hide this text</a> forever then  <a href="">click here</a>
</div>


<!-- Welcome message - end -->
<!-- Do stuff menu  - start -->
<h2>System settings</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=system_defaults&view=manage" class="">
                <img src="./images/common/cog_edit.png" alt=""/>
                {$LANG.system_preferences}
            </a>
            
            <a href="index.php?module=custom_fields&view=manage" class="">
                <img src="./images/common/brick_edit.png" alt=""/>
                {$LANG.custom_fields_upper}
            </a>
    
            

        </td>
    </tr>
</table>
<br>

<!-- Do stuff menu  - end -->

<!-- Don't forget to menu - start -->
<h2>Invoice settings</h2>
<table class="buttons" >
    <tr>
        <td>
    
           <a href="index.php?module=tax_rates&view=manage" class="">
                <img src="./images/common/money_delete.png" alt=""/>
                 {$LANG.tax_rates}
            </a>
            
             <a href="index.php?module=preferences&view=manage" class="">
                <img src="./images/common/page_white_edit.png" alt=""/>
                 {$LANG.invoice_preferences}
            </a>
    
    
           <a href="index.php?module=payment_types&view=manage" class="">
                <img src="./images/common/creditcards.png" alt=""/>
                 {$LANG.payment_types}
            </a>
        </td>
    </tr>

 
</table>
<br>


<!-- Don't forget to menu - start -->
<h2>Database stuff</h2>
<table class="buttons" >

    <tr>
        <td>

            
             <a href="index.php?module=options&view=backup_database" class="">
                <img src="./images/common/database_save.png" alt=""/>
                {$LANG.backup_database}
            </a>
    
    
           <a href="index.php?module=options&view=manage_sqlpatches" class="">
                <img src="./images/common/database.png" alt=""/>
                 {$LANG.database_upgrade_manager}
            </a>
        </td>
    </tr>    
</table>


</div>
