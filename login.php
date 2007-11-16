<?php

/*
* Script: login.php
* 	Login page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/

// we must never forget to start the session
//so config.php works ok without using index.php define browse
define("BROWSE","browse");
include 'config/config.php';
include 'include/sql_queries.php';

session_start();

$errorMessage = '';
if (isset($_POST['user']) && isset($_POST['pass'])) {

    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);

    $userEmail   = $_POST['user'];
    $password = $_POST['pass'];
    
    // check if the user id and password combination exist in database
    $sql = "SELECT user_id 
            FROM ".TB_PREFIX."users
            WHERE user_email = '$userEmail' AND user_password = md5('$password')";
    
    $result = mysql_query($sql, $conn) or die('Query failed. ' . mysql_error()); 
    
    if (mysql_num_rows($result) == 1) {
        // the user id and password match, 
        // set the session
        $_SESSION['db_is_logged_in'] = true;
        
        // after login we move to the main page
	header('Location: .');
        exit;
    } else {
        $errorMessage = 'Sorry, wrong user / password';
    }
    
} 

?>
<html>
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Invoices Login</title>
<link rel="stylesheet" type="text/css" href="./templates/default/css/login.css">

</head>

<body class="login" >
	<div class="Container">


<?php

if ($errorMessage != '') {
?>
<p align="center"><strong><font color="#990000"><?php echo $errorMessage; ?></font></strong></p>
<?php
}
?>
<div id="Dialog">

<h1>Simple Invoices</h1>


	    <form action="" method="post" name="frmLogin" id="frmLogin" <?php if($MD5Auth==True){?>onSubmit="return login(this);"<?php }?>>
	        <input type="hidden" name="action" value="login" />
		<input type="hidden" name="cookieverify" value="" />
		<input type="hidden" name="redirect" value="" />

                <?php if($MD5Auth==True){?>
                <input type="hidden" name="md5" value="">
                <?php }?>		            
                <?php if($ChallengeLife>0){?>
                <input type="hidden" name="ChallengeKey" value="<?php echo $Challenge_Key;?>">
                <?php }?>		            

  	<dl>
  		<dt>Email:</dt>
  		<dd><input name="user" type="text" id="user" value="" /></dd>

  		<dt>Password:</dt>
  		<dd>
  		  <input name="pass" type="password" id="pass" value="" />
			<!--
  		  <span>(<a href="login.php">I forgot my password/username</a>)</span>
			-->
  		</dd>
<!--TODO add language select drop down here -->
<!--
      		<dd><input type="checkbox" name="remember_me" /> Remember me on this computer</dd>
-->
		<dd> <input type="submit" value="login" /></dd>


<!--  		<dd><input type="submit" value="Sign in" /></dd> -->
<!--
	        <label for="username">Username</label>
		<input  name="user" type="text" id="user"/>
			<BR>
		<label for="password">Password</label>
		<input name="pass" type="password" id="pass"/>
-->
		<!--
		<label for="language">Language</label>
		<select id="language" name="language">
				    <option value="en" SELECTED="yes">English (United States)</option>

				</select>
               	-->
<!--
	        <div class="form_actions">
                   <dd> <input type="submit" value="login" /></dd>
		</div>
-->
  	</dl>
            </form>

	</div>
        
		<dd>Powered by <a href="http://www.simpleinvoices.org">Simple Invoices</a></dd>

    </div>

</body>
</html> 
