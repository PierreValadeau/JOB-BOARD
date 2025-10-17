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
require_once __DIR__ . '/../../model/JobModel.php';
$method = $_SERVER['REQUEST_METHOD'];
$jobModel = new JobModel();
try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $result = $jobModel->getById($_GET['id']);
                echo json_encode($result);
            } else {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $offset = ($page - 1) * $limit;
                $result = $jobModel->getAll([], $limit, $offset);
                echo json_encode($result);
            }
            break;
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['title'], $input['description'], $input['location'], $input['company_id'])) {
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit;
            }
            $result = $jobModel->create($input);
            echo json_encode($result);
            break;
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['job_id'])) {
                echo json_encode(['success' => false, 'message' => 'ID offre manquant']);
                exit;
            }
            $id = $input['job_id'];
            unset($input['job_id']);
            $result = $jobModel->update($id, $input);
            echo json_encode($result);
            break;
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID offre manquant']);
                exit;
            }
            $result = $jobModel->delete($input['id']);
            echo json_encode($result);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Méthode non supportée']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>