<h1 style="position: relative; margin: 0 auto; text-align: center;">Past Due Report</h1>
<hr />
<form name="frmpost"
  action="index.php?module=reports&amp;view=past_due_report" 
  method="post" >
  <table style="margin-left:auto;margin-right:auto;">
    <tr>
      <td class="details_screen">Display Detail</td>
      <td><input type="checkbox" name="display_detail"
                 {if $smarty.post.display_detail == "yes"} checked {/if}
                 value="yes" />
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table class="buttons" style="margin-left:auto;margin-right:auto;">
          <tr>
            <td>
              <button type="submit" class="positive" name="submit" value="past_due_report">
                <img class="button_img" src="./images/common/tick.png" alt="" />
                Run Report
              </button>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<br/>
<br/>
<table style="margin-left:auto;margin-right:auto;width:60%;">
  <thead>
    <tr style="font-weight: bold;">
      <th class="details_screen" style="text-align: LEFT;">{$LANG.customer}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="10%" style="text-align: center;">{$LANG.billed}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="10%" style="text-align: center;">{$LANG.paid}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="10%" style="text-align: center;">{$LANG.due}</th>
    </tr>
  </thead>
  <tbody>
  {foreach name=loop1 item=info1 from=$cust_info}
    {foreach name=loop2 item=info2 key=key2 from=$info1}
      {if     $key2=='name'    }{assign var=name     value=$info2}
      {elseif $key2=='billed'  }{assign var=billed   value=$info2}
      {elseif $key2=='paid'    }{assign var=paid     value=$info2}
      {elseif $key2=='owed'    }{assign var=owed     value=$info2}
      {elseif $key2=='inv_info'}{assign var=inv_info value=$info2}
      {/if}
    {/foreach}
    <tr>
      <td class="details_screen">{$name}</td>
      {if $smarty.post.display_detail == 'yes'}
        <td colspan="6">&nbsp;</td>
      {else}
        <td>&nbsp;</td>
        <td class="details_screen" style="text-align: right;">{$billed}</td>
        <td>&nbsp;</td>
        <td class="details_screen" style="text-align: right;">{$paid}</td>
        <td>&nbsp;</td>
        <td class="details_screen" style="text-align: right;">{$owed}</td>
      {/if}
    </tr>
    {if $smarty.post.display_detail == 'yes'}
      {foreach name=loop2 item=info2 from=$inv_info}
        {foreach name=loop3 item=info3 key=key3 from=$info2}
          {if     $key3=='id'    }{assign var=id     value=$info3}
          {elseif $key3=='billed'}{assign var=billed value=$info3}
          {elseif $key3=='paid'  }{assign var=paid   value=$info3}
          {elseif $key3=='owed'  }{assign var=owed   value=$info3}
          {/if}
        {/foreach}    
        <tr>
          <td class="details_screen" style="float:left;margin-left:20%;">Invoice&nbsp;#{$id}</td>
          <td>&nbsp;</td>
          <td class="details_screen" style="float:right;margin-right:auto;">{$billed}</td>
          <td>&nbsp;</td>
          <td class="details_screen" style="float:right;margin-right:auto;">{$paid}</td>
          <td>&nbsp;</td>
          <td class="details_screen" style="float:right;margin-right:auto;">{$owed}</td>
          <td>&nbsp;</td>
        </tr>
      {/foreach}
    {/if}
  {/foreach}
  </tbody>
</table>
