<?php 
require_once('../../config/util.php');

require_once('../../models/user.php');
require_once('../../models/admin.php');
require_once('../../models/admin_db.php');
require_once('../../models/log.php');
require_once('../../models/log_db.php');
require_once('../../models/client.php');
require_once('../../models/client_db.php');
require_once('../../models/company.php');
require_once('../../models/company_db.php');
require_once('../../models/fields.php');
require_once('../../models/validate.php');


$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {        
        $action = 'view_dashboard';
    }
}

// Start field validation
$validate = new Validate();
$fields = $validate->getFields();

// For the account pages
$fields->addField('adm_email', 'Must be valid email.');
$fields->addField('adm_password_1');
$fields->addField('adm_password_2');
$fields->addField('adm_first');
$fields->addField('adm_last');
$fields->addField('adm_title');


if (isset($_SESSION['adm_id'])) {
    $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
}
if (isset($_SESSION['co_id'])) {
    $current_client = CompanyDB::getCompany($_SESSION['co_id']);
}

// For the Login page

switch ($action) {
    case 'view_login':
        // Clear login data
        $adm_email = '';
        $adm_password = '';
        $adm_password_msg = '';
        
        include 'account/account_login.php';
        break;
    case 'login':
        // Get email/password
        $adm_email = filter_input(INPUT_POST, 'adm_email');
        $adm_password = filter_input(INPUT_POST, 'adm_password');
        
        // Validate user data       
        $validate->email('adm_email', $adm_email);
        $validate->text('adm_password', $adm_password, min:6);        

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors()) {
            include 'account/account_login.php';
            break;
        }
        
        // Check database - if valid email/password, log in
        if (AdminDB::isValidAdminLogin($adm_email, $adm_password)) {
            $admin = AdminDB::getAdminByEmail($adm_email);
            $_SESSION['adm_id'] = $admin->getID();
        } else {
            $adm_password_msg = 'Login failed. Invalid email or password.';
            include 'account/account_login.php';
            break;
        }

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData("User logged in.");
        LogDB::updateLog($l);

        // Display Admin Menu page
        include('account/account_view.php');
        break;
    case 'view_dashboard':
        // Get all accounts and current admin from database
        $admins = AdminDB::getAllAdmins();
        $clients = ClientDB::getAllClients();
        // var_dump($clients);
        // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);

        // View admin accounts
        include 'dashboard.php';
        break;
    case 'view_account':
        // Get all accounts and current admin from database
        // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);

        // View admin accounts
        include 'account/account_view.php';
        break;
    case 'view_log':
        $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = 25;

        $logs = LogDB::getPageLog($page, $limit);
        $tot_row = LogDB::getTotalRows();
        $tot_pg = ceil($tot_row / $limit);

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' accessed the system log');
        LogDB::updateLog($l);

        include 'access_log.php';
        break;
    case 'select_client':
        // Select the client
        $_SESSION['cl_id'] = filter_input(INPUT_POST, 'cl_id');
        $_SESSION['co_id'] = filter_input(INPUT_POST, 'co_id');

        header("Location: " .$app_path.'controllers/baseline?action=view_co_baselines');
        break;
    case 'create':
        // Get admin user data
        $new_admin = new Admin();
        $new_admin->setEmail(filter_input(INPUT_POST, 'adm_email'));
        $new_admin->setFirstName(filter_input(INPUT_POST, 'adm_first'));
        $new_admin->setLastName(filter_input(INPUT_POST, 'adm_last'));
        $new_admin->setTitle(filter_input(INPUT_POST, 'adm_title'));
        $new_admin->setPassword(filter_input(INPUT_POST, 'adm_password_1'));
        $confirm_password = filter_input(INPUT_POST, 'adm_password_2');

        // Validate admin user data
        $validate->email('adm_email', $new_admin->getEmail());
        $validate->text('adm_first', $new_admin->getFirstName());
        $validate->text('adm_last', $new_admin->getLastName());    
        $validate->text('adm_title', $new_admin->getTitle());        
        $validate->text('adm_password_1', $new_admin->getPassword(), min:6);
        $validate->verify('adm_password_2', $confirm_password, $new_admin->getPassword());     
        
        // Validate unique email 
        $email_message = '';
        if (AdminDB::isValidAdminEmail($new_admin->getEmail())) {
            $email_message = 'This email is already in use.';
        }
        
        // If validation errors, redisplay account page and exit controller
        if ($fields->hasErrors() || !empty($email_message)) {
            $admins = AdminDB::getAllAdmins();
            // $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
            include 'account/account_view.php';
            break;
        }

        // Add admin user
        $adm_id = AdminDB::addAdmin($new_admin);

        // Set admin user in session
        if (!isset($_SESSION['adm_id'])) {
            $_SESSION['adm_id'] = $adm_id;
        }
        include('account/account_edit.php');
        break;
    case 'view_edit':
        // Get admin user data
        $adm_id = filter_input(INPUT_POST, 'adm_id', FILTER_VALIDATE_INT);
        $admin = AdminDB::getAdmin($adm_id);

        // Display Edit page
        include 'account/account_edit.php';
        break;
    case 'update':
        $admin = new Admin();
        $admin->setID(filter_input(INPUT_POST, 'adm_id', FILTER_VALIDATE_INT));
        $admin->setFirstName(filter_input(INPUT_POST, 'adm_first'));
        $admin->setLastName(filter_input(INPUT_POST, 'adm_last'));
        $admin->setTitle(filter_input(INPUT_POST, 'adm_title'));
        $admin->setEmail(filter_input(INPUT_POST, 'adm_email'));
        $admin->setPassword(filter_input(INPUT_POST, 'adm_password_1'));
        $confirm_password = filter_input(INPUT_POST, 'adm_password_2');
        
        // allow password and confirm password to be blank
        $fields->getField('adm_password_1')->setRequired(FALSE);
        $fields->getField('adm_password_2')->setRequired(FALSE);
        
        // Validate admin user data
        $validate->text('adm_first', $admin->getFirstName());
        $validate->text('adm_last', $admin->getLastName());        
        $validate->text('adm_password_1', $admin->getPassword(), min:6);
        $validate->verify('adm_password_2', $admin->getPassword(), $confirm_password);   
        
        // If validation errors, redisplay edit page and exit controller
        if ($fields->hasErrors()) {
            include 'account/account_view.php';
            break;
        }

        AdminDB::updateAdmin($admin);
        if ($admin->hasPassword()) {
            AdminDB::changePassword($admin);
        }
        $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' updated UID: '.$admin->getID().' Name: '.$admin->getName());
        LogDB::updateLog($l);

        include('account/account_view.php');
        break;
    case 'logout':
        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' logged out');
        LogDB::updateLog($l);

        unset($_SESSION['adm_id']);
        $_SESSION = [];
        include '.';
        break;
    default:
        display_error('Unknown account action: ' . $action);
        break;
}
?>