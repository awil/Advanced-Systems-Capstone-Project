<?php
class ClientDB {

    public static function getAllClients() {
        $db = Database::getDB();
        $q = 'SELECT * from clients order by cl_last, cl_first';

        try{
            $stmt = $db->prepare($q);
            $stmt->execute();
            
            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $clients = [];
            foreach ($rows as $row) {
                $clients[] = self::loadClient($row);
            }
            return $clients;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

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
            displayDatabaseError($e->getMessage());
        }
    }

    public static function isValidClientLogin(string $cl_email, string $password) {
        $db = Database::getDB();
        $query = 'SELECT cl_password 
                  FROM clients
                  WHERE cl_email = :cl_email';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':cl_email', $cl_email);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();
            
            if ($row === FALSE) { return FALSE; } 
            else {
                $hash = $row['cl_password'];
                return password_verify($password, $hash);
            }
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getClient(int $cl_id) {
        $db = Database::getDB();
        $query = 'SELECT cl_id, cl_first, cl_last, cl_title, cl_email, cl_password,
                            co_id, cl_phone, add_id
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
            displayDatabaseError($e->getMessage());
        }
    }
    
    private static function loadClient($row) {
        if ($row) {
            return new Client($row['cl_id'], $row['cl_first'], 
                                $row['cl_last'], $row['cl_title'], $row['cl_email'],
                                $row['cl_password'], $row['co_id'], $row['cl_phone'],
                                 $row['add_id'], );
        } else {
            return NULL;
        }
    }

    public static function getClientByEmail(string $email) {
        $db = Database::getDB();
        $query = 'SELECT cl_id, cl_first, cl_last, cl_title, cl_email, cl_password,
                            co_id, cl_phone, add_id
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
            displayDatabaseError($e->getMessage());
        }
    }

    public static function clientChangeAddress(int $cl_id, int $add_id) {
        $db = Database::getDB();
        $query = 'UPDATE clients 
                  SET add_id = :address_id 
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':address_id', $add_id);
            $statement->bindValue(':client_id', $cl_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
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
            displayDatabaseError($e->getMessage());
        }
    }

    public static function clientChangeCompany(int $cl_id, string $co_id) {
        $db = Database::getDB();
        $query = 'UPDATE clients 
                  SET co_id = :client_co_id
                  WHERE cl_id = :client_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_co_id', $co_id);
            $statement->bindValue(':client_id', $cl_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
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
            displayDatabaseError($e->getMessage());
        }
    }

    public static function addClient(Client $client) {
        $db = Database::getDB();
        $hash = password_hash($client->getPassword(), PASSWORD_DEFAULT);
        
        $query = 'INSERT INTO clients 
                      (cl_id, cl_first, cl_last, cl_title, cl_email, cl_password,
                            co_id, cl_phone, add_id)
                  VALUES 
                    (:client_first, :client_last, :client_title, :client_email, :client_password, :client_co, :client_phone, :client_address)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':client_email', $client->getEmail());
            $statement->bindValue(':client_password', $hash);
            $statement->bindValue(':client_first', $client->getFirstName());
            $statement->bindValue(':client_last', $client->getLastName());
            $statement->bindValue(':client_co', $client->getCompany());
            $statement->bindValue(':client_title', $client->getTitle());
            $statement->bindValue(':client_phone', $client->getPhone());
            $statement->bindValue(':client_address', $client->getClientAddress());
            $statement->execute();
            
            $cl_id = $db->lastInsertId();
            $statement->closeCursor();
            return $cl_id;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
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
            displayDatabaseError($e->getMessage());
        }
    }
}
?>