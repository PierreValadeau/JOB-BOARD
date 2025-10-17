<?php
require_once '../model/JobModel.php';
class JobController {
    private $jobModel;
    public function __construct() {
        $this->jobModel = new JobModel();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($path, '/'));
        switch ($method) {
            case 'GET':
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $this->getJob($_GET['id']);
                } elseif (isset($segments[2]) && is_numeric($segments[2])) {
                    $this->getJob($segments[2]);
                } else {
                    $this->getAllJobs();
                }
                break;
            case 'POST':
                $this->createJob();
                break;
            case 'PUT':
                if (isset($segments[2]) && is_numeric($segments[2])) {
                    $this->updateJob($segments[2]);
                } else {
                    $this->sendResponse(['success' => false, 'message' => 'Job ID required'], 400);
                }
                break;
            case 'DELETE':
                if (isset($segments[2]) && is_numeric($segments[2])) {
                    $this->deleteJob($segments[2]);
                } else {
                    $this->sendResponse(['success' => false, 'message' => 'Job ID required'], 400);
                }
                break;
            default:
                $this->sendResponse(['success' => false, 'message' => 'Method not allowed'], 405);
        }
    }
    private function getAllJobs() {
        $filters = [];
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        if (isset($_GET['location'])) {
            $filters['location'] = $_GET['location'];
        }
        if (isset($_GET['contract_type'])) {
            $filters['contract_type'] = $_GET['contract_type'];
        }
        $result = $this->jobModel->getAll($filters, $limit, $offset);
        $this->sendResponse($result);
    }
    private function getJob($id) {
        $result = $this->jobModel->getById($id);
        $this->sendResponse($result);
    }
    private function createJob() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid JSON'], 400);
            return;
        }
        $required = ['title', 'id_companies'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                $this->sendResponse(['success' => false, 'message' => "$field is required"], 400);
                return;
            }
        }
        $result = $this->jobModel->create($input);
        $this->sendResponse($result, $result['success'] ? 201 : 400);
    }
    private function updateJob($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid JSON'], 400);
            return;
        }
        $result = $this->jobModel->update($id, $input);
        $this->sendResponse($result);
    }
    private function deleteJob($id) {
        $result = $this->jobModel->delete($id);
        $this->sendResponse($result);
    }
    private function sendResponse($data, $httpCode = 200) {
        http_response_code($httpCode);
        echo json_encode($data);
        exit();
    }
}
if (basename($_SERVER['PHP_SELF']) === 'JobController.php') {
    $controller = new JobController();
    $controller->handleRequest();
}
?>