<?php
require_once('../../config/util.php');

require_once('../../models/user.php');
require_once('../../models/client.php');
require_once('../../models/client_db.php');
require_once('../../models/address.php');
require_once('../../models/address_db.php');

require_once('../../models/fields.php');
require_once('../../models/validate.php');


$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {        
        $action = 'view_login';
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

switch ($action) {
    case 'view_login':      
        // Clear login data
        $cl_email = '';
        $cl_password = '';
        $cl_password_message = '';
        
        include 'client_login_register.php';
        break;
    case 'login':
        $cl_email = filter_input(INPUT_POST, 'cl_email');
        $cl_password = filter_input(INPUT_POST, 'cl_password');
        
        // Validate user data
        // $validate->password('cl_password', $cl_password);        

        // If validation errors, redisplay Login page and exit controller
        if ($fields->hasErrors()) {
            include 'controllers/client/client_login_register.php';
            break;
        }
        
        // Check email and password in database
        if (ClientDB::isValidClientLogin($cl_email, $cl_password)) {
            $client = ClientDB::getClientByEmail($cl_email);
            $_SESSION['cl_id'] = $client->getID();
        } else {
            $cl_password_message = 'Login failed. Invalid email or password.';
            include 'controllers/client/client_login_register.php';
            break;
        }

        // If necessary, redirect to the dashboard
        // redirect('.');
        include('client_view.php');
    
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
        $_SESSION = [];
        include('.');
        break;
    default:
        display_error("Unknown account action: " . $action);
        break;
}
?>