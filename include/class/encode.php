<?php

class encode extends DomDocument
{

    public $nodeName;

    private $xpath;

    private $root;

    private $node_name;


    /**
    * Constructor, duh
    *
    * Set up the DOM environment
    *
    * @param    string    $root        The name of the root node
    * @param    string    $nod_name    The name numeric keys are called
    *
    */
    public function __construct($root='root', $node_name='node')
    {
        parent::__construct();

        /*** set the encoding ***/
        $this->encoding = "ISO-8859-1";
        /*** format the output ***/
        $this->formatOutput = true;

        /*** set the node names ***/
        $this->node_name = $node_name;

        /*** create the root element ***/
        $this->root = $this->appendChild($this->createElement( $root ));

        $this->xpath = new DomXPath($this);
    }

	public static function json($data)
	{
		return json_encode($data);
	}
    /*
    * creates the XML representation of the array
    *
    * @access    public
    * @param    array    $arr    The array to convert
    * @aparam    string    $node    The name given to child nodes when recursing
    *
    */
    public function xml( $arr, $node = null)
    {
        if (is_null($node))
        {
            $node = $this->root;
        }
        foreach($arr as $element => $value) 
        {
            $element = is_numeric( $element ) ? $this->node_name : $element;

            $child = $this->createElement($element, (is_array($value) ? null : $value));
            $node->appendChild($child);

            if (is_array($value))
            {
                self::xml($value, $child);
            }
        }
    }
    /*
    * Return the generated XML as a string
    *
    * @access    public
    * @return    string
    *
    */
    public function __toString()
    {
        return $this->saveXML();
    }

    /*
    * array2xml::query() - perform an XPath query on the XML representation of the array
    * @param str $query - query to perform
    * @return mixed
    */
    public function query($query)
    {
        return $this->xpath->evaluate($query);
    }

} // end of class

