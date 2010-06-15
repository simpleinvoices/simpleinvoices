<HTML>

<HEAD>
<?php
include('clsencrypt.php');
// Create a new Encryption Object
$enc = new Encryption;
$encstr = '';
$decstr = '';
if (isset($HTTP_POST_VARS['encrypt'])) {
    $key = $HTTP_POST_VARS['keystr']; 
    // Encrypt the Source Text
    $encstr = $enc->encrypt($key, $HTTP_POST_VARS['text']);
} elseif (isset($HTTP_POST_VARS['decrypt'])) {
    $encstr = $HTTP_POST_VARS['enctext'];
    $key = $HTTP_POST_VARS['keystr']; 
    // Decrypt the Encrypted Text
    $decstr = $enc->decrypt($key, $HTTP_POST_VARS['enctext']);
} 

?>
</HEAD>	
	
<BODY>

<FORM action = '<?php echo urlsafe($_SERVER['PHP_SELF']) ?>' method = 'post'>
<BR>Original Text<BR>
<TEXTAREA name = 'text' cols="40" rows="8" wrap="soft">Welcome to the Real World.</TEXTAREA>
<BR>Enter Key String<BR>
<INPUT name = 'keystr' type = 'text' value = 'halih'>
<BR><Input type='submit' value = 'Encrypt' name='encrypt'><Input type='submit' value = 'Decrypt' name='decrypt'>
<BR>Encrypted Text<BR>
<TEXTAREA name = 'enctext' cols="40" rows="8" wrap="soft"><?php
if (isset($HTTP_POST_VARS['encrypt']) || isset($HTTP_POST_VARS['decrypt']))
    echo htmlsafe($encstr);

?></TEXTAREA>
<BR>Decrypted Text<BR>
<TEXTAREA name = 'dectext' cols="40" rows="8" wrap="soft"><?php
if (isset($HTTP_POST_VARS['encrypt']) || isset($HTTP_POST_VARS['decrypt']))
    echo htmlsafe($decstr);

?></TEXTAREA>
</FORM>

</BODY>

</HTML>
