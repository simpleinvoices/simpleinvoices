{if $mysql < 5 && $db_server == 'mysql'}

		NOTE <a href='docs.php?t=help&p=mysql4' rel='gb_page_center[450, 450]' ><img src='./images/common/help-small.png'></img></a> : As you are using Mysql 4 some features have been disabled<br>
{/if}
<!-- Welcome message - start -->
<div class="welcome">
	<h2>Welcome {$smarty.session.Zend_Auth.email}!</h2>
	Thank you for choosing Simple Invoices! There are just a couple of things to do before you can start invoicing<br><br>
	1 - Setup yourself up as a biller - <a href="index.php?module=biller&view=add">click here</a><br>
	2 - Add a client - <a href="index.php?module=customers&view=add">click here</a><br>
	3 - Add some products - <a href="index.php?module=products&view=add">click here</a><br>
	4 - go nuts creating invoices - <a href="index.php?module=invoices&view=itemised">click here</a><br><br>
	If you need to customise some of the <a href="index.php?module=options&view=index">settings</a> (ie. language, default items, etc..) <a href="">click here</a> and adjust as required
	<br><br>
	Already know Simple Invoices by heart? You can <a href="">hide this text</a> forever then  <a href="">click here</a>

</div>
<!-- Welcome message - end -->
<!-- Do stuff menu  - start -->
<div>
	<!-- Need help mini menu  - start -->
	<div class="floatRight">
		<h2>Need help?</h2>
		<a href="">Simple Invoices Help ></a><br>
		<a href="http://www.simpleinvoices.org/forum">Community Forums ></a><br>
		<a href="http://www.simpleinvoices.org/blog">Simple Invoices Blog ></a>
	</div>
	<!-- Need help mini menu  - end -->
<h2>Start working</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=invoices&view=itemised" class="positive">
                <img src="./images/common/add.png" alt=""/>
                Add a new Invoice {* LANG TODO*}
            </a>
            
             <a href="index.php?module=customers&view=add" class="">
                <img src="./images/common/vcard_add.png" alt=""/>
                 Add a new Client {* LANG TODO*}
            </a>
    
    
           <a href="index.php?module=products&view=add" class="">
                <img src="./images/common/cart_add.png" alt=""/>
                 Add a new Product {* LANG TODO*}
            </a>
        </td>
    </tr>
</table>
<br>

</div>
<!-- Do stuff menu  - end -->

<!-- Don't forget to menu - start -->
<h2>Don't forget to</h2>
<table class="buttons" >
    <tr>
        <td>

            
             <a href="index.php?module=options&view=index" class="">
                <img src="./images/common/cog_edit.png" alt=""/>
                 Customise the settings {* LANG TODO*}
            </a>
    
    
           <a href="./index.php?module=customers&view=manage" class="">
                <img src="./images/common/database_save.png" alt=""/>
                 Backup your Database now {* LANG TODO*}
            </a>
        </td>
    </tr>
</table>
<br>
<!-- Don't forget to menu - end -->

<!-- Reports menu - start -->
<h2>Your reports</h2>
--fancy graph here-- --some links on the right--
<br>
<!-- Reports menu - end -->


