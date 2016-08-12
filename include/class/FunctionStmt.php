<?php
class FunctionStmt {
    const OPERATORS = '/^(\-|\+|\*|\/)$/';
    
    private $function;
    private $parameter;
    private $alias;
    private $parts;

    /**
     * Class constructor.
     * @param string $function Function to be performed. Can be set to <b>null</b> if necessary.
     * @param mixed $parameter The first parameter of the function. Can be a string
     * @param string $alias (Optional) Name to assign to the function result.
     */
    public function __construct($function, $parameter, $alias=null) {
        $this->function = $function;
        $this->parameter = $parameter;
        $this->alias = $alias;
        $this->parts = null;
    }

    /**
     * Add another part of the function.
     * @param string $operator Math operator value is <b>+</b>, <b>-</b>, <b>*</b> or <b>/</b>.
     * @param mixed $part <b>DbField</b> object ot <b>string</b>.
     * @throws PdoDbException if an invalid <b>$operator</b> is specified.
     */
    public function addPart($operator, $part) {
        if (!preg_match(self::OPERATORS, $operator)) {
            $str = "FunctionStmt - addPart(): Invalid operator, $operator.";
            error_log($str);
            throw new PdoDbException($str);
        }

        if (!isset($this->parts)) $this->parts = array();
        $this->parts[] = array($operator, $part);
    }

    /**
     * Build function string from specified parameter.
     * @param $keypairs (Optional) parameter. It is <b>not</b> used in this function. It is
     *        included to maintain call consistency but can be ommitted if needed.
     * @return string Function string.
     */
    public function build($keypairs = null) {
        if (is_a($this->parameter, "DbField")) {
            $parm = $this->parameter->genParm();
        } else {
            $parm = $this->parameter;
        }

        if (empty($this->function)) {
            $stmt = $parm;
        } else {
            $stmt = $this->function . "(" . $parm . ")";
        }
        if (!empty($this->parts)) {
            foreach($this->parts as $part) {
                if (is_a($part[1], "DbField")) {
                    $parm = $part[1]->genParm();
                } else {
                    $parm = $part[1];
                }
                $stmt = "(" . $stmt . ") " . $part[0] . " " . $parm;
            }
        }
        if (isset($this->alias)) {
            $stmt = "(" . $stmt . ") AS " . $this->alias;
        }
        return $stmt;
    }
}
