<?php
function is_allowed_media($media_list) {
  // Now we've got the list of media this style can be applied to;
  // check if at least one of this media types is being used by the script
  //
  $allowed_media = config_get_allowed_media();
  $allowed_found = false;

  foreach ($media_list as $media) {
    $allowed_found |= (array_search($media, $allowed_media) !== false);
  };
  
  return $allowed_found;
}

?>