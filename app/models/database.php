<?php

class Database {
    private static $host = DB_HOST;
    private static $user = DB_USER;
    private static $pass = DB_PASS;
    private static $dbname = DB_NAME;
    private static $db;

    private static $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
    private static $options = array (
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
    );
    private $error;
    private $stmt;

    private function __construct() {
    }

    // Get the DB, display error otherwise
    public static function getDB() {
        if (!isset(self::$db)) {
            try {
                self::$db = new PDO(self::$dsn,
                                    self::$user,
                                    self::$pass,
                                    self::$options);
                
            } catch (PDOException $e) {
                displayDatabaseError($e->getMessage());
            }
        }
        return self::$db;
    }

    // Start a prepared statement
    public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
    }

    // Binding values
    public function bind($param, $value, $type = null) {
        if (is_null ($type)) {
			switch (true) {
				case is_int ($value) :
					$type = PDO::PARAM_INT;
					break;
				case is_bool ($value) :
					$type = PDO::PARAM_BOOL;
					break;
				case is_null ($value) :
					$type = PDO::PARAM_NULL;
					break;
				default :
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
    }

    // Execute the statement
    public function execute() {
        return $this->stmt->execute();
    }

    // Return the resulting array
    public function results() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Return single result
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Return last id inserted
    public function lastInsertId() {
        return self::$db->lastInsertId();
    }
}

?>