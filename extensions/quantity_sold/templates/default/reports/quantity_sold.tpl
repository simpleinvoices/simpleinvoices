<center>
  <h2>{$LANG.quantity_sold}</h2>
</center>
{if $menu != false}

    <div class="welcome">
        <form name="frmpost" action="index.php?module=reports&amp;view=quantity_sold" method="post">

          <table align="center">
            <tr>
              <td class="details_screen">
                {$LANG.product_description}
              </td>
              <td>
                {if $products_list == null }
                  <p><em>{$LANG.no_products}.</em></p>
                {else}
                  <select name="product_id">
                    {foreach from=$products_list item=product}
                      <option {if $product.id == $product_id} selected {/if} value="{$product.id}">{$product.description}</option>
                    {/foreach}
                  </select>
                {/if}
              </td>
            </tr>
            <tr>
                <td class="details_screen">
                  {$LANG.filter_by_date}
                </td>
                <td class="">
                  <input type="checkbox" name="filter_by_date" {if $filter_by_date == "yes"} checked {/if} value="yes">
                </td>
            </tr>
            <tr>
                <td wrap="nowrap" class="details_screen">
                  {$LANG.start_date}
                </td>
                <td>
                  <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date}' />
                </td>
            </tr>
            <tr>
                <td wrap="nowrap" class="details_screen"  >
                  {$LANG.end_date}
                </td>
                <td>
                  <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date}' />
                </td>
            </tr>

            <tr>
              <td colspan="2"><br />
                <table class="buttons" align="center">
                  <tr>
                    <td>
                      <button type="submit" class="positive" name="submit" value="statement_report">
                        <img class="button_img" src="./images/common/tick.png" alt="" />
                        {$LANG.run_report}
                      </button>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </form>
    </div>

{/if}

<br />

{if $smarty.post.submit != null OR $view == export}

  <p>
  {if $filter_by_date == "yes"}
    {$LANG.statement_for_the_period} <strong>{$start_date}</strong> {$LANG.to} <strong>{$end_date}</strong>
  {else}
    {$LANG.quantity_sold_all_time}
  {/if}
</p>

  <table id="quantity-sold-results">
    <thead>
      <tr>
        <th class="description">
          {$LANG.description}
        </th>
        <th class="qty">
          {$LANG.quantity}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="description">
          {$description}
        </td>
        <td class="qty">
          {$count}
        </td>
      </tr>
    </tbody>
  </table>

{/if}
