<?php
require_once '../config/config.php';

echo "<h2>Testing Database Connection</h2>\n";

try {
    // Test basic connection
    echo "<h3>1. Testing Database Connection...</h3>\n";
    $database = Database::getInstance();
    echo "✅ Database connection successful!<br>\n";
    
    // Test database selection
    echo "<h3>2. Testing Database Selection...</h3>\n";
    $connection = $database->getConnection();
    $stmt = $connection->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch();
    echo "✅ Connected to database: <strong>" . $result['current_db'] . "</strong><br>\n";
    
    // Test table verification
    echo "<h3>3. Checking Required Tables...</h3>\n";
    $database->initTables();
    echo "✅ All required tables exist!<br>\n";
    
    // Show table structures
    echo "<h3>4. Table Structures:</h3>\n";
    $tables = ['users', 'companies', 'offers', 'application'];
    
    foreach ($tables as $table) {
        echo "<h4>Table: $table</h4>\n";
        try {
            $structure = $database->getTableStructure($table);
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>\n";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
            
            foreach ($structure as $column) {
                echo "<tr>";
                echo "<td>{$column['Field']}</td>";
                echo "<td>{$column['Type']}</td>";
                echo "<td>{$column['Null']}</td>";
                echo "<td>{$column['Key']}</td>";
                echo "<td>{$column['Default']}</td>";
                echo "<td>{$column['Extra']}</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
        } catch (Exception $e) {
            echo "❌ Error getting structure for table '$table': " . $e->getMessage() . "<br>\n";
        }
    }
    
    // Test basic queries
    echo "<h3>5. Testing Basic Queries...</h3>\n";
    
    // Count records in each table
    foreach ($tables as $table) {
        try {
            $stmt = $connection->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch();
            echo "📊 Table '$table' has {$result['count']} records<br>\n";
        } catch (Exception $e) {
            echo "❌ Error counting records in '$table': " . $e->getMessage() . "<br>\n";
        }
    }
    
    echo "<h3>6. Connection Test Complete!</h3>\n";
    echo "✅ Your database connection is working properly. You can now proceed with building the API.<br>\n";
    
} catch (Exception $e) {
    echo "<h3>❌ Connection Error:</h3>\n";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
    
    echo "<h3>🔧 Troubleshooting Tips:</h3>\n";
    echo "<ul>\n";
    echo "<li>Check if your MySQL/XAMPP/MAMP server is running</li>\n";
    echo "<li>Verify database name is 'job' (not 'job_board')</li>\n";
    echo "<li>Check your database credentials in config/config.php</li>\n";
    echo "<li>Make sure the database 'job' exists in phpMyAdmin</li>\n";
    echo "<li>Verify your MySQL port (default: 3306)</li>\n";
    echo "</ul>\n";
    
    echo "<h3>🔍 Current Configuration:</h3>\n";
    echo "<p>Host: " . Config::DB_HOST . "</p>\n";
    echo "<p>Database: " . Config::DB_NAME . "</p>\n";
    echo "<p>User: " . Config::DB_USER . "</p>\n";
    echo "<p>Charset: " . Config::DB_CHARSET . "</p>\n";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background: #f5f5f5;
    }
    
    h2 {
        color: #1e3a5f;
        border-bottom: 2px solid #1e3a5f;
        padding-bottom: 10px;
    }
    
    h3 {
        color: #2c5282;
        margin-top: 25px;
    }
    
    h4 {
        color: #4a5568;
        margin-top: 15px;
        margin-bottom: 5px;
    }
    
    table {
        width: 100%;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    th {
        background: #1e3a5f;
        color: white;
        padding: 8px;
        text-align: left;
    }
    
    td {
        padding: 6px 8px;
        border-bottom: 1px solid #eee;
    }
    
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    
    ul {
        background: white;
        padding: 15px 30px;
        border-left: 4px solid #e53e3e;
        margin: 10px 0;
    }
</style>