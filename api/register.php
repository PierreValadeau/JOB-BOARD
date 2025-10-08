<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../controller/UserController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }
    
    $required_fields = ['first_name', 'last_name', 'email', 'password'];
    foreach ($required_fields as $field) {
        if (!isset($input[$field]) || empty(trim($input[$field]))) {
            echo json_encode(['success' => false, 'message' => "Le champ $field est requis"]);
            exit;
        }
    }
    
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format d\'email invalide']);
        exit;
    }
    
    if (strlen($input['password']) < 8) {
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères']);
        exit;
    }
    
    $controller = new UserController();
    $userModel = new UserModel();
    
    $userData = [
        'first_name' => trim($input['first_name']),
        'last_name' => trim($input['last_name']),
        'email' => trim($input['email']),
        'password' => $input['password'],
        'phone' => isset($input['phone']) ? trim($input['phone']) : null,
        'role' => isset($input['user_type']) ? $input['user_type'] : 'candidate'
    ];
    
    $result = $userModel->create($userData);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Compte créé avec succès',
            'user_id' => $result['user_id']
        ]);
    } else {
        echo json_encode($result);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>