<?php

// You can either copy the original code and add you modify it, here.

// Or you can require the overriden module (to resists to future updates)
include('../modules/index/index.php');

// and just add some code
$my_content	="<h3>My Own Tag is Here</H3>";

// We add it to the template
$smarty -> assign("my_tag", $my_content);
