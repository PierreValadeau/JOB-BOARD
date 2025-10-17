<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit;
}

require_once __DIR__ . '/../../model/CompanyModel.php';

$method = $_SERVER['REQUEST_METHOD'];
$companyModel = new CompanyModel();

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $result = $companyModel->getById($_GET['id']);
                echo json_encode($result);
            } elseif (isset($_GET['all'])) {
                $result = $companyModel->getAll(1000, 0);
                echo json_encode($result);
            } else {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $offset = ($page - 1) * $limit;
                
                $result = $companyModel->getAll($limit, $offset);
                echo json_encode($result);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['name'])) {
                echo json_encode(['success' => false, 'message' => 'Nom de l\'entreprise manquant']);
                exit;
            }
            
            $result = $companyModel->create($input);
            echo json_encode($result);
            break;
            
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['company_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID entreprise manquant']);
                exit;
            }
            
            $id = $input['company_id'];
            unset($input['company_id']);
            
            $result = $companyModel->update($id, $input);
            echo json_encode($result);
            break;
            
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID entreprise manquant']);
                exit;
            }
            
            $result = $companyModel->delete($input['id']);
            echo json_encode($result);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Méthode non supportée']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>