<div class="si_form si_form_view" id="si_form_pay_details">
  <table>
    <tr>
      <th>{$LANG.payment_id}</th><td>{$payment.id|htmlsafe}</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th>{$LANG.invoice_id}</th><td><a href='index.php?module=invoices&amp;view=quick_view&amp;id={$payment.ac_inv_id|htmlsafe}&amp;action=view'>{$payment.ac_inv_id|htmlsafe}</a></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th>{$LANG.amount}</th><td>{$payment.ac_amount|siLocal_number}</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th>{$LANG.date_upper}</th><td>{$payment.date|htmlsafe}</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th>{$LANG.biller}</th><td colspan="3">{$payment.biller|htmlsafe}</td>
    </tr>
    <tr>
      <th>{$LANG.customer}</th><td>{$payment.customer|htmlsafe}</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th>{$LANG.payment_type}</th><td>{$paymentType.pt_description|htmlsafe}</td>
      <th>{$LANG.check_number}</th>
      <td>{if strtolower($paymentType.pt_description)=="check"}{$payment.ac_check_number|htmlsafe}{/if}</td>
    </tr>
    <tr>
      <th>{$LANG.online_payment_id}</th><td>{$payment.online_payment_id|htmlsafe}</td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <th>{$LANG.notes}</th><td colspan="3">{$payment.ac_notes|outhtml}
    </tr>
  </table>

  <div class="si_toolbar si_toolbar_form">
    <a href="./index.php?module=payments&view=manage" class="negative"><img src="./images/common/cross.png" alt="" />{$LANG.cancel}</a>
  </div>
</div>

