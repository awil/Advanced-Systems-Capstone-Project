<?php
    // Get the document root
    $doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING);
    // Get the application path
    $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
    $dirs = explode('/', $uri);
    $app_path = '/' . $dirs[1] . '/' . $dirs[2]. '/';

    set_include_path($doc_root . $app_path);
    
    // Load helpers
    require_once('config.php');
    require_once('tag_format.php');
    require_once('url_redirect.php');

    // Load Database
    require_once('models/database.php');

    /** 
     * This is to install the site, supposed to run once to encrypt passwords
     */
    // require_once('install.php');
    // install();

    echo 'UTIL WAS RAN -> $app_path: '.$app_path.' $doc_root: '.$doc_root.' $uri: '.$uri.' $inclpath '.get_include_path();

    // Error handling
    function display_error($error_message) {
        global $app_path;
        include 'config/errors/error.php';
        exit();
    }

    function displayDatabaseError($error_message) {
        global $app_path;
        include 'errors/db_error.php';
        exit;
    }

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

?>