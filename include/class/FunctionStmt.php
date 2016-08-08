<?php
class FunctionStmt {
    const OPERATORS = '/^(\-|\+|\*|\/)$/';
    
    private $function;
    private $parameter;
    private $alias;
    private $parts;

    /**
     * Class constructor.
     * @param string $function
     * @param string $parameter
     * @param string $alias (Optional)
     */
    public function __construct($function, $parameter, $alias=null) {
        $this->function = $function;
        $this->parameter = $parameter;
        $this->alias = $alias;
        $this->parts = null;
    }

    /**
     * Add another part of the function.
     * @param string $operator
     * @param string $part
     * @throws PdoDbException
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
     * @return string Function string.
     */
    public function build() {
//  (SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) - COALESCE(ap.amount,0)) AS owing,
        $stmt = $this->function . "(" . $this->parameter . ")";
        if (!empty($this->parts)) {
            foreach($this->parts as $part) {
                $stmt = "(" . $stmt . ") " . $part[0] . " " . $part[1];
            }
        }
        if (isset($this->alias)) {
            $stmt = "(" . $stmt . ") AS " . $this->alias;
        }
        return $stmt;
    }
}
