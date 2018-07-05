{*
/*
* Script: manage.tpl
* 	 Invoice Preferences manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
<div class="si_toolbar si_toolbar_top">
  <a href="index.php?module=product_attribute&view=add" class="">
    <img src="images/common/add.png" alt="" />
    {$LANG.add_product_attribute}
  </a>
</div>
{if $number_of_rows == 0}
  <div class="si_message">There are no product attribute records.  Click the 'Add Product Attribute' button above to create one</div>
{else}
  <table id="manageGrid" style="display:none"></table>
  {include file='modules/product_attribute/manage.js.php'}
{/if}
