<?php
require_once '../model/CompanyModel.php';

class CompanyController {
    private $companyModel;
    
    public function __construct() {
        $this->companyModel = new CompanyModel();
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
                if (isset($segments[2]) && is_numeric($segments[2])) {
                    $this->getCompany($segments[2]);
                } else {
                    $this->getAllCompanies();
                }
                break;
                
            case 'POST':
                $this->createCompany();
                break;
                
            case 'PUT':
                if (isset($segments[2]) && is_numeric($segments[2])) {
                    $this->updateCompany($segments[2]);
                } else {
                    $this->sendResponse(['success' => false, 'message' => 'Company ID required'], 400);
                }
                break;
                
            case 'DELETE':
                if (isset($segments[2]) && is_numeric($segments[2])) {
                    $this->deleteCompany($segments[2]);
                } else {
                    $this->sendResponse(['success' => false, 'message' => 'Company ID required'], 400);
                }
                break;
                
            default:
                $this->sendResponse(['success' => false, 'message' => 'Method not allowed'], 405);
        }
    }
    
    private function getAllCompanies() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        $result = $this->companyModel->getAll($limit, $offset);
        $this->sendResponse($result);
    }
    
    private function getCompany($id) {
        $result = $this->companyModel->getById($id);
        $this->sendResponse($result);
    }
    
    private function createCompany() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid JSON'], 400);
            return;
        }
        
        if (!isset($input['name']) || empty($input['name'])) {
            $this->sendResponse(['success' => false, 'message' => 'Name is required'], 400);
            return;
        }
        
        $result = $this->companyModel->create($input);
        $this->sendResponse($result, $result['success'] ? 201 : 400);
    }
    
    private function updateCompany($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid JSON'], 400);
            return;
        }
        
        $result = $this->companyModel->update($id, $input);
        $this->sendResponse($result);
    }
    
    private function deleteCompany($id) {
        $result = $this->companyModel->delete($id);
        $this->sendResponse($result);
    }
    
    private function sendResponse($data, $httpCode = 200) {
        http_response_code($httpCode);
        echo json_encode($data);
        exit();
    }
}

if (basename($_SERVER['PHP_SELF']) === 'CompanyController.php') {
    $controller = new CompanyController();
    $controller->handleRequest();
}
?>