<?php
class AdminDB {
    public static function isValidAdminLogin($alias, $password) {
        $db = Database::getDB();
        $query = 'SELECT adm_password 
                  FROM admins
                  WHERE adm_alias = :admin_alias';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':admin_alias', $alias);
            $statement->execute();

            $row = $statement->fetch();
            $statement->closeCursor();

            if ($row === FALSE) { return FALSE; } 
            else {
                $hash = $row['adm_password'];
                return password_verify($password, $hash);
            }
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    
    public static function isValidAdminEmail($email) {
        $db = Database::getDB();
        $query = 'SELECT adm_id  
                  FROM admins
                  WHERE adm_email = :adm_email';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_email', $email);
            $statement->execute();
            
            $valid = ($statement->rowCount() == 1);
            $statement->closeCursor();
            return $valid;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function isValidAdminAlias($alias) {
        $db = Database::getDB();
        $query = 'SELECT adm_id  
                  FROM admins
                  WHERE adm_alias = :admin_alias';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':admin_alias', $alias);
            $statement->execute();
            
            $valid = ($statement->rowCount() == 1);
            $statement->closeCursor();
            return $valid;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    
    public static function getAdminCount() {
        $db = Database::getDB();
        $query = 'SELECT count(adm_id) AS adm_count 
                  FROM admins';
        try {
            $statement = $db->prepare($query);
            $statement->execute();

            $row = $statement->fetch();
            $statement->closeCursor();

            if ($row) {
                return $row['adm_count'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function getAllAdmins() {
        $db = Database::getDB();
        $query = 'SELECT adm_id, adm_first, adm_last, adm_title,
                      adm_email, adm_alias, adm_password 
                  FROM admins 
                  ORDER BY adm_last, adm_first';
        try {
            $statement = $db->prepare($query);
            $statement->execute();
            
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            
            $admins = [];
            foreach ($rows as $row) {
                $admins[] = self::loadAdmin($row);
            }
            return $admins;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    
    private static function loadAdmin($row) {
        return new Admin($row['adm_id'], $row['adm_first'], 
                         $row['adm_last'], $row['adm_title'], $row['adm_email'], 
                         $row['adm_alias'],
                         $row['adm_password'], );
    }

    public static function getAdmin ($adm_id) {
        $db = Database::getDB();
        $query = 'SELECT adm_id, adm_first, adm_last, adm_title,
                      adm_email, adm_alias, adm_password 
                  FROM admins 
                  WHERE adm_id = :adm_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_id', $adm_id);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();
            
            if ($row) {
                return self::loadAdmin($row);
            } else {
                return NULL;
            }
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function getAdminByEmail ($email) {
        $db = Database::getDB();
        $query = 'SELECT adm_id, adm_first, adm_last, adm_title,
                      adm_email, adm_alias, adm_password  
                  FROM admins 
                  WHERE adm_email = :adm_email';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_email', $email);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();
            
            if ($row) {
                return self::loadAdmin($row);
            } else {
                return NULL;
            }
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function getAdminByAlias ($adm_alias) {
        $db = Database::getDB();
        $query = 'SELECT adm_id, adm_first, adm_last, adm_title,
                      adm_email, adm_alias, adm_password  
                  FROM admins 
                  WHERE adm_alias = :adm_alias';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_alias', $adm_alias);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();
            
            if ($row) {
                return self::loadAdmin($row);
            } else {
                return NULL;
            }
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function addAdmin($admin) {
        $db = Database::getDB();
        $hash = password_hash($admin->getPassword(), PASSWORD_DEFAULT);
        $query = 'INSERT INTO admins 
                      (adm_email, adm_alias, adm_password, adm_first, adm_last, adm_title)
                  VALUES 
                      (:adm_email, :adm_alias, :adm_password, :adm_first, :adm_last, :adm_title)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_email', $admin->getEmail());
            $statement->bindValue(':adm_alias', $admin->getAlias());
            $statement->bindValue(':adm_password', $hash);
            $statement->bindValue(':adm_first', $admin->getFirstName());
            $statement->bindValue(':adm_last', $admin->getLastName());
            $statement->bindValue(':adm_title', $admin->getTitle());
            $statement->execute();
            
            $adm_id = $db->lastInsertId();
            $statement->closeCursor();
            return $adm_id;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function updateAdmin($admin) {
        $db = Database::getDB();
        $query = 'UPDATE admins
                  SET adm_first = :adm_first,
                      adm_last = :adm_last,
                      adm_title = :adm_title,
                      adm_alias = :adm_alias,
                  WHERE adm_id = :adm_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_first', $admin->getFirstName());
            $statement->bindValue(':adm_last', $admin->getLastName());
            $statement->bindValue(':adm_title', $admin->getTitle());
            $statement->bindValue(':adm_alias', $admin->getAlias());
            $statement->bindValue(':adm_id', $admin->getID());
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    
    public static function changePassword($admin) {
        $db = Database::getDB();
        $hash = password_hash($admin->getPassword(), PASSWORD_DEFAULT);
        $query = 'UPDATE admins
                  SET adm_password = :hash 
                  WHERE adm_id = :adm_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':hash', $hash);
            $statement->bindValue(':adm_id', $admin->getID());
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function deleteAdmin($adm_id) {
        $db = Database::getDB();
        $query = 'DELETE FROM admins 
                  WHERE adm_id = :adm_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':adm_id', $adm_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
}
?>