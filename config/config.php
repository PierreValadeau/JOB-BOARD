<?php
class Config {
    public static function dbDsn(): string {
        $host    = $_ENV['DB_HOST']    ?? 'localhost';
        $port    = $_ENV['DB_PORT']    ?? '3306';
        $dbname  = $_ENV['DB_NAME']    ?? 'job';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
        return "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
    }

    public static function dbUser(): string {
        return $_ENV['DB_USER'] ?? 'root';
    }

    public static function dbPass(): string {
        return $_ENV['DB_PASS'] ?? '';
    }
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
