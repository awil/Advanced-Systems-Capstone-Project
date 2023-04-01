<?php
class CompanyDB {
    public static function getCompany(int $co_id) {
        $db = Database::getDB();
        $query = 'SELECT co_id, co_name, co_add_id
                  FROM companies 
                  WHERE co_id = :company_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_id', $co_id);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadClient($row);
            return $client;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }
    
    private static function loadCompany($row) {
        if ($row) {
            return new Company($row['co_id'], $row['co_name'], 
                                $row['co_add_id'], );
        } else {
            return NULL;
        }
    }

    public static function getCompanyByName(string $co_name) {
        $db = Database::getDB();
        $query = 'SELECT co_id, co_name, co_add_id
                  FROM companies 
                  WHERE co_name = :company_name';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $co_name);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadClient($row);
            return $client;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function companyChangeName(int $co_id, string $co_name) {
        $db = Database::getDB();
        $query = 'UPDATE companies 
                  SET co_name = :company_name 
                  WHERE co_id = :company_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $co_name);
            $statement->bindValue(':company_id', $co_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function addCompany(Company $co) {
        $db = Database::getDB();
        
        $query = 'INSERT INTO companies 
                      (co_name, co_add_id)
                  VALUES 
                      (:company_name, :company_address)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $co->getFirstName());
            $statement->bindValue(':company_address', $co->getLastName());
            $statement->execute();
            
            $cl_id = $db->lastInsertId();
            $statement->closeCursor();
            return $cl_id;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function updateCompany(Company $co) {
        $db = Database::getDB();
        $query = 'UPDATE companies
                  SET co_name = :company_name,
                    co_add_id = :company_address
                  WHERE co_id = :company_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $client->getName());
            $statement->bindValue(':company_address', $client->getAddress());
            $statement->bindValue(':company_id', $client->getID());
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