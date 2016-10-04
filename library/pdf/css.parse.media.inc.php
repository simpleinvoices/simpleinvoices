<?php
function is_allowed_media($media_list) {
  // Now we've got the list of media this style can be applied to;
  // check if at least one of this media types is being used by the script
  //
  $allowed_media = config_get_allowed_media();
  $allowed_found = false;

  // Note that media names should be case-insensitive;
  // it is not guaranteed that $media_list contains lower-case variants,
  // as well as it is not guaranteed that configuration data contains them.
  // Thus, media name lists should be explicitly converted to lowercase
  $media_list = array_map('strtolower', $media_list);
  $allowed_media = array_map('strtolower', $allowed_media);

  foreach ($media_list as $media) {
    $allowed_found |= (array_search($media, $allowed_media) !== false);
  };
  
  return $allowed_found;
}

?>