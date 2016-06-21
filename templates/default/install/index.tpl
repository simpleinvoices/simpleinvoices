{include file=$path|cat:'inc_head.tpl'}

<table class="center">
  <tr>
    <th colspan="2" style="font-weight: bold;">
      To install SimpleInvoices please:
    </th>
  </tr>
  <tr>
    <th colspan="2" style="font-weight:normal;">
      <ol>
        <li>Create a blank MySQL database preferably with UTF-8
            collation.</li>
        <li>Enter the correct database connection details in the
            <strong><em>{$config_file_path}</em></strong> file.</li>
        <li>Review the connection details below and if correct
            click the 'Install Database' button.</li>
      </ol>
    </th>
  </tr>
  <tr>
    <th colspan="2" style="text-align: center; font-weight: bold; text-decoration: underline;">
      <em>{$config_file_path} Database</em> settings
    </th>
  </tr>
</table>
<br/>
<table class="center">
  <tr>
    <th style="text-align: right; margin-right: 0;; font-weight: bold; text-decoration: underline;">
      Property
    </th>
    <th style="text-align: left; padding-left: 40px; font-weight: bold; text-decoration: underline;">
      Value
    </th>
  </tr>
  <tr>
    <td style="text-align: right; margin-right: 0;">Host</td>
    <td style="text-align: left; padding-left: 40px;">{$config->database->params->host}</td>
  </tr>
  <tr>
    <td style="text-align: right; margin-right: 0;">Database</td>
    <td style="text-align: left; padding-left: 40px;">{$config->database->params->dbname}</td>
  </tr>
    <td style="text-align: right; margin-right: 0;">Username</td>
    <td style="text-align: left; padding-left: 40px;">{$config->database->params->username}</td>
  <tr>
  <tr>
    <td style="text-align: right; margin-right: 0;">Password</td>
    <td style="text-align: left; padding-left: 40px;">**********</td>
  </tr>
</table>
<div class="si_toolbar si_toolbar_form">
  <a href="./index.php?module=install&amp;view=structure"
     class="positive"> <img src="./images/common/tick.png" alt="" />
    Install Database
  </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
