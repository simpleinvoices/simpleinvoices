<!-- BEFORE:customers -->
			<li><a{if $pageActive == "customer_add"} class="active"{/if} href="index.php?module=customers&amp;view=add">{$LANG.add_customer}</a></li>
<!-- END:customers -->
<!-- REPLACE:add_product -->
			<li><a{if $pageActive == "product_add"} class="active"{/if} href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
{if $defaults.inventory == "1"}
    		<li><a{if $pageActive == "inventory"} class="active"{/if} href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a></li>
	{if $subPageActive == "inventory_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
	{if $subPageActive == "inventory_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
	{if $subPageActive == "inventory_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
{/if}
<!-- END:add_product -->
<!-- REPLACE:product_attributes -->
{if $defaults.product_attributes}
   			<li><a{if $pageActive == "product_attributes"} class="active"{/if} href="index.php?module=product_attribute&amp;view=manage">{$LANG.product_attributes}</a></li>
	{if $subPageActive == "product_attribute_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
	{if $subPageActive == "product_attribute_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
	{if $subPageActive == "product_attribute_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
    		<li><a{if $pageActive == "product_values"} class="active"{/if} href="index.php?module=product_value&amp;view=manage">{$LANG.product_values}</a></li>
	{if $subPageActive == "product_value_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
	{if $subPageActive == "product_value_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
	{if $subPageActive == "product_value_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
{/if}
<!-- END:product_attributes -->
<!-- BEFORE:settings -->
			<li><a {if $pageActive== "setting"}class="active" {/if}href="index.php?module=options&amp;view=index">{$LANG.settings}</a></li>
	{if $subPageActive == "setting_extensions"}<li><a class="active active_subpage" href="#">{$LANG.extensions}</a></li>{/if}
			<li><a{if $pageActive == "setting_extensions"} class="active"{/if} href="index.php?module=extensions&amp;view=manage">{$LANG.extensions}</a></li>
<!-- END:settings -->
