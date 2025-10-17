<?php
/**
 * AdminModel - Modèle pour la gestion administrative
 * Toutes les requêtes SQL pour le panel admin
 */

require_once __DIR__ . '/../config/config.php';

class AdminModel {
    private $pdo;
    
    public function __construct() {
        try {
            // Utiliser la même méthode que les autres modèles
            $database = Database::getInstance();
            $this->pdo = $database->getConnection();
        } catch (Exception $e) {
            error_log("Erreur de connexion AdminModel: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }
    
    // ================================
    // STATISTIQUES DASHBOARD
    // ================================
    
    public function getDashboardStats() {
        try {
            $stats = [];
            
            // Nombre total d'utilisateurs
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = $stmt->fetch()['total'];
            
            // Nombre total d'entreprises
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM companies");
            $stats['total_companies'] = $stmt->fetch()['total'];
            
            // Nombre total d'offres
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM offers");
            $stats['total_offers'] = $stmt->fetch()['total'];
            
            // Nombre total de candidatures
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM applications");
            $stats['total_applications'] = $stmt->fetch()['total'];
            
            // Candidatures par statut
            $stmt = $this->pdo->query("
                SELECT status, COUNT(*) as count 
                FROM applications 
                GROUP BY status
            ");
            $stats['applications_by_status'] = $stmt->fetchAll();
            
            // Utilisateurs par rôle
            $stmt = $this->pdo->query("
                SELECT role, COUNT(*) as count 
                FROM users 
                GROUP BY role
            ");
            $stats['users_by_role'] = $stmt->fetchAll();
            
            // Offres récentes (7 derniers jours)
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM offers 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            $stats['recent_offers'] = $stmt->fetch()['total'];
            
            // Candidatures récentes (7 derniers jours)
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM applications 
                WHERE application_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            $stats['recent_applications'] = $stmt->fetch()['total'];
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur getDashboardStats: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des statistiques");
        }
    }
    
    // ================================
    // GESTION DES UTILISATEURS
    // ================================
    
    public function getUsers($limit, $offset, $search = '', $role = '') {
        try {
            // Convertir en entiers pour éviter les injections SQL
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT user_id, first_name, last_name, email, phone, role, created_at 
                    FROM users WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (!empty($role)) {
                $sql .= " AND role = ?";
                $params[] = $role;
            }
            
            // Ajouter LIMIT et OFFSET directement dans la requête
            $sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getUsers: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des utilisateurs");
        }
    }
    
    public function countUsers($search = '', $role = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (!empty($role)) {
                $sql .= " AND role = ?";
                $params[] = $role;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['total'];
        } catch (PDOException $e) {
            error_log("Erreur countUsers: " . $e->getMessage());
            throw new Exception("Erreur lors du comptage des utilisateurs");
        }
    }
    
    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT user_id, first_name, last_name, email, phone, role, created_at 
                FROM users WHERE user_id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur getUserById: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération de l'utilisateur");
        }
    }
    
    public function emailExists($email, $excludeUserId = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
            $params = [$email];
            
            if ($excludeUserId) {
                $sql .= " AND user_id != ?";
                $params[] = $excludeUserId;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['count'] > 0;
        } catch (PDOException $e) {
            error_log("Erreur emailExists: " . $e->getMessage());
            throw new Exception("Erreur lors de la vérification de l'email");
        }
    }
    
    public function createUser($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO users (first_name, last_name, email, phone, password, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['password'],
                $data['role']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur createUser: " . $e->getMessage());
            throw new Exception("Erreur lors de la création de l'utilisateur");
        }
    }
    
    public function updateUser($id, $data) {
        try {
            $fields = [];
            $params = [];
            
            foreach (['first_name', 'last_name', 'email', 'phone', 'role'] as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (isset($data['password'])) {
                $fields[] = "password = ?";
                $params[] = $data['password'];
            }
            
            $params[] = $id;
            
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur updateUser: " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour de l'utilisateur");
        }
    }
    
    public function deleteUser($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur deleteUser: " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de l'utilisateur");
        }
    }
    
    // ================================
    // GESTION DES ENTREPRISES
    // ================================
    
    public function getCompanies($limit, $offset, $search = '') {
        try {
            // Convertir en entiers pour éviter les injections SQL
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT id_companies, name, email, phone, location, description, website, created_at 
                    FROM companies WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (name LIKE ? OR email LIKE ? OR location LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // Ajouter LIMIT et OFFSET directement dans la requête
            $sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getCompanies: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des entreprises");
        }
    }
    
    public function countCompanies($search = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM companies WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (name LIKE ? OR email LIKE ? OR location LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['total'];
        } catch (PDOException $e) {
            error_log("Erreur countCompanies: " . $e->getMessage());
            throw new Exception("Erreur lors du comptage des entreprises");
        }
    }
    
    public function getCompanyById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM companies WHERE id_companies = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur getCompanyById: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération de l'entreprise");
        }
    }
    
    public function companyEmailExists($email, $excludeCompanyId = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM companies WHERE email = ?";
            $params = [$email];
            
            if ($excludeCompanyId) {
                $sql .= " AND id_companies != ?";
                $params[] = $excludeCompanyId;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['count'] > 0;
        } catch (PDOException $e) {
            error_log("Erreur companyEmailExists: " . $e->getMessage());
            throw new Exception("Erreur lors de la vérification de l'email");
        }
    }
    
    public function createCompany($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO companies (name, email, phone, location, description, website) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'] ?? null,
                $data['location'] ?? null,
                $data['description'] ?? null,
                $data['website'] ?? null
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur createCompany: " . $e->getMessage());
            throw new Exception("Erreur lors de la création de l'entreprise");
        }
    }
    
    public function updateCompany($id, $data) {
        try {
            $fields = [];
            $params = [];
            
            foreach (['name', 'email', 'phone', 'location', 'description', 'website'] as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            $params[] = $id;
            
            $sql = "UPDATE companies SET " . implode(', ', $fields) . " WHERE id_companies = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur updateCompany: " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour de l'entreprise");
        }
    }
    
    public function deleteCompany($id) {
        try {
            // Les offres seront supprimées automatiquement grâce à la contrainte CASCADE
            $stmt = $this->pdo->prepare("DELETE FROM companies WHERE id_companies = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur deleteCompany: " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de l'entreprise");
        }
    }
    
    // ================================
    // GESTION DES OFFRES D'EMPLOI
    // ================================
    
    public function getOffers($limit, $offset, $search = '', $contract_type = '') {
        try {
            // Convertir en entiers pour éviter les injections SQL
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT o.offers_id, o.title, o.description, o.location, o.contract_type, 
                           o.salary, o.published_date, o.created_at, c.name as company_name
                    FROM offers o 
                    JOIN companies c ON o.id_companies = c.id_companies 
                    WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (o.title LIKE ? OR o.description LIKE ? OR o.location LIKE ? OR c.name LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (!empty($contract_type)) {
                $sql .= " AND o.contract_type = ?";
                $params[] = $contract_type;
            }
            
            // Ajouter LIMIT et OFFSET directement dans la requête
            $sql .= " ORDER BY o.created_at DESC LIMIT $limit OFFSET $offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getOffers: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des offres");
        }
    }
    
    public function countOffers($search = '', $contract_type = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM offers o 
                    JOIN companies c ON o.id_companies = c.id_companies 
                    WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (o.title LIKE ? OR o.description LIKE ? OR o.location LIKE ? OR c.name LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (!empty($contract_type)) {
                $sql .= " AND o.contract_type = ?";
                $params[] = $contract_type;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['total'];
        } catch (PDOException $e) {
            error_log("Erreur countOffers: " . $e->getMessage());
            throw new Exception("Erreur lors du comptage des offres");
        }
    }
    
    public function getOfferById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, c.name as company_name 
                FROM offers o 
                JOIN companies c ON o.id_companies = c.id_companies 
                WHERE o.offers_id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur getOfferById: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération de l'offre");
        }
    }
    
    public function createOffer($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO offers (id_companies, title, description, long_description, location, 
                                  contract_type, salary, published_date, job_requirements, company_info) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            // Préparer les données JSON
            $job_requirements = null;
            if (!empty($data['job_requirements'])) {
                $job_requirements = is_string($data['job_requirements']) ? 
                    $data['job_requirements'] : json_encode($data['job_requirements']);
            }
            
            $company_info = null;
            if (!empty($data['company_info'])) {
                $company_info = is_string($data['company_info']) ? 
                    $data['company_info'] : json_encode($data['company_info']);
            }
            
            $stmt->execute([
                $data['id_companies'],
                $data['title'],
                $data['description'],
                $data['long_description'] ?? null,
                $data['location'],
                $data['contract_type'],
                $data['salary'] ?? null,
                $data['published_date'] ?? date('Y-m-d'),
                $job_requirements,
                $company_info
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur createOffer: " . $e->getMessage());
            throw new Exception("Erreur lors de la création de l'offre");
        }
    }
    
    public function updateOffer($id, $data) {
        try {
            $fields = [];
            $params = [];
            
            $allowedFields = ['id_companies', 'title', 'description', 'long_description', 
                            'location', 'contract_type', 'salary', 'published_date'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            // Gérer les champs JSON
            if (isset($data['job_requirements'])) {
                $fields[] = "job_requirements = ?";
                $params[] = is_string($data['job_requirements']) ? 
                    $data['job_requirements'] : json_encode($data['job_requirements']);
            }
            
            if (isset($data['company_info'])) {
                $fields[] = "company_info = ?";
                $params[] = is_string($data['company_info']) ? 
                    $data['company_info'] : json_encode($data['company_info']);
            }
            
            $params[] = $id;
            
            $sql = "UPDATE offers SET " . implode(', ', $fields) . " WHERE offers_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur updateOffer: " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour de l'offre");
        }
    }
    
    public function deleteOffer($id) {
        try {
            // Les candidatures seront supprimées automatiquement grâce à la contrainte CASCADE
            $stmt = $this->pdo->prepare("DELETE FROM offers WHERE offers_id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur deleteOffer: " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de l'offre");
        }
    }
    
    // ================================
    // GESTION DES CANDIDATURES
    // ================================
    
    public function getApplications($limit, $offset, $search = '', $status = '') {
        try {
            // Convertir en entiers pour éviter les injections SQL
            $limit = (int)$limit;
            $offset = (int)$offset;
            
            $sql = "SELECT a.id, a.job_id, a.applicant_name, a.applicant_email, a.applicant_phone,
                           a.cover_letter, a.application_date, a.status, o.title as job_title, 
                           c.name as company_name
                    FROM applications a 
                    JOIN offers o ON a.job_id = o.offers_id 
                    JOIN companies c ON o.id_companies = c.id_companies 
                    WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (a.applicant_name LIKE ? OR a.applicant_email LIKE ? OR o.title LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (!empty($status)) {
                $sql .= " AND a.status = ?";
                $params[] = $status;
            }
            
            // Ajouter LIMIT et OFFSET directement dans la requête
            $sql .= " ORDER BY a.application_date DESC LIMIT $limit OFFSET $offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getApplications: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des candidatures");
        }
    }
    
    public function countApplications($search = '', $status = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM applications a 
                    JOIN offers o ON a.job_id = o.offers_id 
                    WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (a.applicant_name LIKE ? OR a.applicant_email LIKE ? OR o.title LIKE ?)";
                $searchTerm = "%$search%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (!empty($status)) {
                $sql .= " AND a.status = ?";
                $params[] = $status;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['total'];
        } catch (PDOException $e) {
            error_log("Erreur countApplications: " . $e->getMessage());
            throw new Exception("Erreur lors du comptage des candidatures");
        }
    }
    
    public function updateApplicationStatus($id, $status) {
        try {
            $stmt = $this->pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("Erreur updateApplicationStatus: " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour du statut");
        }
    }
    
    public function deleteApplication($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM applications WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur deleteApplication: " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de la candidature");
        }
    }
    
    // ================================
    // MÉTHODES UTILITAIRES
    // ================================
    
    public function getAllCompaniesForSelect() {
        try {
            $stmt = $this->pdo->query("SELECT id_companies, name FROM companies ORDER BY name");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur getAllCompaniesForSelect: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des entreprises");
        }
    }
}
?>