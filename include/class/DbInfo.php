<?php
require_once 'include/class/MyCrypt.php';

/**
 * class Dbinfo
 * Contains the database connection information.
 * @author Rich Rowley
 */
class DbInfo {
    private $dbname;
    private $adapter;
    private $host;
    private $password;
    private $port;
    private $sectionname;
    private $username;

    /**
     * Class constructor
     * @param string $filepath File path including name for the file that
     *        that contains the secured information.
     * @param string $sectionname Name of secured information file section
     *        with database information to be used.
     * @param string (Optional) $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     */
    public function __construct($filepath, $sectionname, $prefix=null) {
        $this->adapter  = "mysql";
        $this->dbname   = null;
        $this->host     = null;
        $this->password = null;
        $this->port     = "3306";
        $this->username = null;
        $this->loadSectionInfo($filepath, $sectionname, $prefix);
    }

    /**
     * Load and decrypt the database parameters for the specified section.
     * @param string $filepath Path for file containing the encrypted information.
     * @param string $sectionname Name of section to load database parameters from. The section
     *        is in the form, <b>[sectionname]</b>.
     * @param string $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @throws PdoException If unable to open the file and file the section name and data to decrypt.
     */
    public function loadSectionInfo($filepath, $sectionname, $prefix) {
        global $site_id;

        $this->filepath = $filepath;
        $this->sectionname = (is_string($sectionname) ? $sectionname : $site_id);
        if (($secure_info = file($this->filepath, FILE_USE_INCLUDE_PATH)) === false) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Unable to open the secure information file, $this->filepath");
        }

        $found = false;
        $i = 0;

        // Find the section to use
        while(!$found && $i < count($secure_info)) {
            $line = trim($secure_info[$i]);
            // Search for pattern (sans quotes): "   [xA0_ -.]". Ex: "   [Section_A 1]"
            if (preg_match('/^ *\[[a-zA-Z0-9_ \-\.]+\]/', $line) === 1) {
                $beg = strpos($line, '[') + 1;
                $len = strpos($line, ']') - $beg;
                $section = substr($line, $beg, $len);
                $found = ($section == $this->sectionname);
            }
            $i++;
        }

        if (!$found) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Section, $this->sectionname, not found.");
        }

        $key = MyCrypt::keygen($this->sectionname); // Create secure key from section  name
        while($i < count($secure_info)) {
            $line = trim($secure_info[$i]);
            if (strstr($line,"=") === false) break;

            $parts = explode("=", $line);
            if (count($parts) != 2) break;

            $pieces = MyCrypt::unjoin($line, $prefix);
            if (preg_match('/^(adapter|dbname|host|password|port|username)$/', $pieces[0])) {
                if (strlen($pieces[1]) < 60) {
                    $decrypt = preg_replace('/\'/', '', $pieces[1]);
                } else {
                    $decrypt = MyCrypt::decrypt($pieces[1], $key);
                }

                switch ($pieces[0]) {
                    case 'adapter':
                        if (preg_match('/^pdo_/', $decrypt) == 1) {
                            $this->adapter = substr($decrypt, 4);
                        } else {
                            $this->adapter = $decrypt;
                        }
                        break;

                    case 'dbname':
                        $this->dbname = $decrypt;
                        break;

                    case 'host':
                        $this->host = $decrypt;
                        break;

                    case 'password':
                        $this->password = $decrypt;
                        break;

                    case 'port':
                        $this->port = $decrypt;
                        break;

                    case 'username':
                        $this->username = $decrypt;
                        break;
                }
            }
            $i++;
        }

        if (empty($this->host)) {
            $this->host = "localhost";
        }

        if (empty($this->dbname) || empty($this->password) || empty($this->username)) {
            throw new PdoDbException("DbInfo loadSectionInfo(): Missing one or more of dbname, password and username.");
        }
    }

    /**
     * getter for class property.
     * @return string $adapter
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * getter for class property.
     * @return string $dbname.
     */
    public function getDbname() {
        return $this->dbname;
    }

    /**
     * getter for class property.
     * @return string $host.
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * getter for class property.
     * @return string $password.
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * getter for class property.
     * @return string $port.
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * getter for class property.
     * @return string $sectionname.
     */
    public function getSectionname() {
        return $this->sectionname;
    }

    /**
     * getter for class property.
     * @return string $username.
     */
    public function getUsername() {
        return $this->username;
    }
}
