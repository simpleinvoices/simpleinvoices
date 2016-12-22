{include file=$path|cat:'inc_head.tpl'}

<div style="margin:0 auto 40px auto;width:50%;text-align:left;">
  <p>The SimpleInvoices essential data has been imported. Using the buttons
     below, you can choose to <strong>Start using SimpleInvoices</strong> or
     to <strong>Install Sample Data</strong> to test SimpleInvoices further.</p>
  <p><strong>NOTE:</strong> If the <strong><em>authentication.enabled</em></strong>
     setting in the configuration file, <strong>{$config_file_path}</strong>,
     is set to <strong>true</strong>. You will need to use the demonstration ID,
     <strong>demo@simpleinvoices.org</strong>, and the password,
     <strong>demo</strong>.</p>
</div>
<div class="si_toolbar si_toolbar_form">
  <a href="./index.php" class="positive">
    <img src="./images/common/tick.png" alt="" />
    Start using SimpleInvoices
  </a>
  <a href="./index.php?module=install&amp;view=sample_data" class="positive">
    <img src="./images/common/tick.png" alt="" />
    Install Sample Data
  </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
