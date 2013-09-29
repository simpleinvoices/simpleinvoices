<?php


/**
 * @author Konstantin Bournayev
 * @version 1.0
 * @created 24-џэт-2006 20:56:23
 */
class PSImageEncoderStream
{
  var $last_image_id;

  // Generates new unique image identifier
  // 
  // @return generated identifier
  //
  function generate_id()
	{
    	$this->last_image_id ++;
    	return $this->last_image_id;
	}

}

/**
 * @created 24-џэт-2006 20:56:23
 * @author Konstantin Bournayev
 * @version 1.0
 * @updated 24-џэт-2006 21:19:35
 */
class PSImageEncoder
{

	var $last_image_id;

	function __construct()
	{
	}



	/**
	 * Generates new unique image identifier
	 * @return generated identifier
	 */
	function generate_id()
	{
	}

}
?>