<?php
// Chargement du fichier .env
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

// Charger le fichier .env
loadEnv(__DIR__ . '/../.env');

// Définir les constantes pour compatibilité
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'job');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

class Config {
    public static function dbDsn(): string {
        return "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    }

    public static function dbUser(): string {
        return DB_USER;
    }

    public static function dbPass(): string {
        return DB_PASS;
    }
}


class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = Config::dbDsn();
            $this->connection = new PDO($dsn, Config::dbUser(), Config::dbPass());
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
