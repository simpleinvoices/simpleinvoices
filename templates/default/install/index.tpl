{include file=$path|cat:'inc_head.tpl'}
 
<table class="center" style="width:60%">
  <tr>
    <th style="font-weight: bold;">
      To install SimpleInvoices please:
    </th>
  </tr>
  <tr>
    <th style="font-weight:normal;">
      <ol>
        <li>Create a blank MySQL database preferably with UTF-8
            collation.</li>
        <li>Enter the correct database connection details in the
            <strong><em>{$config_file_path}</em></strong> file.
            {if $config_file_path == "config/config.php"}
            <p style="margin: 0 0 0 10px;">
            <strong>NOTE:</strong> You can copy the <strong>config.php</strong>
            file to a file named <strong>custom.config.php</strong> and make
            your changes to it. The advantage is that future updates to SimpleInvoices
            will not write over the <strong>custom.config.php</strong> file; thus
            preserving your settings.
            </p>
            {/if}
        </li>
        <li>Review the connection details below and if correct,
            click the <strong>Install Database</strong> button.</li>
      </ol>
    </th>
  </tr>
  <tr>
    <th style="text-align: center; font-weight: bold; text-decoration: underline;">
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
