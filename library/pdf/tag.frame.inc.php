<?php
// $Header: /cvsroot/html2ps/tag.frame.inc.php,v 1.19 2006/05/27 15:33:27 Konstantin Exp $

/**
 * Calculated  the actual  size of  frameset rows/columns  using value
 * specified in 'rows'  of 'cols' attribute. This value  is defined as
 * "MultiLength"; according to HTML 4.01 6.6:
 *
 * MultiLength:  The  value (  %MultiLength;  in  the  DTD) may  be  a
 * %Length; or a relative length. A relative length has the form "i*",
 * where  "i"  is an  integer.  When  allotting  space among  elements
 * competing for  that space, user  agents allot pixel  and percentage
 * lengths  first,  then divide  up  remaining  available space  among
 * relative lengths.  Each relative length  receives a portion  of the
 * available space  that is proportional to the  integer preceding the
 * "*". The  value "*" is  equivalent to "1*".  Thus, if 60  pixels of
 * space  are  available  after   the  user  agent  allots  pixel  and
 * percentage space,  and the competing  relative lengths are  1*, 2*,
 * and 3*, the 1* will be alloted 10 pixels, the 2* will be alloted 20
 * pixels, and the 3* will be alloted 30 pixels.
 *
 * @param $lengths_src String source Multilength value
 * @param $total Integer total space to be filled
 * 
 * @return Array list of calculated lengths 
 */
function guess_lengths($lengths_src, $total) {
  /**
   * Initialization: the comma-separated list is exploded to the array
   * of  distinct values,  list of  calculated lengths  is initialized
   * with default (zero) values
   */
  $lengths = explode(",",$lengths_src);
  $values  = array();
  foreach ($lengths as $length) { 
    $values[] = 0; 
  };

  /**
   * First pass: fixed-width sizes (%Length). There's two types of 
   * fixed widths: pixel widths and percentage widths
   *
   * According to HTML 4.01, 6.6:
   *
   * Length: The value  (%Length; in the DTD) may  be either a %Pixel;
   * or  a   percentage  of  the  available   horizontal  or  vertical
   * space. Thus, the value "50%" means half of the available space.
   *
   * Pixels:  The value  (%Pixels;  in  the DTD)  is  an integer  that
   * represents  the   number  of   pixels  of  the   canvas  (screen,
   * paper). Thus,  the value "50"  means fifty pixels.  For normative
   * information  about  the definition  of  a  pixel, please  consult
   * [CSS1].
   */
  for($i=0; $i < count($lengths); $i++) {
    /**
     * Remove leading/trailing spaces from current text value
     */
    $length_src = trim($lengths[$i]);
    
    if (substr($length_src,strlen($length_src)-1,1) == "%") {
      /**
       * Percentage value
       */
      $fraction = substr($length_src, 0, strlen($length_src)-1) / 100;
      $values[$i] = $total * $fraction;

    } elseif (substr($length_src,strlen($length_src)-1,1) != "*") {
      /**
       * Pixel value
       */
      $values[$i] = px2pt($length_src);
    };
  };

  // Second pass: relative-width columns
  $rest = $total - array_sum($values);

  $parts = 0;
  foreach ($lengths as $length_src) { 
    if (substr($length_src,strlen($length_src)-1,1) == "*") { 
      $parts += max(1,substr($length_src,0,strlen($length)-1));
    };
  };

  if ($parts > 0) {
    $part_size = $rest / $parts;

    for ($i = 0; $i < count($lengths); $i++) {
      $length = $lengths[$i];

      if (substr($length,strlen($length)-1,1) == "*") { 
        $values[$i] = $part_size * max(1,substr($length,0,strlen($length)-1));
      };
    };
  };

  // Fix over/underconstrained framesets
  $width = array_sum($values);

  if ($width > 0) {
    $koeff = $total / $width;
    for($i = 0; $i < count($values); $i++) {
      $values[$i] *= $koeff;
    };
  };

  return $values;
}

?>