<html>
<head>
<?php
// Create a new Encryption Object
$enc = new Encryption();
$encstr = '';
$decstr = '';
if (isset($_POST['encrypt'])) {
    $key = $_POST['keystr'];
    // Encrypt the Source Text
    $encstr = $enc->encrypt($key, $_POST['text']);
} elseif (isset($_POST['decrypt'])) {
    $encstr = $_POST['enctext'];
    $key = $_POST['keystr'];
    // Decrypt the Encrypted Text
    $decstr = $enc->decrypt($key, $_POST['enctext']);
}
?>
</head>
<body>
<form action = '<?php echo urlsafe($_SERVER['PHP_SELF']) ?>' method = 'post'>
<br/>
Original Text
<br/>
<textarea name = 'text' cols="40" rows="8" wrap="soft">Welcome to the Real World.</textarea>
<br/>
Enter Key String
<br/>
<input name = 'keystr' type = 'text' value = 'halih'/>
<br/>
<input type='submit' value = 'Encrypt' name='encrypt'/>
<input type='submit' value = 'Decrypt' name='decrypt'/>
<br/>Encrypted Text<br/>
<textarea name = 'enctext' cols="40" rows="8" wrap="soft">
<?php
if (isset($_POST['encrypt']) || isset($_POST['decrypt']))
    echo htmlsafe($encstr);
?>
</textarea>
<br/>
Decrypted Text
<br/>
<textarea name = 'dectext' cols="40" rows="8" wrap="soft">
<?php
if (isset($_POST['encrypt']) || isset($_POST['decrypt']))
    echo htmlsafe($decstr);
?>
</textarea>
</form>
</body>
</html>
