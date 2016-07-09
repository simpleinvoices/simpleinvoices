<?php

/**
 * class Dbinfo
 * Contains the database connection information.
 * @author Rich Rowley
 */
class DbInfo {
    private $dbname;
    private $dbtype;
    private $host;
    private $password;
    private $admin;

    /**
     * Class constructor
     * @param string $dbtype Only <b>mysql</b> supported at this time.
     * @param string $dbname Name of database.
     * @param string $host URL for host. Ex: <b>localhost</b>.
     * @param string $password Password assigned to the <b>$admin</b>.
     * @param string $admin Admin user ID associated with <b>$password</b>.
     */
    public function __construct($dbtype, $dbname, $host, $password, $admin) {
        // @formatter:off
        $this->admin    = $admin;
        $this->dbtype   = $dbtype;
        $this->dbname   = $dbname;
        $this->host     = $host;
        $this->password = $password;
        // @formatter:on
    }

    /**
     * getter for class property.
     * @return string $admin
     */
    public function getAdmin() {
        return $this->admin;
    }

    /**
     *
     * getter for class property.
     * @return string $dbtype
     */
    public function getDbtype() {
        return $this->dbtype;
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
}
