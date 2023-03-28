<?php 

function adm_first_run() {
    $bcost = adm_test_cost();
    $users = adm_get_users_to_mod();
    $users = adm_hash_pass($users, $bcost);
    update_install();
}

function adm_test_cost() {
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
    
function adm_update_install() {
    $db = Database::getDB();
    $sql = 'UPDATE install_options SET opt_value = \'1\' WHERE opt_name = \'gdit_install\'; UPDATE install_options SET opt_value = \'1\' WHERE opt_name = \'gdit_install\'; ';
    $statement = $db->prepare($sql);
    $statement->execute();
    $statement->closeCursor();
}

function adm_get_users_to_mod() {
    $db = Database::getDB();
    $sql = 'SELECT * FROM admins WHERE admins.adm_password is not null';
    $statement = $db->prepare($sql);
    $statement->execute();
    $users_to_mod = $statement->fetchAll();
    $statement->closeCursor();
    return $users_to_mod;
}

function adm_hash_pass($u, $bcost) {
    $options = ['cost' => $bcost];
    foreach($u as $key => $user) : 
        $pass = $user['adm_password'];
        $newpass = password_hash($pass, PASSWORD_BCRYPT, $options);
        $u[$key]['adm_password'] = trim($newpass); 
        adm_update_pwd($u[$key]);
    endforeach;
    return $u;
}

function adm_hash_password($p) {
    $b = adm_get_bcrypt();
    $options = ['cost' => $b];
    $newp = password_hash($p, PASSWORD_BCRYPT, $options);
    $newp = trim($newp);
    return $newp;
}

function adm_update_pwd($u) {
    $db = Database::getDB();
    $query = 'UPDATE admins
        SET adm_password = :userPass
        WHERE adm_email = :userEmail';
    $statement = $db->prepare($query);
    $statement->bindValue(':userEmail', $u['adm_email']);
    $statement->bindValue(':userPass', $u['adm_password']);
    $statement->execute();
    $statement->closeCursor();
}

function adm_get_bcrypt() {
    $db = Database::getDB();
    $q = 'SELECT opt_value from install_options WHERE opt_name=\'serv_bcryp_cost\'';
    $statement = $db->prepare($q);
    $statement->execute();
    $cost = $statement->fetch();
    $cost = intval($cost[0]);
    $statement->closeCursor();
    return $cost;
}


?>