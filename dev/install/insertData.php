<?php include("header.php");


        $server = trim($_POST['server']);
	if (!in_array($server, array('pgsql', 'mysql'), true)) {
		die($LANG['invalidDBServer']);
	}
        $_SESSION['server'] = $server;

        $host = trim($_POST['host']);
        $_SESSION['host'] = $host;

        $dbname = trim($_POST['dbname']);
	if (!preg_match('/^[-_a-z0-9]+$/Di', $dbname)) {
		die($LANG['unableSelectDb']);
	}
        $_SESSION['dbname'] = $dbname;

        $username = trim($_POST['username']);
        $_SESSION['username'] = $username;

        $passwd = trim($_POST['passwd']);
        $_SESSION['passwd'] = $passwd;

        $table_prefix = trim($_POST['prefix']);
        $_SESSION['table_prefix'] = $table_prefix;

        
        // connection
	if ($server == 'pgsql') {
		$conDB = 'postgres';
	} elseif ($server == 'mysql') {
		$conDB = 'mysql';
	}
	$dbh = new PDO("$server:host=$host;dbname=$conDB", $username, $passwd) or die($LANG['unableConnectDb']);
        
        // Select database server version
	$version = $dbh->query('SELECT version() AS v');
        if ($server == 'mysql' && preg_match('/^5\..*/', $version['v'])) {
            $mysql5_create_table = "sql/simpleinvoices.sql";
            //sql query to create tables
            $sql_version = $mysql5_create_table;
            $_SESSION['sql_version'] = $sql_version;
        } elseif ($server == 'pgsql' && preg_match('/^PostgreSQL 8\.[^0].*/')) {
            $pgsql_create_table = "sql/simpleinvoices-pgsql.sql";
            //sql query to create tables
            $sql_version = $pgsql_create_table;
            $_SESSION['sql_version'] = $sql_version;
        }
        
        
        function parse_simple_dump($url, $ignoreerrors = false)
        {
            global $dbh;
            $file_content = file($url);
            //print_r($file_content);
            $query = "";
            foreach($file_content as $sql_line) {
                $tsl = trim($sql_line);
                if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
                    $query .= $sql_line;
                    if (preg_match("/;\s*$/", $sql_line)) {
                        $dbh->exec($query);
                        if ($dbh->errorCode() && !$ignoreerrors) {
                            die(htmlspecialchars(end($dbh->errorInfo())));
                        }
                        $query = "";
                    }
                }
            }
        }
        
        // Form action DB
        $submit_array = array_keys($_POST['submit']);
        $action = $submit_array[0];
        switch ($action) {
        case 'create':
            // Created a new DB, $dbname is subject to a character whitelist
	    $dbh->exec('CREATE DATABASE '.$dbname) or die($LANG['existingDb'].htmlspecialchars(end($dbh->errorInfo)));
	    $dbh = null;
            
            // Select this new DB
	    $dbh = new PDO("$server:host=$host;dbname=$dbname", $username, $passwd) or die($LANG['unableSelectDb']);
            // Create tables
            parse_simple_dump($sql_version, $ignoreerrors = false);
            break;
            
        case 'drop':
            // Select an existing DB
	    $dbh = null;
	    $dbh = new PDO("$server:host=$host;dbname=$dbname", $username, $passwd) or die($LANG['unableSelectDb']);
            // Drop tables
            $dropTables = "sql/drop.sql";
            parse_simple_dump($dropTables, $ignoreerrors = false);
            
            // Create tables
            parse_simple_dump($sql_version, $ignoreerrors = false);
            break;
        }
        
        // close connection
        $dbh = null;


?>
			<form name="insertion" method="post" action="insertion.php">
			<p>
				<input type="submit" name="submit[insertNo]" value="<?php echo $LANG['insertDataNo'] ?>" />
				<input type="submit" name="submit[insertYes]" value="<?php echo $LANG['insertDataYes'] ?>" /> 
			</p>
			</form>				
				
			<hr />

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

<?php include("footer.php"); ?>
