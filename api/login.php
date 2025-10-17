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
    if (!isset($input['email']) || !isset($input['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis']);
        exit;
    }
    $controller = new UserController();
    $userModel = new UserModel();
    $result = $userModel->login($input['email'], $input['password']);
    if ($result['success']) {
        session_start();
        $_SESSION['user_id'] = $result['user']['user_id'];
        $_SESSION['user_email'] = $result['user']['email'];
        $_SESSION['user_role'] = $result['user']['role'];
        $_SESSION['user_name'] = $result['user']['first_name'] . ' ' . $result['user']['last_name'];
        $_SESSION['user_phone'] = $result['user']['phone'] ?? '';
        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => $result['user'],
            'redirect_admin' => ($result['user']['role'] === 'admin')
        ]);
    } else {
        echo json_encode($result);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>