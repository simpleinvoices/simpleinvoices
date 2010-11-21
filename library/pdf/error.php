<?php

if (!function_exists('error_no_method')) {
  function error_no_method($method, $class) {
    die(sprintf("Error: unoverridden '%s' method called in '%s'", $method, $class));
  }
};

?>