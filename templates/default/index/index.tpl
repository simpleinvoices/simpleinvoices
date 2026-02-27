{if $mysql < 5 && $db_server == 'mysql'}
<div>
		{$LANG.note} 
		<a href='index.php?module=documentation&amp;view=view&amp;page=help_mysql4' rel='gb_page_center[450, 450]' > <img src='./images/common/help-small.png' alt="" /></a>
		 : {$LANG.mysql4_features_disabled}<br />
</div>
{/if}


{if $first_run_wizard == true}

    <div class="page-header d-print-none mb-4">
        <h1 class="page-title">{$LANG.simple_invoices}</h1>
        <p class="text-muted">{$LANG.thank_you} {$LANG.before_starting}</p>
    </div>
    <div class="si_message alert mb-4">
       {$LANG.thank_you} {$LANG.before_starting}
    </div>
    <div class="card">
        <div class="card-body">
        <table class="si_table_toolbar table table-transparent">
    {if $billers == null}
        <tr>
                <th>{$LANG.setup_as_biller}</th>
                <td class="si_toolbar">
                    <a href="./index.php?module=billers&amp;view=add" class="positive">
                        <img src="./images/common/user_add.png" alt="" />
                        {$LANG.add_new_biller}
                    </a>
                </td>
        </tr>
    {/if}
    {if $customers == null}
            <tr>
                <th>{$LANG.setup_add_customer}</th>
                <td class="si_toolbar">
                    <a href="./index.php?module=customers&amp;view=add" class="positive">
                        <img src="./images/common/vcard_add.png" alt="" />
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
    {/if}
    {if $products == null}
            <tr>
                <th>{$LANG.setup_add_products}</th>
                <td class="si_toolbar">
                    <a href="./index.php?module=products&amp;view=add" class="positive">
                        <img src="./images/common/cart_add.png" alt="" />
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>

    {/if}
    {if $taxes == null}
            <tr>
                <th>{$LANG.setup_add_taxrate}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=tax_rates&amp;view=add" class="positive">
                        <img src="./images/common/money_delete.png" alt="" />
                        {$LANG.add_new_tax_rate}
                    </a>
                </td>
            </tr>

    {/if}
    {if $preferences == null}
            <tr>
                <th>{$LANG.setup_add_inv_pref}</th>
                <td class="si_toolbar">
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
                <th>{$LANG.setup_create_invoices}</th>
                <td class="si_toolbar">
                    <a href="./index.php?module=invoices&amp;view=itemised" class="positive">
                        <img src="./images/famfam/add.png" alt="" />
                        {$LANG.new_invoice}
                    </a>
                </td>
            </tr>


			<tr>
				<th>{$LANG.setup_customisation}</th>
                <td class="si_toolbar">
					<a href="index.php?module=options&amp;view=index" class=""><img src="./images/common/cog_edit.png" alt=""/>{$LANG.settings}</a>
				</td>
			</tr>
        </table>
        </div>
    </div>

{else}
    <div class="si_index si_index_home">
        <div class="page-header d-print-none mb-4">
            <h1 class="page-title">{$LANG.dashboard}</h1>
        </div>
        <div class="row row-deck row-cards mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card card-link card-link-pop">
                    <div class="card-body">
                        <h2 class="h4">{$LANG.need_help}</h2>
                        <a href="http://www.simpleinvoices.org/help" target="_blank" rel="noopener">{$LANG.help_si_help}</a><br />
                        <a href="http://www.simpleinvoices.org/forum" target="_blank" rel="noopener">{$LANG.help_community_forums}</a><br />
                        <a href="http://www.simpleinvoices.org/blog" target="_blank" rel="noopener">{$LANG.help_blog}</a>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="mt-4">{$LANG.start_working}</h2>
		<div class="si_toolbar mb-4">
			<a href="index.php?module=invoices&amp;view=itemised" class="positive"><img src="./images/common/add.png" alt=""/>{$LANG.add_new_invoice}</a>
			<a href="index.php?module=customers&amp;view=add" class=""><img src="./images/common/vcard_add.png" alt=""/>{$LANG.add_customer}</a>
			<a href="index.php?module=products&amp;view=add" class=""><img src="./images/common/cart_add.png" alt=""/>{$LANG.add_new_product}</a>
		</div>

        <h2 class="align_left mt-4">{$LANG.dont_forget_to}</h2>
		<div class="si_toolbar">
			<a href="index.php?module=options&amp;view=index" class=""><img src="./images/common/cog_edit.png" alt=""/>{$LANG.customise_settings}</a>
			<a href="./index.php?module=options&amp;view=backup_database" class=""><img src="./images/common/database_save.png" alt=""/>{$LANG.backup_your_database}</a>
		</div>

    </div>
{/if}
