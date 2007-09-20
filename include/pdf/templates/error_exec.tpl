<html>
<head>
<title>html2ps/html2pdf error message</title>
<style>
body {
  color:#000;
  background-color:#fff;
  margin:10px;
  font-family:arial, helvetica, sans-serif;
  color:#000;
  font-size:12px;
  line-height:18px;
}
p,td {
  color:#000;
  font-size:12px;
  line-height:18px;
  margin-top:3px;
  vertical-align: top;
}
h1 {
  font-family:arial, helvetica, sans-serif;
  color:#669;
  font-size:27px;
  letter-spacing:-1px;
  margin-top:12px;
  margin-bottom:12px;
}
tr.odd {
  background-color: #f0f0f0;
}
tr.even {
  background-color: #ffffff;
}
td {
  padding: 3px;
}
</style>
</head>
<body>
<h1>Error during 'exec'</h1>
<p>
Error executing the following command:<br/>
<code><?php echo $_cmd; ?></code>.
<p>
<table>
<tr class="odd"> 
<th width="20%">Problem</th><th>Solution</th>
</tr>
<tr class="even">
<td>'exec' function is disabled (please note that it have nothing to do with the PHP <i>safe mode</i>; 
particular functions can be disabled even when <i>safe mode</i> is OFF).</td>
<td>Enable 'exec' function in your php.ini (refer your PHP manual or <a href="http://www.php.net">www.php.net</a> for exact instructions)</td>
</tr>
<tr class="odd">
<td>Executable is missing on your server.</td>
<td>Check and update paths to executable files in script configuration</td>
</tr>
<tr class="even">
<td>Script cannot find path to your executable in system PATH variable.</td>
<td>Check PATH variable. Please take into account that PHP may run under different user account than yours, so it may have its own PATH value.
Do not forget to restart Apache after you've made changes to system variables.</td>
</tr>
<tr class="odd">
<td rowspan="2">safe_mode is On and executable is not in your safe_mode_exec_dir.</td>
<td>Disable safe_mode OR update safe_mode_exec_dir value OR move the executable (and, most probably, its dependent files) to safe_mode_exec_dir.</td>
</tr>
</table>
</body>
</html>              
