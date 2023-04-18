<?php
require_once('../../config/util.php');

require_once('../../models/user.php');
require_once('../../models/log.php');
require_once('../../models/log_db.php');
require_once('../../models/admin.php');
require_once('../../models/admin_db.php');
require_once('../../models/client.php');
require_once('../../models/client_db.php');
require_once('../../models/baseline.php');
require_once('../../models/baseline_db.php');
require_once('../../models/company.php');
require_once('../../models/company_db.php');
require_once('../../models/poam.php');
require_once('../../models/ctrl.php');
require_once('../../models/fields.php');
require_once('../../models/validate.php');


$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {        
        $action = 'view_clients';
        if (isset($_SESSION['cl_id'])) {
            $action = 'view_account';
        }
    }
}

// Set up all possible fields to validate
$validate = new Validate();
$fields = $validate->getFields();

// for the Registration page and other pages
$fields->addField('cl_email', 'Must be valid email.');
$fields->addField('cl_password_1');
$fields->addField('cl_password_2');
$fields->addField('cl_first');
$fields->addField('cl_last');
$fields->addField('cl_title');
$fields->addField('co_id');
$fields->addField('cl_phone', required:FALSE);
$fields->addField('add_id');
$fields->addField('add_line1');
$fields->addField('add_line2', required:FALSE);
$fields->addField('add_city');
$fields->addField('add_state');
$fields->addField('add_zipCode');


// for the Login page
$fields->addField('cl_password');

if (isset($_SESSION['adm_id'])) {
    $current_admin = AdminDB::getAdmin($_SESSION['adm_id']);
}
if (isset($_SESSION['co_id'])) {
    $current_client = CompanyDB::getCompany($_SESSION['co_id']);
}

switch ($action) {
    case 'view_clients':    
        // Get all accounts and current admin from database
        $clients = ClientDB::getAllClients();  
        $companies = CompanyDB::getAllCompanies();

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' viewed client database');
        LogDB::updateLog($l);

        include 'controllers/client/clients.php';
        break;
    case 'select_client':
        // Select the client
        $_SESSION['cl_id'] = filter_input(INPUT_POST, 'cl_id');
        $_SESSION['co_id'] = filter_input(INPUT_POST, 'co_id');

        header("Location: " .$app_path.'controllers/baseline?action=view_co_baselines');
        break;
    case 'view_account':
        $client = ClientDB::getClient($_SESSION['cl_id']);
        $address = AddressDB::getAddress($client->getClientAddress());
        
        include 'controllers/client/client_view.php';
        break;
    case 'view_account_edit':
        $client = ClientDB::getClient($_SESSION['cl_id']);     

        include 'client_account_edit.php';
        break;
    case 'update_account':
        // Get the client data
        $client = new Client($_SESSION['cl_id']);
        $client->setFirstName(filter_input(INPUT_POST, 'cl_first'));
        $client->setLastName(filter_input(INPUT_POST, 'cl_last'));
        $client->setEmail(filter_input(INPUT_POST, 'cl_email'));
        $client->setPassword(filter_input(INPUT_POST, 'cl_password_1'));
        $confirm_password = filter_input(INPUT_POST, 'cl_password_2');
        
        // allow password and confirm password to be blank
        $fields->getField('cl_password_1')->setRequired(FALSE);
        $fields->getField('cl_password_2')->setRequired(FALSE);
        
        // Validate user data
        $validate->password('cl_password_1', $client->getPassword());
        $validate->verify('cl_password_2', $client->getPassword(), $confirm_password);        
        $validate->text('cl_first', $client->getFirstName());
        $validate->text('cl_last', $client->getLastName());  
        $validate->email('cl_email', $client->getEmail());   

        // If validation errors, redisplay account edit page
        if ($fields->hasErrors()) {
            include 'controllers/client/client_account_edit.php';
            break;
        }

        // Update the client data
        ClientDB::updateClient($client);
        if (!empty($client->getPassword())) {
            ClientDB::clientChangePassword($client);
        }

        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setCompanyID($_SESSION['co_id']);
        $l->setAccData($current_admin->getName().' updated '.$client->getName().'\'s account');
        LogDB::updateLog($l);

        redirect('.');
        break;
    case 'view_address_edit':
        $client = ClientDB::getClient($_SESSION['cl_id']);
        
        // Set up variables for address type
        $address_id = $client->getAddress();
        $heading = 'Update Address';

        // Get the data for the address
        $address = AddressDB::getAddress($address_id);

        // Display the data on the page
        include 'client_address_edit.php';
        break;
    case 'update_address':
        $client = ClientDB::getClient($_SESSION['cl_id']);

        // Get the post data
        $address->setClientID($_SESSION['cl_id']);
        $address->setLine1(filter_input(INPUT_POST, 'add_line1'));
        $address->setLine2(filter_input(INPUT_POST, 'add_line2'));
        $address->setCity(filter_input(INPUT_POST, 'add_city'));
        $address->setState(filter_input(INPUT_POST, 'add_state'));
        $address->setZipCode(filter_input(INPUT_POST, 'add_zipCode'));

        // Validate the data
        $validate->text('add_line1', $address->getLine1());        
        $validate->text('add_line2', $address->getLine2());        
        $validate->text('add_city', $address->getCity());        
        $validate->state('add_state', $address->getState());        
        $validate->zip('add_zipCode', $address->getZipCode());        

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors()) {
            include 'controllers/client/client_address_edit.php';
            break;
        }
        
        // If the old address has orders, disable it
        // Otherwise, delete it
        AddressDB::disableOrDeleteAddress($address->getID());

        // Add the new address
        $address_id = AddressDB::addAddress($client->getID(), $address);

        redirect('.');
        break;
    case 'logout':
        $l = new Log();
        $l->setAdmID($_SESSION['adm_id']);
        $l->setAccDate(date("Y-m-d H:i:s"));
        $l->setAccData($current_admin->getName().' logged out');
        LogDB::updateLog($l);

        $_SESSION = [];
        include('.');
        break;
    default:
        display_error("Unknown account action: " . $action);
        break;
}
?>