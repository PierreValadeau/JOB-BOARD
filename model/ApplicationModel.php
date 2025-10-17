<?php
require_once __DIR__ . '/../config/config.php';

class ApplicationModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    

    public function createApplication($data) {
        try {
            $sql = "INSERT INTO applications (job_id, applicant_name, applicant_email, applicant_phone, cover_letter) 
                    VALUES (:job_id, :applicant_name, :applicant_email, :applicant_phone, :cover_letter)";
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                ':job_id' => $data['job_id'],
                ':applicant_name' => $data['applicant_name'],
                ':applicant_email' => $data['applicant_email'],
                ':applicant_phone' => $data['applicant_phone'] ?? null,
                ':cover_letter' => $data['cover_letter'] ?? null
            ]);
            
            if ($result) {
                return [
                    'success' => true,
                    'application_id' => $this->db->lastInsertId(),
                    'message' => 'Candidature envoyée avec succès'
                ];
            }
            
            return ['success' => false, 'message' => 'Erreur lors de l\'envoi de la candidature'];
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Vous avez déjà postulé pour cette offre'];
            }
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }
    

    public function getApplicationsForJob($jobId) {
        try {
        $sql = "SELECT a.*, o.title as job_title 
            FROM applications a 
            JOIN offers o ON a.job_id = o.offers_id 
            WHERE a.job_id = :job_id 
            ORDER BY a.application_date DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':job_id' => $jobId]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    

    public function getAllApplications() {
        try {
        $sql = "SELECT a.*, o.title as job_title, o.company_name 
            FROM applications a 
            JOIN offers o ON a.job_id = o.offers_id 
            ORDER BY a.application_date DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    

    public function updateApplicationStatus($applicationId, $status) {
        try {
            $sql = "UPDATE applications SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':status' => $status,
                ':id' => $applicationId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    

    public function hasUserApplied($jobId, $email) {
        try {
            $sql = "SELECT COUNT(*) FROM applications WHERE job_id = :job_id AND applicant_email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':job_id' => $jobId, ':email' => $email]);
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>