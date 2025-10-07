<?php
require_once '../config/config.php';

class CompanyController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
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
        
        switch ($method) {
            case 'GET':
                $this->getAllCompanies();
                break;
                
            case 'POST':
                $this->createCompany();
                break;
                
            default:
                $this->sendResponse(['success' => false, 'message' => 'Method not allowed'], 405);
        }
    }
    
    private function getAllCompanies() {
        $sql = "SELECT * FROM companies ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $companies = $stmt->fetchAll();
        
        $this->sendResponse([
            'success' => true,
            'companies' => $companies,
            'total' => count($companies)
        ]);
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
        
        $sql = "INSERT INTO companies (name, industry, description) VALUES (:name, :industry, :description)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $input['name'],
            ':industry' => $input['industry'] ?? null,
            ':description' => $input['description'] ?? null
        ]);
        
        $this->sendResponse([
            'success' => true,
            'company_id' => $this->db->lastInsertId(),
            'message' => 'Company created'
        ], 201);
    }
    
    private function sendResponse($data, $httpCode = 200) {
        http_response_code($httpCode);
        echo json_encode($data);
        exit();
    }
}

$controller = new CompanyController();
$controller->handleRequest();
?>