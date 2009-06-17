Simple Invoices installation
<br />
<br />
Please create a blank database on your database server and enter the correct database connection details in you config/config.ini file
<br />
Review review the connection details below,  if correct click 'Install DB' button
<br />
<br />
<b>Database</b>
<br />
Host: {$config->database->params->host}<br />
Database: {$config->database->params->dbname}<br />
Username: {$config->database->params->username}<br />
Password: {$config->database->params->password}<br />
<br>
<table class="buttons" align="center">
    <tr>
        <td>
        
            <a href="./index.php?module=install&amp;view=structure" class="positive">
                <img src="./images/common/tick.png" alt="" />
                Install DB
            </a>
    
        </td>
    </tr>
</table>
