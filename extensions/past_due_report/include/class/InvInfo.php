<?php
/**
 * InvInfo class with invoice information.
 * @author Rich
 */
class InvInfo {
    public $id;
    public $billed;
    public $paid;
    public $owed;
    
    public function __construct($id, $billed, $paid, $owed) {
        // @formatter:off
        $this->id     = $id;
        $this->billed = $billed;
        $this->paid   = $paid;
        $this->owed   = $owed;
        // @formatter:on
    }
}