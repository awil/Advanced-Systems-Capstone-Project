<?php
class ClientDB {
    public static function hasClientEmail(string $email) {
        $db = Database::getDB();
        $query = 'SELECT cl_id 
                  FROM clients
                  WHERE cl_email = :client_email';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_email', $email);
            $statement->execute();
            
            $valid = ($statement->rowCount() == 1);
            $statement->closeCursor();
            return $valid;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function isValidClientLogin(string $cl_alias, string $password) {
        $db = Database::getDB();
        $query = 'SELECT cl_password 
                  FROM clients
                  WHERE cl_alias = :cl_alias';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':cl_alias', $cl_alias);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();
            
            if ($row === FALSE) { return FALSE; } 
            else {
                $hash = $row['cl_password'];
                return password_verify($password, $hash);
            }
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function getClient(int $cl_id) {
        $db = Database::getDB();
        $query = 'SELECT cl_id, cl_first, cl_last, cl_email, cl_alias, cl_password,
                            cl_co_id, cl_title, cl_add_id
                  FROM clients 
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_id', $cl_id);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadClient($row);
            return $client;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    
    private static function loadClient($row) {
        if ($row) {
            return new Client($row['cl_id'], $row['cl_first'], 
                                $row['cl_last'], $row['cl_email'], $row['cl_alias'],
                                $row['cl_password'], $row['cl_co_id'], 
                                $row['cl_title'], $row['cl_add_id'], );
        } else {
            return NULL;
        }
    }

    public static function getClientByAlias(string $cl_alias) {
        $db = Database::getDB();
        $query = 'SELECT cl_id, cl_first, cl_last, cl_email, cl_alias, cl_password,
                            cl_co_id, cl_title, cl_add_id FROM clients WHERE cl_alias = :cl_alias';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':cl_alias', $cl_alias);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadClient($row);
            return $client;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function getClientByEmail(string $email) {
        $db = Database::getDB();
        $query = 'SELECT cl_id, cl_first, cl_last, cl_email, cl_alias, cl_password,
                            cl_co_id, cl_title, cl_add_id
                  FROM clients 
                  WHERE cl_email = :client_email';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_email', $email);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadClient($row);
            return $client;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function clientChangeAddress(int $cl_id, int $cl_add_id) {
        $db = Database::getDB();
        $query = 'UPDATE clients 
                  SET cl_add_id = :address_id 
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':address_id', $cl_add_id);
            $statement->bindValue(':client_id', $cl_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function clientChangeTitle(int $cl_id, string $cl_title) {
        $db = Database::getDB();
        $query = 'UPDATE clients 
                  SET cl_title = :client_title
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_title', $cl_title);
            $statement->bindValue(':client_id', $cl_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function clientChangeCompany(int $cl_id, string $cl_co_id) {
        $db = Database::getDB();
        $query = 'UPDATE clients 
                  SET cl_co_id = :client_co_id
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_co_id', $cl_co_id);
            $statement->bindValue(':client_id', $cl_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    
    public static function clientChangePassword($client) {
        $db = Database::getDB();
        $hash = password_hash($client->getPassword(), PASSWORD_DEFAULT);
        $query = 'UPDATE clients
                  SET cl_password = :hash 
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':hash', $hash);
            $statement->bindValue(':client_id', $client->getID());
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function addClient(Client $client) {
        $db = Database::getDB();
        $hash = password_hash($client->getPassword(), PASSWORD_DEFAULT);
        
        $query = 'INSERT INTO clients 
                      (cl_email, cl_password, cl_first, cl_last, cl_co_id, cl_title, cl_alias, cl_phone, cl_add_id)
                  VALUES 
                      (:client_email, :client_password, :client_first, :client_last, :client_co, :client_title, :client_alias, :client_phone, :client_address)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_email', $client->getEmail());
            $statement->bindValue(':client_password', $hash);
            $statement->bindValue(':client_first', $client->getFirstName());
            $statement->bindValue(':client_last', $client->getLastName());
            $statement->bindValue(':client_co', $client->getCompany());
            $statement->bindValue(':client_title', $client->getTitle());
            $statement->bindValue(':client_alias', $client->getAlias());
            $statement->bindValue(':client_phone', $client->getPhone());
            $statement->bindValue(':client_address', $client->getClientAddress());
            $statement->execute();
            
            $cl_id = $db->lastInsertId();
            $statement->closeCursor();
            return $cl_id;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function updateClient(Client $client) {
        $db = Database::getDB();
        $query = 'UPDATE clients
                  SET cl_first = :client_first,
                      cl_last = :client_last
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_first', $client->getFirstName());
            $statement->bindValue(':client_last', $client->getLastName());
            $statement->bindValue(':client_id', $client->getID());
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