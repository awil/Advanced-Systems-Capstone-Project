<?php
    // Get the document root
    $doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING);

    // Get the application path
    $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
    $dirs = explode('/', $uri);
    $app_path = '/' . $dirs[1] . '/' . $dirs[2] . '/';

    set_include_path($doc_root . $app_path);

    // Load Libraries
    require_once('config/db_config.php');
    require_once('libraries/database.php');
    // require_once('app/config/install.php');

    // install();

    echo 'UTIL WAS RAN:'.$app_path;

    // Common functions
    function display_error($error) {
        global $app_path;
        include 'libraries/errors/error.php';
        exit();
    }

    // function redirect($url) {
    //     session_write_close();
    //     header("Location " . $url);
    // }

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

?>