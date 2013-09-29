<?php

class BoxPage extends GenericContainerBox {
  function BoxPageMargin() {
    $this->GenericContainerBox();
  }

  function &create(&$pipeline, $rules) {
    $box =& new BoxPage();

    $state =& $pipeline->getCurrentCSSState();
    $state->pushDefaultState();
    $rules->apply($state);
    $box->readCSS($state);
    $state->popState();

    return $box;
  }

  function get_bottom_background() { 
    return $this->get_bottom_margin(); 
  }

  function get_left_background()   { 
    return $this->get_left_margin();   
  }

  function get_right_background()  { 
    return $this->get_right_margin();  
  }

  function get_top_background()    { 
    return $this->get_top_margin();    
  }

  function reflow(&$media) {
    $this->put_left(mm2pt($media->margins['left']));
    $this->put_top(mm2pt($media->height() - $media->margins['top']));
    $this->put_width(mm2pt($media->real_width()));
    $this->put_height(mm2pt($media->real_height()));
  }

  function show(&$driver) {    
    $this->offset(0, $driver->offset);
    parent::show($driver);
  }
}

?>