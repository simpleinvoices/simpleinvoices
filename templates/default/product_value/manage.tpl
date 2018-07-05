{*
/*
* Script: manage.tpl
* 	 Invoice Product Values manage template
*
* License:
*	 GPL v3 or above
*/
*}
<div class="si_toolbar si_toolbar_top">
  <a href="index.php?module=product_value&view=add" class="">
    <img src="images/common/add.png" alt="" />
    {$LANG.add_product_value}
  </a>
</div>
{if $number_of_rows == 0}
  <div class="si_message">There are no product value records.  Click the 'Add Product Value' button above to create one</div>
{else}
  <table id="manageGrid" style="display:none"></table>
  {include file='modules/product_value/manage.js.php'}
{/if}
