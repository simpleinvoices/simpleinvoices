<?php

/**
 * @package HTML2PS
 * @subpackage Document
 * Contains information about the background image to be rendered.
 *
 * If box does not have any background image it will still contain the 
 * BackgroundImage object having $_url member set to NULL.
 * 
 * @see GenericFormattedBox
 * @see CSSBackgroundImage
 
 * @link http://www.w3.org/TR/CSS21/colors.html#q2 CSS 2.1 "The background"
 */
class BackgroundImage {
  /**
   * @var string URL of the background image file (may be NULL in case no background image specified).
   * @access private
   */
  var $_url;

  /**
   * @var Resource image to be displayed 
   * @access private
   */
  var $_image;

  /**
   * Constructs new BackgroundImage object
   * 
   * @param string $url URL of the image file (or NULL of no image should be rendered at all)
   * @param resource $image image object to be displayed
   */
  function BackgroundImage($url, $image) {
    $this->_url = $url;
    $this->_image = $image;
  }

  /**
   * "Deep copy" routine; it is required for compatibility with PHP 5
   *
   * @return BackgroundImage A copy of current object
   */
  function copy() {
    return new BackgroundImage($this->_url, $this->_image);
  }

  /**
   * Checks if this value is equivalent to default value. According to CSS2, default value 
   * if the 'background-image' is 'none' - no image at all; in this case $_url member should 
   * contain NULL value.
   * 
   * @link http://www.w3.org/TR/CSS21/colors.html#propdef-background-image CSS 2 'background-image' description
   *
   * @return boolean flag indicating whether this background image value is equivalent to default value
   * 
   * @see CSSProperty::is_default()
   * @see CSSBackgroundImage::default_value()
   */
  function is_default() { 
    return is_null($this->_url); 
  }

  /**
   * Renders the backgroung image using the specified output driver.
   * 
   * @param OutputDriver $driver an output driver object
   * @param GenericFormattedBox $box an box owning this background image
   * @param int $repeat the 'background-repeat' value
   * @param BackgroundPosition $position the 'background-position' value
   *
   * @uses BackgroundPosition
   * @uses OutputDriver
   */
  function show(&$driver, $box, $repeat, $position) {
    /**
     * If no image should be rendered, just return
     * @see BackgroundImage::$_url
     */
    if ($this->_url == null) { 
      return; 
    }

    if (is_null($this->_image)) { 
      return; 
    }

    /**
     * Setup clipping region for padding area. Note that background image is drawn in the padding 
     * area which in generic case is greater than content area.
     * 
     * @see OutputDriver::clip()
     *
     * @link http://www.w3.org/TR/CSS21/box.html#box-padding-area CSS 2.1 definition of padding area
     */
    $driver->save();
    $driver->moveto($box->get_left_background(),  $box->get_top_background());
    $driver->lineto($box->get_right_background(), $box->get_top_background());
    $driver->lineto($box->get_right_background(), $box->get_bottom_background());
    $driver->lineto($box->get_left_background(),  $box->get_bottom_background());
    $driver->closepath();
    $driver->clip();

    /**
     * get real image size in device points
     *
     * @see pt2pt()
     * @see px2pt()
     */
    $image_height = px2pt(imagesy($this->_image));
    $image_width  = px2pt(imagesx($this->_image));

    /**
     * Get dimensions of the rectangle to be filled with the background image
     */
    $padding_width  = $box->get_right_background() - $box->get_left_background();
    $padding_height = $box->get_top_background() - $box->get_bottom_background();

    /**
     * Calculate the vertical offset from the top padding edge to the background image top edge using current 
     * 'background-position' value. 
     * 
     * @link file:///C:/docs/css/colors.html#propdef-background-position CSS 2 'background-position' description
     */
    if ($position->x_percentage) {
      $x_offset = ($padding_width  - $image_width)  * $position->x / 100;
    } else {
      $x_offset = $position->x;
    }

    /**
     * Calculate the horizontal offset from the left padding edge to the background image left edge using current 
     * 'background-position' value
     * 
     * @link file:///C:/docs/css/colors.html#propdef-background-position CSS 2 'background-position' description
     */
    if ($position->y_percentage) {
      $y_offset = ($padding_height - $image_height) * $position->y / 100;
    } else {
      $y_offset = $position->y;
    };

    /**
     * Output the image (probably tiling it; depends on current value of 'background-repeat') using 
     * current output driver's tiled image output functions. Note that px2pt(1) is an image scaling factor; as all
     * page element are scaled to fit the media, background images should be scaled too!
     * 
     * @see OutputDriver::image()
     * @see OutputDriver::image_rx()
     * @see OutputDriver::image_ry()
     * @see OutputDriver::image_rxry()
     *
     * @link file:///C:/docs/css/colors.html#propdef-background-repeat CSS 2.1 'background-repeat' property description
     */
    switch ($repeat) {
    case BR_NO_REPEAT:
      /**
       * 'background-repeat: no-repeat' case; no tiling at all
       */
      $driver->image($this->_image, 
                     $box->get_left_background() + $x_offset, 
                     $box->get_top_background() - $image_height - $y_offset, 
                     px2pt(1));
      break;
    case BR_REPEAT_X:
      /**
       * 'background-repeat: repeat-x' case; horizontal tiling
       */
      $driver->image_rx($this->_image, 
                        $box->get_left_background() + $x_offset, 
                        $box->get_top_background() - $image_height - $y_offset, 
                        $image_width,
                        $box->get_right_background(),
                        $x_offset, 
                        $y_offset,
                        px2pt(1));
      break;
    case BR_REPEAT_Y:
      /**
       * 'background-repeat: repeat-y' case; vertical tiling
       */
      $driver->image_ry($this->_image, 
                        $box->get_left_background() + $x_offset, 
                        $box->get_top_background() - $image_height - $y_offset, 
                        $image_height, 
                        $box->get_bottom_background(), 
                        $x_offset,
                        $y_offset,
                        px2pt(1));
      break;
    case BR_REPEAT:
      /**
       * 'background-repeat: repeat' case; full tiling
       */
      $driver->image_rx_ry($this->_image, 
                           $box->get_left_background() + $x_offset, 
                           $box->get_top_background() - $image_height + $y_offset, 
                           $image_width,
                           $image_height,
                           $box->get_right_background(),
                           $box->get_bottom_background(),
                           $x_offset, 
                           $y_offset, 
                           px2pt(1));
      break;
    };

    /**
     * Restore the previous clipping area
     * 
     * @see OutputDriver::clip()
     * @see OutputDriver::restore()
     */
    $driver->restore();
  }
}

?>