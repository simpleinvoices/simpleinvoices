<?php
require_once 'ftcc/db/PdoDb.php';
require_once 'ftcc/db/Request.php';

/**
 * Requests class
 * @author Rich
 * Apr 28, 2016
 */
class Requests {
    private $pdoDb;
    private $requests;
    private $addIds;

    /**
     * Class constructor.
     * Opens database and initializes class properties.
     */
    public function __construct() {
        $this->pdoDb = new PdoDb(new DbInfo());
        $this->reset();
    }

    /**
     * Class destructor
     */
    public function __destruct() {
        $this->pdoDb = null;
    }

    /**
     * Reset class properties.
     */
    public function reset() {
        $this->pdoDb->clearAll();
        $this->requests = array();
        $this->addIds = array();
    }

    /**
     * Turn database debug on.
     */
    public function debugOn() {
        $this->pdoDb->debugOn();
    }

    /**
     * Turn database debug off.
     */
    public function debugOff() {
        $this->pdoDb->debugOff();
    }

    /**
     * Add a <b>Request</b> to be processed.
     * @param Request $request
     * @return integer Number of request. Keep for retrieval of the
     *         record ID automatically assigned to new records assuming
     *         the table the record was added to has an auto assign field.
     */
    public function add(Request $request) {
        $this->requests[] = $request;
        return count($this->requests);
    }

    /**
     * Process all requests.
     * process
     * @throws Exception
     * @return return_type
     */
    public function process() {
        try {
            $idx = 0;
            $this->pdoDb->begin();
            foreach ($this->requests as $request) {
                $result = $request->performRequest($this->pdoDb);
                if ($request->isAdd()) {
                    $this->addIds[$idx] = $result;
                }
                $idx++;
            }
            $this->pdoDb->commit();
        } catch (Exception $e) {
            $this->pdoDb->rollback();
            $str = "Requests process(): " . $request->describe() . ". Error: " . $e->getMessage();
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Get the ID value assigned to a new record.
     * @param integer $idx Number of the request to get the ID associated with it.
     * @return integer ID assigned or 0 if no ID exists.
     */
    public function getAddId($idx) {
        if (array_key_exists($this->addIds, $idx)) return $this->addIds[$idx];
        return 0;
    }
}
?>