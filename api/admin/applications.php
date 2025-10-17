<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit;
}
require_once __DIR__ . '/../../model/ApplicationModel.php';
$method = $_SERVER['REQUEST_METHOD'];
$applicationModel = new ApplicationModel();
try {
    switch ($method) {
        case 'GET':
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;
            $result = $applicationModel->getAll($limit, $offset);
            echo json_encode($result);
            break;
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID candidature manquant']);
                exit;
            }
            $result = $applicationModel->delete($input['id']);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Méthode non supportée']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>