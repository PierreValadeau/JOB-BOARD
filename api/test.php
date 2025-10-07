<?php
require_once '../config/config.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    $stmt = $connection->query("SELECT COUNT(*) as companies FROM companies");
    $companiesCount = $stmt->fetch()['companies'];
    
    $stmt = $connection->query("SELECT COUNT(*) as offers FROM offers");
    $offersCount = $stmt->fetch()['offers'];
    
    $stmt = $connection->query("SELECT COUNT(*) as users FROM users");
    $usersCount = $stmt->fetch()['users'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Database connection OK',
        'data' => [
            'companies' => $companiesCount,
            'offers' => $offersCount,
            'users' => $usersCount
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]);
}
?>