<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $redirect = $_GET['redirect'] ?? '';
    
    session_destroy();
    
    if ($redirect === 'index') {
        header('Location: ../view/index.php');
    } else {
        header('Location: ../view/login.php');
    }
    exit();
}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

try {
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Déconnexion réussie'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la déconnexion']);
}
?>