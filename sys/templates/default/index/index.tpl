{if $mysql < 5 && $db_server == 'mysql'}

		{$LANG.note} <a href='index.php?module=documentation&amp;view=view&amp;page=help_mysql4' rel='gb_page_center[450, 450]' ><img src='{$smarty_embed_path}/sys/images/common/help-small.png' alt="" /></a> : {$LANG.mysql4_features_disabled}<br />
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
                     {$LANG.setup_as_biller}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=billers&amp;view=add" class="positive">
                        <img src="{$smarty_embed_path}/sys/images/common/user_add.png" alt="" />
                        {$LANG.add_new_biller}
                    </a>
                </td>
        </tr>
    {/if}
    {if $customers == null}
            <tr>
                <td>
                     {$LANG.setup_add_customer}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=customers&amp;view=add" class="positive">
                        <img src="{$smarty_embed_path}/sys/images/common/vcard_add.png" alt="" />
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
    {/if}
    {if $products == null}
            <tr>
                <td>
                     {$LANG.setup_add_products}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=products&amp;view=add" class="positive">
                        <img src="{$smarty_embed_path}/sys/images/common/cart_add.png" alt="" />
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>

    {/if}
    {if $taxes == null}
            <tr>
                <td>
                     {$LANG.setup_add_taxrate}&nbsp;  
                </td>
                <td>
                    <a href="index.php?module=tax_rates&amp;view=add" class="positive">
                        <img src="{$smarty_embed_path}/sys/images/common/money_delete.png" alt="" />
                        {$LANG.add_new_tax_rate}
                    </a>
                </td>
            </tr>

    {/if}
    {if $preferences == null}
            <tr>
                <td>
                     {$LANG.setup_add_inv_pref}&nbsp;  
                </td>
                <td>
                    </a>
                    <a href="./index.php?module=preferences&amp;view=add" class="positive">
                        <img src="{$smarty_embed_path}/sys/images/common/page_white_edit.png" alt="" />
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
                     {$LANG.setup_create_invoices}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=invoices&amp;view=itemised" class="positive">
                        <img src="{$smarty_embed_path}/sys/images/famfam/add.png" alt="" />
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
            {$LANG.setup_customisation}&nbsp;

            </td>
            <td>
            <a href="index.php?module=options&amp;view=index" class="">
            <img src="{$smarty_embed_path}/sys/images/common/cog_edit.png" alt=""/>
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
        <h2>{$LANG.start_working}</h2>
        <table class="buttons">
        <tr>
            <td>
            <a href="index.php?module=invoices&amp;view=itemised" class="positive">
            <img src="{$smarty_embed_path}/sys/images/common/add.png" alt=""/>
            {$LANG.add_new_invoice}
            </a>
        </td>
        <td>
            <a href="index.php?module=customers&amp;view=add" class="">
            <img src="{$smarty_embed_path}/sys/images/common/vcard_add.png" alt=""/>
            {$LANG.add_customer}
            </a>
        </td>
        <td>
            <a href="index.php?module=products&amp;view=add" class="">
            <img src="{$smarty_embed_path}/sys/images/common/cart_add.png" alt=""/>
            {$LANG.add_new_product}
            </a>
            </td>
        </tr>
        </table>
        <br />
        <!-- Do stuff menu  - end -->
    </div>

    <!-- Reports menu - start -->
    <div class="align_left">
        <h2>{$LANG.your_reports}</h2>
        	<div class="graphName" style="height: auto; width:600px; text-align: center;">
			<b>{$LANG.monthly_sales_per_year_flot}</b>
			</div>
			<!-- Flot integration -->
			<div id="placeholder" style="width:600px;height:200px;">
			</div>
        	{literal}
        	<script id="source" language="javascript" type="text/javascript"> 
			$(function () {
				{/literal}
				{foreach item=year from=$years}
					var d{$year|htmlsafe} = [{foreach key=key item=item_sales from=$total_sales.$year}[{$key|htmlsafe}, {if $item_sales > 0}{$item_sales|siLocal_number}{else}0{/if}],{/foreach}];
				 {/foreach}
			 	
			     $.plot($("#placeholder"), [
			        {foreach item=year from=$years}
			        {literal}
			        {
			        	{/literal}
			            data: d{$year|htmlsafe},
			            label: "{$year|htmlsafe}",
			            lines: {literal} { show: true } 
			        },
			        {/literal}
			         {/foreach}
			         {literal}
			    ]);
			});
			</script> 
			{/literal}
        <br />
    </div>
    <!-- Reports menu - end -->
{/if}
