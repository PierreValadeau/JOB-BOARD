<?php
class Config {
    const DB_HOST = 'localhost';
    const DB_NAME = 'job';  
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_CHARSET = 'utf8';
    
    const API_VERSION = 'v1';
    const BASE_URL = 'http://localhost';
    
    const CORS_ORIGIN = '*';
    const CORS_METHODS = 'GET, POST, PUT, DELETE, OPTIONS';
    const CORS_HEADERS = 'Content-Type, Authorization, X-Requested-With';
}

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=' . Config::DB_CHARSET;
            $this->connection = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
  
    public function initTables() {
        try {
            $tables = ['users', 'companies', 'offers', 'application'];
            foreach ($tables as $table) {
                $stmt = $this->connection->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() == 0) {
                    throw new Exception("Required table '$table' does not exist");
                }
            }
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database verification failed: " . $e->getMessage());
        }
    }
    
    public function getTableStructure($tableName) {
        try {
            $stmt = $this->connection->query("DESCRIBE $tableName");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Failed to get table structure: " . $e->getMessage());
        }
    }
}
?>
