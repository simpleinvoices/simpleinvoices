{include file=$path|cat:'inc_head.tpl'}

<div style="width:50%;margin:0 auto 30px auto;">
  <ul>
    <li>The Simple Invoices database has now been installed.</li>
    <li>The next step is to import the essential data.</li>
    <li>Click the 'Install Essential Data' button below to continue the installation.</li>
  </ul>
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
