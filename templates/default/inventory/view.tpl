<div class="si_form si_form_view">
  <table>
    <tr>
      <th>{$LANG.date_upper}</th>
      <td>{$inventory.date|htmlsafe}</td>
    </tr>
    <tr>
      <th>{$LANG.product}</th>
      <td>{$inventory.description|htmlsafe}</td>
    </tr>
    <tr>
      <th>{$LANG.quantity}</th>
      <td>{$inventory.quantity|siLocal_number_trim}</td>
    </tr>
    <tr>
      <th>{$LANG.cost}</th>
      <td>{$inventory.cost|siLocal_number}</td>
    </tr>
    <tr>
      <th>{$LANG.notes}</th>
      <td>{$inventory.note}</td>
    </tr>
  </table>
</div>
<div class="si_toolbar si_toolbar_form">
  <a href="index.php?module=inventory&amp;view=edit&amp;id={$inventory.id|urlencode}"
     class="positive"> <img src="images/famfam/report_edit.png" alt="" />
    {$LANG.edit}
  </a>
  <a href="index.php?module=inventory&view=manage"
     class="negative"> <img src="images/common/cross.png" alt="" />
    {$LANG.cancel}
  </a>
</div>
