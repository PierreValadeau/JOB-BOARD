<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

try {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'is_logged_in' => true,
            'user' => [
                'user_id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role'],
                'name' => $_SESSION['user_name'],
                'phone' => $_SESSION['user_phone'] ?? ''
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'is_logged_in' => false
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>