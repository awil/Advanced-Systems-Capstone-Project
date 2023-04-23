<?php
require_once('../config/util.php');

require_once('../models/user.php');
require_once('../models/admin.php');
require_once('../models/admin_db.php');
require_once('../models/client.php');
require_once('../models/client_db.php');

require_once('../models/fields.php');
require_once('../models/validate.php');


$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {        
        $action = 'view_login';
        if (isset($_SESSION['cl_id'])) {
            header ('Location: ./client?action=view_account');
        }
        if (isset($_SESSION['adm_id'])) {
            header ('Location: ./admin?action=view_dashboard');
        }
    }
}

// Start field validation
$validate = new Validate();
$fields = $validate->getFields();

// For the Login page
$fields->addField('u_email', 'Must be valid email.');
$fields->addField('u_password');

switch ($action) {
    case 'view_login':
        // Clear login data
        $u_email = '';
        $u_password = '';
        $u_password_msg = '';
        
        include '../views/login.php';
        break;
    case 'login':
        // Get email/password
        $u_email = filter_input(INPUT_POST, 'u_email');
        $u_password = filter_input(INPUT_POST, 'u_password');
        
        // Validate user data       
        $validate->email('u_email', $u_email);
        $validate->text('u_password', $u_password, min:6);        

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors()) {
            include '../views/login.php';
            break;
        }
        
        // Check database - if valid email/password, log in
        if (AdminDB::isValidAdminLogin($u_email, $u_password)) {
            $admin = AdminDB::getAdminByEmail($u_email);
            $_SESSION['adm_id'] = $admin->getID();
        } else if (ClientDB::isValidClientLogin($u_email, $u_password)) {
            $client = ClientDB::getClientByEmail($u_email);
            $_SESSION['cl_id'] = $client->getID();
        } else {
            $u_password_msg = 'Login failed. Invalid email or password.';
            include '../views/login.php';
            break;
        }

        // Display Admin Menu page
        include('account/account_view.php');

        if (isset($_SESSION['adm_id'])) {
            header ('Location: ./admin?action=view_dashboard');
            break;
        } else if (isset($_SESSION['cl_id'])) {
            header ('Location: ./client?action=view_account');
            break;
        } else {
            include '../views/login.php';
            break;
        }

    case 'about':
        include('../views/about.php');
        break;
    case 'logout':
        $_SESSION = [];
        include('../index.php');
        break;
    default:
        display_error("Unknown account action: " . $action);
        break;
}