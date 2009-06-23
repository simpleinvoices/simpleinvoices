{if $mysql < 5 && $db_server == 'mysql'}

		NOTE <a href='index.php?module=documentation&amp;view=view&amp;page=help_mysql4' rel='gb_page_center[450, 450]' ><img src='./images/common/help-small.png' alt="" /></a> : As you are using Mysql 4 some features have been disabled<br />
{/if}

<!-- Welcome message - start -->
<br />
{if $first_run_wizard == true}
<br />
    <span class="welcome">
       {$LANG.thank_you} {$LANG.before_starting}
    </span>
    <br />
    <br />
    <br />
    
        <table class="buttons" align="center">
    {if $billers == null}
        <tr>
                <td>
                     Setup yourself up as a biller, click &nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=billers&amp;view=add" class="positive">
                        <img src="./images/common/user_add.png" alt="" />
                        {$LANG.add_new_biller}
                    </a>
                </td>
        </tr>
    {/if}
    {if $customers == null}
            <tr>
                <td>
                     Add a client, click &nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=customers&amp;view=add" class="positive">
                        <img src="./images/common/vcard_add.png" alt="" />
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
    {/if}
    {if $products == null}
            <tr>
                <td>
                     Add some products, click &nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=products&amp;view=add" class="positive">
                        <img src="./images/common/cart_add.png" alt="" />
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>

    {/if}
    {if $taxes == null}
            <tr>
                <td>
                     Add a tax rate, click &nbsp;  
                </td>
                <td>
                    <a href="index.php?module=tax_rates&view=add" class="positive">
                        <img src="./images/common/money_delete.png" alt="" />
                        {$LANG.add_new_tax_rate}
                    </a>
                </td>
            </tr>

    {/if}
    {if $preferences == null}
            <tr>
                <td>
                     Add an invoice preference, click &nbsp;  
                </td>
                <td>
                    </a>
                    <a href="./index.php?module=preferences&amp;view=add" class="positive">
                        <img src="./images/common/page_white_edit.png" alt="" />
                        {$LANG.add_new_preference}
                    </a>
                </td>
            </tr>


    {/if}
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <br />        
                </td>
            </tr>
            <tr>
                <td>
                     Go nuts creating invoices, click &nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=invoices&amp;view=itemised" class="positive">
                        <img src="./images/famfam/add.png" alt="" />
                        {$LANG.new_invoice}
                    </a>
                </td>
            </tr>
        </table>
        <br />

    <br />
        <table class="buttons" align="center" >
        <tr>
            <td>
            If you need to customise some of the settings (ie. language, default items, etc..) click, &nbsp;

            </td>
            <td>
            <a href="index.php?module=options&amp;view=index" class="">
            <img src="./images/common/cog_edit.png" alt=""/>
            {$LANG.settings}
            </a>
            </td>
        </tr>
        </table>
        <br /><br />
{else}
    <!-- Welcome message - end -->

    <div>
        <!-- Do stuff menu  - start -->
        <!-- Need help mini menu  - start -->
        <div class="floatRight">
            <h2>Need help?</h2>
            <a href="">Simple Invoices Help ></a><br />
            <a href="http://www.simpleinvoices.org/forum">Community Forums ></a><br />
            <a href="http://www.simpleinvoices.org/blog">Simple Invoices Blog ></a>
        </div>
        <!-- Need help mini menu  - end -->
        <h2>Start working</h2>
        <table class="buttons" >
        <tr>
            <td>
            <a href="index.php?module=invoices&amp;view=itemised" class="positive">
            <img src="./images/common/add.png" alt=""/>
            {$LANG.add_new_invoice}
            </a>
        </td>
        <td>
            <a href="index.php?module=customers&amp;view=add" class="">
            <img src="./images/common/vcard_add.png" alt=""/>
            {$LANG.add_customer}
            </a>
        </td>
        <td>
            <a href="index.php?module=products&amp;view=add" class="">
            <img src="./images/common/cart_add.png" alt=""/>
            {$LANG.add_new_product}
            </a>
            </td>
        </tr>
        </table>
        <br />
        <!-- Do stuff menu  - end -->
        <!-- Don't forget to menu - start -->
        <h2 class="align_left">Don't forget to</h2>
        <table class="buttons" >
        <tr>
            <td>
            <a href="index.php?module=options&amp;view=index" class="">
            <img src="./images/common/cog_edit.png" alt=""/>
            Customise the settings {* LANG TODO*}
            </a>
        </td>
        <td>
            <a href="./index.php?module=options&amp;view=backup_database" class="">
            <img src="./images/common/database_save.png" alt=""/>
            Backup your Database now {* LANG TODO*}
            </a>
        </td>
        </tr>
        </table>
        <br />
        <!-- Don't forget to menu - end -->
    </div>

    <!-- Reports menu - start -->
    <div class="align_left">
        <h2>Your reports</h2>
        --fancy graph here-- --some links on the right--
        <br />
    </div>
    <!-- Reports menu - end -->
    <br />
    <br />
    <span class="welcome">
       Note: this page is a work-in-progress
    </span>
    <br />
    <br />
{/if}
