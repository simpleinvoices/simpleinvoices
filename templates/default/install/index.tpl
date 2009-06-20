<img src="images/common/simple_invoices_logo.jpg"/>
<br/>
<br/>

<table align="center">
<tr><td>
<div id="money" class="ui-tabs-panel" style="">
    <ul class="subnav">
        <li>
            <a class="active" href="index.php?module=install&view=index">Simple Invoices installer</a>
        </li>
        <li>
            <a class="active active_subpage" href="index.php?module=install&view=structire">Step 1: Install Database</a>
        </li>
        <li>
            <a href="index.php?module=install&view=essential">Step 2: Import essentail data</a>
        </li>
        <li>
            <a href="index.php?module=install&view=sample_data">Step 3: Import sample data</a>
        </li>
    </ul>
</div>
</td></tr>
</table>
<br />
<br />
To install Simple Invoices please
            <br />1. Create a blank MySQL database
            <br />2. Enter the correct database connection details in the config/config.ini file
            <br />3. Review the connection details below and if correct click the 'Install Database' button
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
                Install Database
            </a>
    
        </td>
    </tr>
</table>
