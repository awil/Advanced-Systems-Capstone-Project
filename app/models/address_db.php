<?php
class AddressDB {
    public static function getAddress(int $add_id) {
        $db = Database::getDB();
        $query = 'SELECT add_id, add_line1, add_line2, add_city, add_state, add_zipCode
                  FROM addresses 
                  WHERE add_id = :address_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':address_id', $add_id);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();
            
            if ($row) {
                return new Address($row['add_id'], $row['add_line1'], $row['add_line2'], 
                                   $row['add_city'], $row['add_state'],
                                   $row['add_zipCode'],);
            } else {
                return NULL;
            }
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }
    
    public static function addAddress(Address $address) {
            $db = Database::getDB();
            $query = 'INSERT INTO addresses (add_line1, add_line2,
                                    add_city, add_state, add_zipCode)
                      VALUES (:add_line1, :add_line2,
                                    :add_city, :add_state, :add_zipCode)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':add_line1', $address->getLine1());
            $statement->bindValue(':add_line2', $address->getLine2());
            $statement->bindValue(':add_city', $address->getCity());
            $statement->bindValue(':add_state', $address->getState());
            $statement->bindValue(':add_zip_code', $address->getZipCode());
            $statement->execute();
            
            $add_id = $db->lastInsertId();
            $statement->closeCursor();
            return $add_id;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function updateAddress (Address $address) {
        $db = Database::getDB();
        $query = 'UPDATE addresses
                  SET add_line1 = :add_line1,
                  add_line2 = :add_line2,
                  add_city = :add_city,
                  add_state = :add_state,
                  add_zipCode = :add_zip_code
                  WHERE add_id = :add_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':add_id', $address->getID());
            $statement->bindValue(':add_line1', $address->getLine1());
            $statement->bindValue(':add_line2', $address->getLine2());
            $statement->bindValue(':add_city', $address->getCity());
            $statement->bindValue(':add_state', $address->getState());
            $statement->bindValue(':add_zip_code', $address->getZipCode());
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function disableOrDeleteAddress(int $add_id) {
        $db = Database::getDB();
        if (self::isUsedAddressId($add_id)) {
            $query = 'UPDATE addresses SET add_disabled = 1 
                      WHERE add_id = :add_id';
            try {
                $statement = $db->prepare($query);
                $statement->bindValue(':add_id', $add_id);
                $statement->execute();
            
                $row_count = $statement->rowCount();
                $statement->closeCursor();
                return $row_count;
            } catch (PDOException $e) {
                displayDatabaseError($e->getMessage());
            }
        } else {
            $query = 'DELETE FROM addresses 
                      WHERE add_id = :add_id';
            try {
                $statement = $db->prepare($query);
                $statement->bindValue(':add_id', $add_id);
                $statement->execute();
            
                $row_count = $statement->rowCount();
                $statement->closeCursor();
                return $row_count;
            } catch (PDOException $e) {
                displayDatabaseError($e->getMessage());
            }
        }
    }

    public static function isUsedAddressId(int $add_id) {
        $db = Database::getDB();

        // Check if the address is used as a client address
        $query1 = 'SELECT COUNT(*) FROM clients 
                   WHERE add_id = :value';
        $statement1 = $db->prepare($query1);
        $statement1->bindValue(':value', $add_id);
        $statement1->execute();
        
        $result1 = $statement1->fetch();
        $billing_count = $result1[0];
        $statement1->closeCursor();
        if ($billing_count > 0) { return TRUE; }

        // Check if the address is used as a company address
        $query2 = 'SELECT COUNT(*) FROM companies WHERE co_add_id = :value';
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':value', $co_id);
        $statement2->execute();
        
        $result2 = $statement2->fetch();
        $ship_count = $result2[0];
        $statement2->closeCursor();
        if ($ship_count > 0) { return TRUE; }

        return FALSE;
    }
}

?>