<?php
/**
 * This loads the site 
 */
  require_once __DIR__.'/config/util.php';

  if ( !defined('ABSPATH') ) {
    define('ABSPATH', __DIR__.'/');
  }

  include('views/home.php');
?>

