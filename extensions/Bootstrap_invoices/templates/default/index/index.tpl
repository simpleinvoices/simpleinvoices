<h1 class="title">{$LANG.dashboard}</h1>
{if $mysql < 5 && $db_server == 'mysql'}
<div>
		{$LANG.note} 
		<a href='index.php?module=documentation&amp;view=view&amp;page=help_mysql4' rel='gb_page_center[450, 450]' > <span class="glyphicon glyphicon-question-sign"></span></a>
		 : {$LANG.mysql4_features_disabled}
</div>
{/if}


{if $first_run_wizard == true}

    <div class="si_message">
       {$LANG.thank_you} {$LANG.before_starting}
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
    {if $billers == null}
        <tr>
                <td class="si_toolbar">
                    <a href="./index.php?module=billers&amp;view=add" class="btn btn-default positive">
                        <span class="glyphicon glyphicon-plus"></span>
                        {$LANG.add_new_biller}
                    </a>
                </td>
                <th>{$LANG.setup_as_biller}</th>
        </tr>
    {/if}
    {if $customers == null}
            <tr>
                <td class="si_toolbar">
                    <a href="./index.php?module=customers&amp;view=add" class="btn btn-default positive"><span class="glyphicon glyphicon-user"></span>
                        {$LANG.customer_add}
                    </a>
                </td>
                <th>{$LANG.setup_add_customer}</th>
            </tr>
    {/if}
    {if $products == null}
            <tr>
                <td class="si_toolbar">
                    <a href="./index.php?module=products&amp;view=add" class="btn btn-default positive"><span class="glyphicon glyphicon-shopping-cart"></span>
                        {$LANG.add_new_product}
                    </a>
                </td>
                <th>{$LANG.setup_add_products}</th>
            </tr>

    {/if}
    {if $taxes == null}
            <tr>
                <td class="si_toolbar">
                    <a href="index.php?module=tax_rates&amp;view=add" class="btn btn-default positive"><span class="glyphicon glyphicon-usd"></span>
                        {$LANG.add_new_tax_rate}
                    </a>
                </td>
                <th>{$LANG.setup_add_taxrate}</th>
            </tr>

    {/if}
    {if $preferences == null}
            <tr>
                <td class="si_toolbar">
                    </a>
                    <a href="./index.php?module=preferences&amp;view=add" class="btn btn-default positive"><span class="glyphicon glyphicon-edit"></span>
                        {$LANG.add_new_preference}
                    </a>
                </td>
                <th>{$LANG.setup_add_inv_pref}</th>
            </tr>

    {/if}
                </td>
            </tr>


            <tr>
                <td class="si_toolbar">
                    <a href="./index.php?module=invoices&amp;view=itemised" class="btn btn-default positive"><span class="glyphicon glyphicon-plus"></span>
                        {$LANG.new_invoice}
                    </a>
                </td>
                <th>{$LANG.setup_create_invoices}</th>
            </tr>


			<tr>
                <td class="si_toolbar">
					<a href="index.php?module=options&amp;view=index" class="btn btn-default"><span class="glyphicon glyphicon-cog"></span> {$LANG.settings}</a>
				</td>
                <th>{$LANG.setup_customisation}</th>
			</tr>
        </table>
    </div>

{else}
    <div class="col-md-9 si_index si_index_home">

        <h2>{$LANG.start_working}</h2>
		<div class="si_toolbar">
			<a href="index.php?module=invoices&amp;view=itemised" class="btn btn-default positive"><span class="glyphicon glyphicon-plus"></span> {$LANG.add_new_invoice}</a>
			<a href="index.php?module=customers&amp;view=add" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> {$LANG.add_customer}</a>
			<a href="index.php?module=products&amp;view=add" class="btn btn-default"><span class="glyphicon glyphicon-shopping-cart"></span> {$LANG.add_new_product}</a>
		</div>

        <h2 class="align_left">{$LANG.dont_forget_to}</h2>
		<div class="si_toolbar">
			<a href="index.php?module=options&amp;view=index" class="btn btn-default"><span class="glyphicon glyphicon-cog"></span> {$LANG.customise_settings}</a>
			<a href="./index.php?module=options&amp;view=backup_database" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> {$LANG.backup_your_database}</a>
		</div>

    </div>
    <div class="col-md-3 si_index_help">
            <h2>{$LANG.need_help}</h2>
            <ul class="nav nav-pills nav-stacked">
            <li><a href="">{$LANG.help_si_help} &gt;</a></li>
            <li><a href="http://www.simpleinvoices.org/forum">{$LANG.help_community_forums} &gt;</a></li>
            <li><a href="http://www.simpleinvoices.org/blog">{$LANG.help_blog} &gt;</a></li>
            <li><a href="https://groups.google.com/forum/#!forum/simpleinvoices">{$LANG.help_mailing_list} &gt;</a></li>
            </ul>
        </div>
{/if}
