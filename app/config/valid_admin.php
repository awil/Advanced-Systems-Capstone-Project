<?php
    // make sure the user is logged in as a valid administrator
    if (!isset($_SESSION['adm_id'])) {
        header('Location: ' . URLROOT . '/controllers/admin/' );
    }
?>