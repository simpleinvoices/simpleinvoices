<?php
// $Header: /cvsroot/html2ps/box.img.php,v 1.50 2007/05/06 18:49:29 Konstantin Exp $

define('SCALE_NONE',0);
define('SCALE_WIDTH',1);
define('SCALE_HEIGHT',2);

class GenericImgBox extends GenericInlineBox {
  function GenericImgBox() {
    $this->GenericInlineBox();
  }

  function get_max_width_natural(&$context) {
    return $this->get_full_width($context);
  }

  function get_min_width(&$context) { 
    return $this->get_full_width(); 
  }

  function get_max_width(&$context) { 
    return $this->get_full_width(); 
  }

  function is_null() { 
    return false; 
  }

  function pre_reflow_images() {
    switch ($this->scale) {
    case SCALE_WIDTH:
      // Only 'width' attribute given
      $size = 
        $this->src_width/$this->src_height*
        $this->get_width();
      
      $this->put_height($size);
    
      // Update baseline according to constrained image height
      $this->default_baseline = $this->get_full_height();
      break;
    case SCALE_HEIGHT:
      // Only 'height' attribute given
      $size = 
        $this->src_height/$this->src_width*
        $this->get_height();

      $this->put_width($size);
      $this->setCSSProperty(CSS_WIDTH, new WCConstant($size));

      $this->default_baseline = $this->get_full_height();
      break;
    };
  }

  function readCSS(&$state) {
    parent::readCSS($state);

    // '-html2ps-link-target'
    global $g_config;
    if ($g_config["renderlinks"]) {
      $this->_readCSS($state, 
                      array(CSS_HTML2PS_LINK_TARGET));
    };
  }

  function reflow_static(&$parent, &$context) {  
    $this->pre_reflow_images();
    
    GenericFormattedBox::reflow($parent, $context);
  
    // Check if we need a line break here
    $this->maybe_line_break($parent, $context);

    // set default baseline
    $this->baseline = $this->default_baseline; 

    // append to parent line box
    $parent->append_line($this);

    // Move box to the parent current point
    $this->guess_corner($parent);

    // Move parent's X coordinate
    $parent->_current_x += $this->get_full_width();

    // Extend parent height
    $parent->extend_height($this->get_bottom_margin());
  }

  function _get_font_name(&$driver, $subword_index) {
    if (isset($this->_cache[CACHE_TYPEFACE][$subword_index])) {
      return $this->_cache[CACHE_TYPEFACE][$subword_index];
    };

    $font_resolver =& $driver->get_font_resolver();

    $font = $this->getCSSProperty(CSS_FONT);
    $typeface = $font_resolver->getTypefaceName($font->family, 
                                                $font->weight, 
                                                $font->style, 
                                                'iso-8859-1');

    $this->_cache[CACHE_TYPEFACE][$subword_index] = $typeface;

    return $typeface;
  }

  function reflow_text(&$driver) {
    // In XHTML images are treated as a common inline elements; they are affected by line-height and font-size
    global $g_config;
    if ($g_config['mode'] == 'xhtml') {
      /**
       * A simple assumption is made: fonts used for different encodings
       * have equal ascender/descender values  (while they have the same
       * typeface, style and weight).
       */
      $font_name = $this->_get_font_name($driver, 0);

      /**
       * Get font vertical metrics
       */
      $ascender  = $driver->font_ascender($font_name, 'iso-8859-1');
      if (is_null($ascender)) {
        error_log("ImgBox::reflow_text: cannot get font ascender");
        return null;
      };

      $descender = $driver->font_descender($font_name, 'iso-8859-1'); 
      if (is_null($descender)) {
        error_log("ImgBox::reflow_text: cannot get font descender");
        return null;
      };

      /**
       * Setup box size
       */
      $font = $this->getCSSProperty(CSS_FONT_SIZE);
      $font_size       = $font->getPoints();

      $this->ascender         = $ascender  * $font_size;
      $this->descender        = $descender * $font_size;
    } else {
      $this->ascender = $this->get_height();
      $this->descender = 0;
    };

    return true;
  }

  // Image boxes are regular inline boxes; whitespaces after images should be rendered
  // 
  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    $linebox_started = true;
    $previous_whitespace = false;
    return;
  }

  function show_fixed(&$driver) {
    return $this->show($driver);
  }
}

class BrokenImgBox extends GenericImgBox {
  var $alt;

  function BrokenImgBox($width, $height, $alt) {
    $this->scale = SCALE_NONE;
    $this->encoding = DEFAULT_ENCODING;

    // Call parent constructor
    $this->GenericImgBox();

    $this->alt = $alt;
  }  

  function show(&$driver) {
    $driver->save();

    // draw generic box
    GenericFormattedBox::show($driver);

    $driver->setlinewidth(0.1);
    $driver->moveto($this->get_left(),  $this->get_top());
    $driver->lineto($this->get_right(), $this->get_top());
    $driver->lineto($this->get_right(), $this->get_bottom());
    $driver->lineto($this->get_left(),  $this->get_bottom());
    $driver->closepath();
    $driver->stroke();

    if (!$GLOBALS['g_config']['debugnoclip']) {
      $driver->moveto($this->get_left(),  $this->get_top());
      $driver->lineto($this->get_right(), $this->get_top());
      $driver->lineto($this->get_right(), $this->get_bottom());
      $driver->lineto($this->get_left(),  $this->get_bottom());
      $driver->closepath();
      $driver->clip();
    };

    // Output text with the selected font
    $size = pt2pt(BROKEN_IMAGE_ALT_SIZE_PT);

    $status = $driver->setfont("Times-Roman", "iso-8859-1", $size);
    if (is_null($status)) {
      return null;
    };

    $driver->show_xy($this->alt, 
                     $this->get_left() + $this->width/2 - $driver->stringwidth($this->alt, 
                                                                               "Times-Roman", 
                                                                               "iso-8859-1",
                                                                               $size)/2, 
                     $this->get_top()  - $this->height/2 - $size/2);

    $driver->restore();

    $strategy =& new StrategyLinkRenderingNormal();
    $strategy->apply($this, $driver);

    return true;
  }
}

class ImgBox extends GenericImgBox {
  var $image;
  var $type; // unused; should store the preferred image format (JPG / PNG)

  function ImgBox($img) {
    $this->encoding = DEFAULT_ENCODING;
    $this->scale = SCALE_NONE;

    // Call parent constructor
    $this->GenericImgBox();

    // Store image for further processing
    $this->image = $img;
  }

  function &create(&$root, &$pipeline) {
    // Open image referenced by HTML tag
    // Some crazy HTML writers add leading and trailing spaces to SRC attribute value - we need to remove them
    //
    $url_autofix = new AutofixUrl();
    $src = $url_autofix->apply(trim($root->get_attribute("src")));

    $image_url = $pipeline->guess_url($src);
    $src_img = Image::get($image_url, $pipeline);

    if (is_null($src_img)) {
      // image could not be opened, use ALT attribute
      
      if ($root->has_attribute('width')) {
        $width = px2pt($root->get_attribute('width'));
      } else {
        $width = px2pt(BROKEN_IMAGE_DEFAULT_SIZE_PX);
      };

      if ($root->has_attribute('height')) {
        $height = px2pt($root->get_attribute('height'));
      } else {
        $height = px2pt(BROKEN_IMAGE_DEFAULT_SIZE_PX);
      };

      $alt = $root->get_attribute('alt');

      $box =& new BrokenImgBox($width, $height, $alt);

      $box->readCSS($pipeline->getCurrentCSSState());

      $box->put_width($width);
      $box->put_height($height);
      
      $box->default_baseline = $box->get_full_height();
      
      $box->src_height = $box->get_height();
      $box->src_width  = $box->get_width();
      
      return $box;
    } else {
      $box =& new ImgBox($src_img);
      $box->readCSS($pipeline->getCurrentCSSState());
      $box->_setupSize();
     
      return $box;
    }
  }

  function _setupSize() {
    $this->put_width(px2pt(imagesx($this->image)));
    $this->put_height(px2pt(imagesy($this->image)));
    $this->default_baseline = $this->get_full_height();
     
    $this->src_height = imagesx($this->image);
    $this->src_width  = imagesy($this->image);

    $wc = $this->getCSSProperty(CSS_WIDTH);
    $hc = $this->get_height_constraint();

    // Proportional scaling 
    if ($hc->is_null() && !$wc->isNull()) {
      $this->scale = SCALE_WIDTH;

      // Only 'width' attribute given
      $size = 
        $this->src_width/$this->src_height*
        $this->get_width();
        
      $this->put_height($size);
        
      // Update baseline according to constrained image height
      $this->default_baseline = $this->get_full_height();
        
    } elseif (!$hc->is_null() && $wc->isNull()) {
      $this->scale = SCALE_HEIGHT;

      // Only 'height' attribute given
      $size = 
        $this->src_height/$this->src_width*
        $this->get_height();
        
      $this->put_width($size);
      $this->setCSSProperty(CSS_WIDTH, new WCConstant($size));
        
      $this->default_baseline = $this->get_full_height();
    };
  }

  function show(&$driver) {
    // draw generic box
    GenericFormattedBox::show($driver);

    // Check if "designer" set the height or width of this image to zero; in this there will be no reason 
    // in drawing the image at all
    //
    if ($this->get_width() < EPSILON ||
        $this->get_height() < EPSILON) {
      return true;
    };

    $driver->image_scaled($this->image, 
                          $this->get_left(), $this->get_bottom(),
                          $this->get_width() / imagesx($this->image), $this->get_height() / imagesy($this->image));

    $strategy =& new StrategyLinkRenderingNormal();
    $strategy->apply($this, $driver);

    return true;
  }
}
?>