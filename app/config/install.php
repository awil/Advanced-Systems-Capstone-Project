<?php 

function install() {
    
    $i = check_install();
    $in = intval($i[0][2]); // is installed already?
    $fr = intval($i[1][2]); // is first run?
    if($in == 0 && $fr == 0){
            first_run();            
    }

}

function check_install() {
    $db = Database::getDB();

    $sql = 'SELECT * FROM install_options';
    $statement = $db->prepare($sql);
    $statement->execute();
    $install_status = $statement->fetchAll();
    $statement->closeCursor();
    return $install_status;
}

function first_run() {
    $bcost = test_cost();
    $users = get_users_to_mod();
    $users = hash_pass($users, $bcost);
    update_install();
    require_once('install_adm.php');
    adm_first_run();
}

function test_cost() {
    /**
     * This code will benchmark your server to determine how high of a cost you can
     * afford. You want to set the highest cost that you can without slowing down
     * you server too much. 8-10 is a good baseline, and more is good if your servers
     * are fast enough. The code below aims for ≤ 50 milliseconds stretching time,
     * which is a good baseline for systems handling interactive logins.
     */
    $timeTarget = 0.05; // 50 milliseconds 

    $cost = 8;
    do {
        $cost++;
        $start = microtime(true);
        password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
        $end = microtime(true);
    } while (($end - $start) < $timeTarget);

    $db = Database::getDB();
    $sql = 'UPDATE install_options SET opt_value = :cost
            WHERE opt_name = \'serv_bcryp_cost\'';
    $statement = $db->prepare($sql);
    $statement->bindValue(':cost', $cost);
    $statement->execute();
    $statement->closeCursor();

    return $cost;
}
    
function update_install() {
    $db = Database::getDB();
    $sql = 'UPDATE install_options SET opt_value = \'1\' WHERE opt_name = \'gdit_install\'; UPDATE install_options SET opt_value = \'1\' WHERE opt_name = \'gdit_install\'; ';
    $statement = $db->prepare($sql);
    $statement->execute();
    $statement->closeCursor();
}

function get_users_to_mod() {
    $db = Database::getDB();
    $sql = 'SELECT * FROM clients WHERE clients.cl_password is not null';
    $statement = $db->prepare($sql);
    $statement->execute();
    $users_to_mod = $statement->fetchAll();
    $statement->closeCursor();
    return $users_to_mod;
}

function hash_pass($u, $bcost) {
    $options = ['cost' => $bcost];
    foreach($u as $key => $user) : 
        $pass = $user['cl_password'];
        $newpass = password_hash($pass, PASSWORD_BCRYPT, $options);
        $u[$key]['cl_password'] = trim($newpass); 
        update_pwd($u[$key]);
    endforeach;
    return $u;
}

function hash_password($p) {
    $b = get_bcrypt();
    $options = ['cost' => $b];
    $newp = password_hash($p, PASSWORD_BCRYPT, $options);
    $newp = trim($newp);
    return $newp;
}

function update_pwd($u) {
    $db = Database::getDB();
    $query = 'UPDATE clients
        SET cl_password = :userPass
        WHERE cl_email = :userEmail';
    $statement = $db->prepare($query);
    $statement->bindValue(':userEmail', $u['cl_email']);
    $statement->bindValue(':userPass', $u['cl_password']);
    $statement->execute();
    $statement->closeCursor();
}

function get_bcrypt() {
    $db = Database::getDB();
    $q = 'SELECT opt_value from install_options WHERE opt_name=\'serv_bcryp_cost\'';
    $statement = $db->prepare($q);
    $statement->execute();
    $cost = $statement->fetch();
    $cost = intval($cost[0]);
    $statement->closeCursor();
    return $cost;
}

function log_it($output, $with_script=true) {
    $js = 'console.log('.json_encode($output, JSON_HEX_TAG).');';
    if ($with_script) {
        $js = '<script>'.$js.'</script>';
    }
    echo $js;
}

?>