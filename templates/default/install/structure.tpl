{include file=$path|cat:'inc_head.tpl'}

<div style="margin:0 auto 40px auto;width:50%;text-align:left;">
  <p>The SimpleInvoices database tables have been created. Click the
     <strong>Install Essential Data</strong> button below to
     continue with the installation.</p>
</div>
<div class="si_toolbar si_toolbar_form">
  <a href="./index.php?module=install&amp;view=essential" class="positive">
    <img src="./images/common/tick.png" alt="" />Install Essential Data
  </a>
  <a href="./index.php" class="negative">
    <img src="./images/common/cross.png" alt="" />Cancel
  </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
