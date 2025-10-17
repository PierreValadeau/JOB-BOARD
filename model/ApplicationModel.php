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
    
    public function getAll($limit = 10, $offset = 0) {
        try {
            $sql = "SELECT a.id as application_id, a.applicant_name as user_name, 
                          a.applicant_email, a.applicant_phone, a.cover_letter, 
                          a.application_date, a.status,
                          o.title as job_title, 
                          c.name as company_name 
                    FROM applications a 
                    JOIN offers o ON a.job_id = o.offers_id 
                    JOIN companies c ON o.id_companies = c.id_companies
                    ORDER BY a.application_date DESC 
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Compter le total
            $countSql = "SELECT COUNT(*) as total FROM applications";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];
            
            return [
                'success' => true,
                'applications' => $applications,
                'total' => (int)$total
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération des candidatures: ' . $e->getMessage(),
                'applications' => [],
                'total' => 0
            ];
        }
    }

    public function getAllApplications() {
        try {
            $sql = "SELECT a.*, o.title as job_title, c.name as company_name 
                    FROM applications a 
                    JOIN offers o ON a.job_id = o.offers_id 
                    JOIN companies c ON o.id_companies = c.id_companies
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

    public function delete($id) {
        try {
            $sql = "DELETE FROM applications WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':id' => $id]);
            
            if ($result && $stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Candidature supprimée avec succès'];
            }
            
            return ['success' => false, 'message' => 'Candidature non trouvée'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }
}
?>