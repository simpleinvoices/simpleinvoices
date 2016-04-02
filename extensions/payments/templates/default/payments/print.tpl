<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="{$css|urlsafe}" media="all">
  <title>{$preference.pref_inv_wording|htmlsafe} {$LANG.number_short|htmlsafe}: {$invoice.id|htmlsafe}</title>
</head>
<body>
  {php}
  global $smarty;
  $now = new DateTime();
  $curr_month = $now->format('m');
  if ($curr_month == '12') {
    $logo = $smarty->get_template_vars('logo');
    $len = strlen($logo) - 4;
    $new_logo = substr($logo,0,$len).'Christmas.gif';
    $smarty->assign('logo', $new_logo);
  }
  {/php}
  <br />
  <div id="container">
    <div id="header"></div>
    <table style="width: 100%; margin-right: auto; margin-left: auto;">
      <tr>
        <td colspan="5"><img src="{$logo|urlsafe}" border="0" hspace="0" align="left"></td>
        <th align="right">
          <span class="font1">Receipt for {$LANG.payment_id}&nbsp;{$payment.id|htmlsafe}</span>
        </th>
      </tr>
      <tr>
        <td colspan="6" class="tbl1-top">&nbsp;</td>
      </tr>
    </table>
    <table style="width: 100%; margin-right: auto; margin-left: auto;">
      <tr>
        {if $cust_info_count >= 1}
          <td class="tbl1-bottom col1"><b>{$cust_info[0][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[0][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 1}
          <td class="tbl1-bottom col1"><b>{$biller_info[0][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[0][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 2}
          <td class="tbl1-bottom col1"><b>{$cust_info[1][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[1][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 2}
          <td class="tbl1-bottom col1"><b>{$biller_info[1][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[1][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 3}
          <td class="tbl1-bottom col1"><b>{$cust_info[2][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[2][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 3}
          <td class="tbl1-bottom col1"><b>{$biller_info[2][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[2][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 4}
          <td class="tbl1-bottom col1"><b>{$cust_info[3][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[3][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 4}
          <td class="tbl1-bottom col1"><b>{$biller_info[3][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[3][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 5}
          <td class="tbl1-bottom col1"><b>{$cust_info[4][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[4][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 5}
          <td class="tbl1-bottom col1"><b>{$biller_info[4][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[4][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 6}
          <td class="tbl1-bottom col1"><b>{$cust_info[5][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[5][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 6}
          <td class="tbl1-bottom col1"><b>{$biller_info[5][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[5][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 7}
          <td class="tbl1-bottom col1"><b>{$cust_info[6][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[6][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 7}
          <td class="tbl1-bottom col1"><b>{$biller_info[6][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[6][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 8}
          <td class="tbl1-bottom col1"><b>{$cust_info[7][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[7][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 8}
          <td class="tbl1-bottom col1"><b>{$biller_info[7][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[7][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 9}
          <td class="tbl1-bottom col1"><b>{$cust_info[8][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[8][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 9}
          <td class="tbl1-bottom col1"><b>{$biller_info[8][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[8][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 10}
          <td class="tbl1-bottom col1"><b>{$cust_info[9][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[9][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 10}
          <td class="tbl1-bottom col1"><b>{$biller_info[9][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[9][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 11}
          <td class="tbl1-bottom col1"><b>{$cust_info[10][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[10][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 11}
          <td class="tbl1-bottom col1"><b>{$biller_info[10][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[10][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 12}
          <td class="tbl1-bottom col1"><b>{$cust_info[11][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[11][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 12}
          <td class="tbl1-bottom col1"><b>{$biller_info[11][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[11][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 13}
          <td class="tbl1-bottom col1"><b>{$cust_info[12][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[12][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 13}
          <td class="tbl1-bottom col1"><b>{$biller_info[12][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[12][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        {if $cust_info_count >= 14}
          <td class="tbl1-bottom col1"><b>{$cust_info[13][0]}</b></td>
          <td class="col1 tbl1-bottom">{$cust_info[13][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
        <td class="tbl1-bottom col1"></td>
        {if $biller_info_count >= 14}
          <td class="tbl1-bottom col1"><b>{$biller_info[13][0]}</b></td>
          <td class="col1 tbl1-bottom">{$biller_info[13][1]|htmlsafe}</td>
        {else}
          <td class="tbl1-bottom col1"></td>
          <td class="col1 tbl1-bottom"></td>
        {/if}
      </tr>
      <tr>
        <td class="" colspan="4"></td>
      </tr>
    </table>
    <table class="left" style="width:100%;">
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      <tr>
        <td class="tbl1-bottom col1"><b>{$LANG.payment_id}</b></td>
        <td class="tbl1-bottom col1" colspan="3"><b>{$preference.pref_description|htmlsafe}{$LANG.id}</b></td>
        <td class="tbl1-bottom col1" align="right"><b>{$LANG.amount}</b></td>
        <td class="tbl1-bottom col1" align="right"><b>{$LANG.date_upper}</b></td>
        <td class="tbl1-bottom col1" align="right"><b>{$LANG.payment_type}</b></td>
      </tr>
      <tr class="">
        <td class="">{$payment.id|htmlsafe}</td>
        <td class="" colspan="3">{$invoice.index_id|htmlsafe}</td>
        <td class="" align="right">{$preference.pref_currency_sign}{$payment.ac_amount|siLocal_number}</td>
        <td class="" align="right">{$payment.date|htmlsafe}</td>
        <td class="" align="right">
          {$paymentType.pt_description|htmlsafe}
          {if $payment.ac_check_number != ""}&nbsp;{$payment.ac_check_number|htmlsafe}{/if}
        </td>
      </tr>
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      {* hide notes if from an online payment *}
      {if $payment.ac_notes != "" AND $preference.include_online_payment ==""}
      <tr>
        <td class='tbl1-bottom col1'>{$LANG.notes}:</td>
        <td></td>
      </tr>
      {/if}
    </table>
    {if $payment.ac_notes != "" AND $preference.include_online_payment ==""}
    <table>
      <tr>
        <td colspan="2">{$payment.ac_notes|outhtml}</td>
      </tr>
    </table>
    {/if}
  </div>
</body>
</html>
