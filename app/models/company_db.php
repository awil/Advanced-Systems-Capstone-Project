<?php
class CompanyDB {

    public static function getAllCompanies() {
        $db = Database::getDB();
        $q = 'SELECT * from companies';

        try{
            $stmt = $db->prepare($q);
            $stmt->execute();
            
            $rows = $stmt->fetchAll();
            $stmt->closeCursor();

            $companies = [];
            foreach ($rows as $row) {
                $companies[] = self::loadCompany($row);
            }
            return $companies;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getCompany(int $co_id) {
        $db = Database::getDB();
        $query = 'SELECT co_id, co_name, co_short, add_id
                  FROM companies 
                  WHERE co_id = :company_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_id', $co_id);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadCompany($row);
            return $client;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }
    
    private static function loadCompany($row) {
        if ($row) {
            return new Company($row['co_id'], $row['co_name'], $row['co_short'], 
                                $row['add_id'], );
        } else {
            return NULL;
        }
    }

    public static function getCompanyByName(string $co_name) {
        $db = Database::getDB();
        $query = 'SELECT co_id, co_name, co_short, add_id
                  FROM companies 
                  WHERE co_name = :company_name';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':company_name', $co_name);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadCompany($row);
            return $client;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function getCompanyByShort(string $co_short) {
        $db = Database::getDB();
        $query = 'SELECT co_id, co_name, co_short, add_id
                  FROM companies 
                  WHERE co_short = :co_short';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':co_short', $co_short);
            $statement->execute();
            
            $row = $statement->fetch();
            $statement->closeCursor();

            $client = self::loadCompany($row);
            return $client;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function changeName(int $co_id, string $co_name) {
        $db = Database::getDB();
        $query = 'UPDATE companies 
                  SET co_name = :co_name 
                  WHERE co_id = :co_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':co_name', $co_name);
            $statement->bindValue(':co_id', $co_id);
            $statement->execute();
            
            $row_count = $statement->rowCount();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            displayDatabaseError($e->getMessage());
        }
    }

    public static function changeShort(int $co_id, string $co_short) {
        $db = Database::getDB();
        $query = 'UPDATE companies 
                  SET co_short = :co_short 
                  WHERE co_id = :co_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':co_short', $co_short);
            $statement->bindValue(':co_id', $co_id);
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
                      (co_id, co_name, co_short, add_id)
                  VALUES 
                      (null, :co_name, :co_short, :add_id)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':co_name', $co->getName());
            $statement->bindValue(':co_short', $co->getShort());
            $statement->bindValue(':add_id', $co->getAddress());
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
                  SET co_name = :co_name,
                    co_short = :co_short,
                    add_id = :add_id
                  WHERE co_id = :co_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':co_name', $client->getName());
            $statement->bindValue(':co_short', $client->getShort());
            $statement->bindValue(':add_id', $client->getAddress());
            $statement->bindValue(':co_id', $client->getID());
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