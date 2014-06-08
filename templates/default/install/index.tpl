{include file=$path|cat:'inc_head.tpl'}

<div class="si_form">
	<table>
	<tr>
		<td colspan="2">
			<b>To install Simple Invoices please:</b>
			<br />
			<br />1. Create a blank MySQL database preferably with UTF-8 collation
			<br />2. Enter the correct database connection details in the config/config.php file
			<br />3. Review the connection details below and if correct click the 'Install Database' button
			<br />
			<br />
			<b>Database</b>
			<br />
		</td>
	</tr>
	<tr>
		<td>Host:</td><td>{$config->database->params->host}</td>
	</tr>
	<tr>
		<td>Database:</td><td>{$config->database->params->dbname}</td>
	</tr>
	<tr>
		<td>Username:</td><td>{$config->database->params->username}</td>
	</tr>
	<tr>
		<td>Password:</td><td>**********</td>
	</tr>
	</table>
</div>


<div class="si_toolbar si_toolbar_form">
		<a href="./index.php?module=install&amp;view=structure" class="positive">
			<img src="./images/common/tick.png" alt="" />
			Install Database
		</a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
