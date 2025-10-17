<?php
/**
 * API Admin - Point d'entrée pour toutes les opérations administratives
 * Routes pour les appels AJAX du panel admin
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../controller/AdminController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminController = new AdminController();

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    
    $action = $_GET['action'] ?? '';
    $entity = $_GET['entity'] ?? '';
    $id = $_GET['id'] ?? null;
    
    $input = file_get_contents('php://input');
    $data = $input ? json_decode($input, true) : $_POST;
    
    switch ($action) {
        case 'dashboard':
            echo $adminController->getDashboardStats();
            break;
            
        case 'get_users':
            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            $role = $_GET['role'] ?? '';
            echo $adminController->getUsers($page, $search, $role);
            break;
            
        case 'get_user':
            if (!$id) {
                throw new Exception('ID utilisateur requis');
            }
            echo $adminController->getUser($id);
            break;
            
        case 'create_user':
            if ($method !== 'POST') {
                throw new Exception('Méthode POST requise');
            }
            echo $adminController->createUser($data);
            break;
            
        case 'update_user':
            if ($method !== 'POST' && $method !== 'PUT') {
                throw new Exception('Méthode POST/PUT requise');
            }
            if (!$id) {
                throw new Exception('ID utilisateur requis');
            }
            echo $adminController->updateUser($id, $data);
            break;
            
        case 'delete_user':
            if ($method !== 'DELETE' && $method !== 'POST') {
                throw new Exception('Méthode DELETE requise');
            }
            if (!$id) {
                throw new Exception('ID utilisateur requis');
            }
            echo $adminController->deleteUser($id);
            break;
            

        case 'get_companies':
            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            echo $adminController->getCompanies($page, $search);
            break;
            
        case 'get_company':
            if (!$id) {
                throw new Exception('ID entreprise requis');
            }
            echo $adminController->getCompany($id);
            break;
            
        case 'create_company':
            if ($method !== 'POST') {
                throw new Exception('Méthode POST requise');
            }
            echo $adminController->createCompany($data);
            break;
            
        case 'update_company':
            if ($method !== 'POST' && $method !== 'PUT') {
                throw new Exception('Méthode POST/PUT requise');
            }
            if (!$id) {
                throw new Exception('ID entreprise requis');
            }
            echo $adminController->updateCompany($id, $data);
            break;
            
        case 'delete_company':
            if ($method !== 'DELETE' && $method !== 'POST') {
                throw new Exception('Méthode DELETE requise');
            }
            if (!$id) {
                throw new Exception('ID entreprise requis');
            }
            echo $adminController->deleteCompany($id);
            break;
            
  
        case 'get_offers':
            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            $contract_type = $_GET['contract_type'] ?? '';
            echo $adminController->getOffers($page, $search, $contract_type);
            break;
            
        case 'get_offer':
            if (!$id) {
                throw new Exception('ID offre requis');
            }
            echo $adminController->getOffer($id);
            break;
            
        case 'create_offer':
            if ($method !== 'POST') {
                throw new Exception('Méthode POST requise');
            }
            echo $adminController->createOffer($data);
            break;
            
        case 'update_offer':
            if ($method !== 'POST' && $method !== 'PUT') {
                throw new Exception('Méthode POST/PUT requise');
            }
            if (!$id) {
                throw new Exception('ID offre requis');
            }
            echo $adminController->updateOffer($id, $data);
            break;
            
        case 'delete_offer':
            if ($method !== 'DELETE' && $method !== 'POST') {
                throw new Exception('Méthode DELETE requise');
            }
            if (!$id) {
                throw new Exception('ID offre requis');
            }
            echo $adminController->deleteOffer($id);
            break;
            
     
        case 'get_applications':
            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            echo $adminController->getApplications($page, $search, $status);
            break;
            
        case 'update_application_status':
            if ($method !== 'POST' && $method !== 'PUT') {
                throw new Exception('Méthode POST/PUT requise');
            }
            if (!$id) {
                throw new Exception('ID candidature requis');
            }
            $status = $data['status'] ?? '';
            if (!$status) {
                throw new Exception('Statut requis');
            }
            echo $adminController->updateApplicationStatus($id, $status);
            break;
            
        case 'delete_application':
            if ($method !== 'DELETE' && $method !== 'POST') {
                throw new Exception('Méthode DELETE requise');
            }
            if (!$id) {
                throw new Exception('ID candidature requis');
            }
            echo $adminController->deleteApplication($id);
            break;
            
      
        case 'get_companies_list':
            require_once __DIR__ . '/../model/AdminModel.php';
            $adminModel = new AdminModel();
            $companies = $adminModel->getAllCompaniesForSelect();
            echo json_encode($companies);
            break;
            
        default:
            throw new Exception('Action non reconnue: ' . $action);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'message' => $e->getMessage()
    ]);
}
?>