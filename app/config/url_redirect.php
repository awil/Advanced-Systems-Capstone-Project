<?php
  // Simple page redirect
  function redirect($page){

    echo 'url_redirect.php -> page: '.$page;

    header('location: '.$app_path.'/'.$page);
  }


  
    // function redirect($url) {
    //     session_write_close();
    //     header("Location " . $url);
    // }