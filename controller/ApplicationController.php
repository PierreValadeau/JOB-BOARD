<?php
require_once __DIR__ . '/../model/ApplicationModel.php';

class ApplicationController {
    private $applicationModel;
    
    public function __construct() {
        $this->applicationModel = new ApplicationModel();
    }
    
    public function handleRequest() {
        header('Content-Type: application/json');
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'POST':
                $this->createApplication();
                break;
            case 'GET':
                $this->getApplications();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                break;
        }
    }
    
    private function createApplication() {
        try {
            // Récupérer les données JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            // Validation des données obligatoires
            if (!isset($data['job_id']) || !isset($data['applicant_name']) || !isset($data['applicant_email'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Données manquantes: job_id, applicant_name et applicant_email sont requis']);
                return;
            }
            
            // Validation de l'email
            if (!filter_var($data['applicant_email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(['error' => 'Adresse email invalide']);
                return;
            }
            
            // Vérifier si l'utilisateur a déjà postulé
            if ($this->applicationModel->hasUserApplied($data['job_id'], $data['applicant_email'])) {
                http_response_code(409);
                echo json_encode(['error' => 'Vous avez déjà postulé pour cette offre']);
                return;
            }
            
            // Créer la candidature
            $result = $this->applicationModel->createApplication($data);
            
            if ($result['success']) {
                http_response_code(201);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['error' => $result['message']]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
    
    private function getApplications() {
        try {
            // Si un job_id est fourni, récupérer les candidatures pour cette offre
            if (isset($_GET['job_id'])) {
                $jobId = (int)$_GET['job_id'];
                $applications = $this->applicationModel->getApplicationsForJob($jobId);
            } else {
                // Sinon, récupérer toutes les candidatures
                $applications = $this->applicationModel->getAllApplications();
            }
            
            echo json_encode($applications);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
}

// Si le fichier est appelé directement
if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__)) {
    $controller = new ApplicationController();
    $controller->handleRequest();
}
?>