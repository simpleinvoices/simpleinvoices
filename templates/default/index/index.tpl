{if $mysql < 5 && $db_server == 'mysql'}
<div>
		{$LANG.note} 
		<a href='index.php?module=documentation&amp;view=view&amp;page=help_mysql4' rel='gb_page_center[450, 450]' > <img src='./images/common/help-small.png' alt="" /></a>
		 : {$LANG.mysql4_features_disabled}<br />
</div>
{/if}


{if $first_run_wizard == true}

    <div class="si_message">
       {$LANG.thank_you} {$LANG.before_starting}
    </div>
    
        <table class="si_table_toolbar">
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

{else}
    <div class="si_index si_index_home">

        <div class="si_index_help">
            <h2>{$LANG.need_help}</h2>
            <a href="./documentation/{$language}/general/FAQs.html">{$LANG.help_si_help} &gt;</a><br />
            <a href="./documentation/{$language}/general/gpl.html">GPL {$LANG.license} &gt;</a><br />
            <a href="http://www.simpleinvoices.org/forum">{$LANG.help_community_forums} &gt;</a><br />
            <a href="https://plus.google.com/communities/102476804981627142204">Google Groups Forum &gt;</a><br />
            <del><a href="http://www.simpleinvoices.org/blog">{$LANG.help_blog} &gt;</a></del><br />
            <a href="https://groups.google.com/forum/#!forum/simpleinvoices">{$LANG.help_mailing_list} &gt;</a>
        </div>

        <h2>{$LANG.start_working}</h2>
		<div class="si_toolbar">
			<a href="index.php?module=invoices&amp;view=itemised" class="positive"><img src="./images/common/add.png" alt=""/>{$LANG.add_new_invoice}</a>
			<a href="index.php?module=customers&amp;view=add" class=""><img src="./images/common/vcard_add.png" alt=""/>{$LANG.add_customer}</a>
			<a href="index.php?module=products&amp;view=add" class=""><img src="./images/common/cart_add.png" alt=""/>{$LANG.add_new_product}</a>
		</div>

        <h2 class="align_left">{$LANG.dont_forget_to}</h2>
		<div class="si_toolbar">
			<a href="index.php?module=options&amp;view=index" class=""><img src="./images/common/cog_edit.png" alt=""/>{$LANG.customise_settings}</a>
			<a href="./index.php?module=options&amp;view=backup_database" class=""><img src="./images/common/database_save.png" alt=""/>{$LANG.backup_your_database}</a>
		</div>

    </div>
{/if}
