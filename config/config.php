<?php
// Chargement des variables d'environnement depuis le fichier .env
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Ignorer les commentaires
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Charger le fichier .env s'il existe
loadEnv(__DIR__ . '/../.env');

class Config {
    // Constantes pour compatibilité avec la classe Database
    const DB_HOST = 'localhost';
    const DB_PORT = '3306';
    const DB_NAME = 'job';
    const DB_CHARSET = 'utf8mb4';
    const DB_USER = 'root';
    const DB_PASS = '';

    public static function dbDsn(): string {
        $host    = $_ENV['DB_HOST']    ?? self::DB_HOST;
        $port    = $_ENV['DB_PORT']    ?? self::DB_PORT;
        $dbname  = $_ENV['DB_NAME']    ?? self::DB_NAME;
        $charset = $_ENV['DB_CHARSET'] ?? self::DB_CHARSET;
        return "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
    }

    public static function dbUser(): string {
        return $_ENV['DB_USER'] ?? self::DB_USER;
    }

    public static function dbPass(): string {
        return $_ENV['DB_PASS'] ?? self::DB_PASS;
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
