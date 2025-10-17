<?php
/**
 * AdminController - Contrôleur pour la gestion administrative
 * Gère toutes les opérations CRUD pour les entités
 */

require_once __DIR__ . '/../model/AdminModel.php';
require_once __DIR__ . '/../config/config.php';

class AdminController {
    private $adminModel;
    
    public function __construct() {
        $this->adminModel = new AdminModel();
    }
    
    /**
     * Vérifie si l'utilisateur connecté est un administrateur
     */
    public function checkAdminAccess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            throw new Exception('Accès refusé. Droits administrateur requis.');
        }
    }
    
    /**
     * Récupère les statistiques du dashboard
     */
    public function getDashboardStats() {
        $this->checkAdminAccess();
        try {
            $stats = $this->adminModel->getDashboardStats();
            return json_encode([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            error_log("Erreur getDashboardStats: " . $e->getMessage());
            return json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des statistiques',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    // ================================
    // GESTION DES UTILISATEURS
    // ================================
    
    /**
     * Récupère tous les utilisateurs avec pagination et filtres
     */
    public function getUsers($page = 1, $search = '', $role = '') {
        $this->checkAdminAccess();
        try {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $users = $this->adminModel->getUsers($limit, $offset, $search, $role);
            $total = $this->adminModel->countUsers($search, $role);
            
            return json_encode([
                'success' => true,
                'users' => $users,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page
            ]);
        } catch (Exception $e) {
            error_log("Erreur getUsers: " . $e->getMessage());
            return json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des utilisateurs',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Récupère un utilisateur par son ID
     */
    public function getUser($id) {
        $this->checkAdminAccess();
        try {
            $user = $this->adminModel->getUserById($id);
            if ($user) {
                // Ne pas retourner le mot de passe
                unset($user['password']);
                return json_encode($user);
            } else {
                return json_encode(['error' => 'Utilisateur non trouvé']);
            }
        } catch (Exception $e) {
            error_log("Erreur getUser: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la récupération de l\'utilisateur']);
        }
    }
    
    /**
     * Crée un nouvel utilisateur
     */
    public function createUser($data) {
        $this->checkAdminAccess();
        try {
            // Validation des données
            $errors = $this->validateUserData($data);
            if (!empty($errors)) {
                return json_encode(['error' => 'Données invalides', 'details' => $errors]);
            }
            
            // Vérifier si l'email existe déjà
            if ($this->adminModel->emailExists($data['email'])) {
                return json_encode(['error' => 'Cet email est déjà utilisé']);
            }
            
            // Hasher le mot de passe
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $userId = $this->adminModel->createUser($data);
            if ($userId) {
                return json_encode(['success' => true, 'user_id' => $userId, 'message' => 'Utilisateur créé avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la création de l\'utilisateur']);
            }
        } catch (Exception $e) {
            error_log("Erreur createUser: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la création de l\'utilisateur']);
        }
    }
    
    /**
     * Met à jour un utilisateur
     */
    public function updateUser($id, $data) {
        $this->checkAdminAccess();
        try {
            // Validation des données
            $errors = $this->validateUserData($data, $id);
            if (!empty($errors)) {
                return json_encode(['error' => 'Données invalides', 'details' => $errors]);
            }
            
            // Vérifier si l'email existe déjà (sauf pour cet utilisateur)
            if ($this->adminModel->emailExists($data['email'], $id)) {
                return json_encode(['error' => 'Cet email est déjà utilisé']);
            }
            
            // Si un nouveau mot de passe est fourni, le hasher
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                unset($data['password']); // Ne pas mettre à jour le mot de passe si vide
            }
            
            if ($this->adminModel->updateUser($id, $data)) {
                return json_encode(['success' => true, 'message' => 'Utilisateur mis à jour avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la mise à jour de l\'utilisateur']);
            }
        } catch (Exception $e) {
            error_log("Erreur updateUser: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la mise à jour de l\'utilisateur']);
        }
    }
    
    /**
     * Supprime un utilisateur
     */
    public function deleteUser($id) {
        $this->checkAdminAccess();
        try {
            // Empêcher la suppression de son propre compte
            if ($id == $_SESSION['user_id']) {
                return json_encode(['error' => 'Vous ne pouvez pas supprimer votre propre compte']);
            }
            
            if ($this->adminModel->deleteUser($id)) {
                return json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la suppression de l\'utilisateur']);
            }
        } catch (Exception $e) {
            error_log("Erreur deleteUser: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la suppression de l\'utilisateur']);
        }
    }
    
    // ================================
    // GESTION DES ENTREPRISES
    // ================================
    
    /**
     * Récupère toutes les entreprises
     */
    public function getCompanies($page = 1, $search = '') {
        $this->checkAdminAccess();
        try {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $companies = $this->adminModel->getCompanies($limit, $offset, $search);
            $total = $this->adminModel->countCompanies($search);
            
            return json_encode([
                'success' => true,
                'companies' => $companies,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page
            ]);
        } catch (Exception $e) {
            error_log("Erreur getCompanies: " . $e->getMessage());
            return json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des entreprises',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Récupère une entreprise par son ID
     */
    public function getCompany($id) {
        $this->checkAdminAccess();
        try {
            $company = $this->adminModel->getCompanyById($id);
            if ($company) {
                return json_encode($company);
            } else {
                return json_encode(['error' => 'Entreprise non trouvée']);
            }
        } catch (Exception $e) {
            error_log("Erreur getCompany: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la récupération de l\'entreprise']);
        }
    }
    
    /**
     * Crée une nouvelle entreprise
     */
    public function createCompany($data) {
        $this->checkAdminAccess();
        try {
            $errors = $this->validateCompanyData($data);
            if (!empty($errors)) {
                return json_encode(['error' => 'Données invalides', 'details' => $errors]);
            }
            
            if ($this->adminModel->companyEmailExists($data['email'])) {
                return json_encode(['error' => 'Cet email est déjà utilisé']);
            }
            
            $companyId = $this->adminModel->createCompany($data);
            if ($companyId) {
                return json_encode(['success' => true, 'company_id' => $companyId, 'message' => 'Entreprise créée avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la création de l\'entreprise']);
            }
        } catch (Exception $e) {
            error_log("Erreur createCompany: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la création de l\'entreprise']);
        }
    }
    
    /**
     * Met à jour une entreprise
     */
    public function updateCompany($id, $data) {
        $this->checkAdminAccess();
        try {
            $errors = $this->validateCompanyData($data);
            if (!empty($errors)) {
                return json_encode(['error' => 'Données invalides', 'details' => $errors]);
            }
            
            if ($this->adminModel->companyEmailExists($data['email'], $id)) {
                return json_encode(['error' => 'Cet email est déjà utilisé']);
            }
            
            if ($this->adminModel->updateCompany($id, $data)) {
                return json_encode(['success' => true, 'message' => 'Entreprise mise à jour avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la mise à jour de l\'entreprise']);
            }
        } catch (Exception $e) {
            error_log("Erreur updateCompany: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la mise à jour de l\'entreprise']);
        }
    }
    
    /**
     * Supprime une entreprise
     */
    public function deleteCompany($id) {
        $this->checkAdminAccess();
        try {
            if ($this->adminModel->deleteCompany($id)) {
                return json_encode(['success' => true, 'message' => 'Entreprise supprimée avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la suppression de l\'entreprise']);
            }
        } catch (Exception $e) {
            error_log("Erreur deleteCompany: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la suppression de l\'entreprise']);
        }
    }
    
    // ================================
    // GESTION DES OFFRES D'EMPLOI
    // ================================
    
    /**
     * Récupère toutes les offres
     */
    public function getOffers($page = 1, $search = '', $contract_type = '') {
        $this->checkAdminAccess();
        try {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $offers = $this->adminModel->getOffers($limit, $offset, $search, $contract_type);
            $total = $this->adminModel->countOffers($search, $contract_type);
            
            return json_encode([
                'success' => true,
                'offers' => $offers,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page
            ]);
        } catch (Exception $e) {
            error_log("Erreur getOffers: " . $e->getMessage());
            return json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des offres',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Récupère une offre par son ID
     */
    public function getOffer($id) {
        $this->checkAdminAccess();
        try {
            $offer = $this->adminModel->getOfferById($id);
            if ($offer) {
                return json_encode($offer);
            } else {
                return json_encode(['error' => 'Offre non trouvée']);
            }
        } catch (Exception $e) {
            error_log("Erreur getOffer: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la récupération de l\'offre']);
        }
    }
    
    /**
     * Crée une nouvelle offre
     */
    public function createOffer($data) {
        $this->checkAdminAccess();
        try {
            $errors = $this->validateOfferData($data);
            if (!empty($errors)) {
                return json_encode(['error' => 'Données invalides', 'details' => $errors]);
            }
            
            $companyMode = $data['company_mode'] ?? 'existing';
            
            if ($companyMode === 'manual') {
                // Créer d'abord l'entreprise
                $companyData = [
                    'name' => $data['company_name'],
                    'email' => $data['company_email'],
                    'phone' => $data['company_phone'] ?? null,
                    'website' => $data['company_website'] ?? null,
                    'description' => $data['company_description'] ?? null,
                    'location' => $data['location'] // Utiliser la localisation de l'offre pour l'entreprise
                ];
                
                $companyId = $this->adminModel->createCompany($companyData);
                if (!$companyId) {
                    return json_encode(['error' => 'Erreur lors de la création de l\'entreprise']);
                }
                
                // Utiliser l'ID de la nouvelle entreprise pour l'offre
                $data['id_companies'] = $companyId;
            }
            
            $offerId = $this->adminModel->createOffer($data);
            if ($offerId) {
                return json_encode(['success' => true, 'offer_id' => $offerId, 'message' => 'Offre créée avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la création de l\'offre']);
            }
        } catch (Exception $e) {
            error_log("Erreur createOffer: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la création de l\'offre']);
        }
    }
    
    /**
     * Met à jour une offre
     */
    public function updateOffer($id, $data) {
        $this->checkAdminAccess();
        try {
            $errors = $this->validateOfferData($data);
            if (!empty($errors)) {
                return json_encode(['error' => 'Données invalides', 'details' => $errors]);
            }
            
            if ($this->adminModel->updateOffer($id, $data)) {
                return json_encode(['success' => true, 'message' => 'Offre mise à jour avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la mise à jour de l\'offre']);
            }
        } catch (Exception $e) {
            error_log("Erreur updateOffer: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la mise à jour de l\'offre']);
        }
    }
    
    /**
     * Supprime une offre
     */
    public function deleteOffer($id) {
        $this->checkAdminAccess();
        try {
            if ($this->adminModel->deleteOffer($id)) {
                return json_encode(['success' => true, 'message' => 'Offre supprimée avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la suppression de l\'offre']);
            }
        } catch (Exception $e) {
            error_log("Erreur deleteOffer: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la suppression de l\'offre']);
        }
    }
    
    // ================================
    // GESTION DES CANDIDATURES
    // ================================
    
    /**
     * Récupère toutes les candidatures
     */
    public function getApplications($page = 1, $search = '', $status = '') {
        $this->checkAdminAccess();
        try {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $applications = $this->adminModel->getApplications($limit, $offset, $search, $status);
            $total = $this->adminModel->countApplications($search, $status);
            
            return json_encode([
                'success' => true,
                'applications' => $applications,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page
            ]);
        } catch (Exception $e) {
            error_log("Erreur getApplications: " . $e->getMessage());
            return json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des candidatures',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Met à jour le statut d'une candidature
     */
    public function updateApplicationStatus($id, $status) {
        $this->checkAdminAccess();
        try {
            $validStatuses = ['pending', 'reviewed', 'accepted', 'rejected'];
            if (!in_array($status, $validStatuses)) {
                return json_encode(['error' => 'Statut invalide']);
            }
            
            if ($this->adminModel->updateApplicationStatus($id, $status)) {
                return json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la mise à jour du statut']);
            }
        } catch (Exception $e) {
            error_log("Erreur updateApplicationStatus: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la mise à jour du statut']);
        }
    }
    
    /**
     * Supprime une candidature
     */
    public function deleteApplication($id) {
        $this->checkAdminAccess();
        try {
            if ($this->adminModel->deleteApplication($id)) {
                return json_encode(['success' => true, 'message' => 'Candidature supprimée avec succès']);
            } else {
                return json_encode(['error' => 'Erreur lors de la suppression de la candidature']);
            }
        } catch (Exception $e) {
            error_log("Erreur deleteApplication: " . $e->getMessage());
            return json_encode(['error' => 'Erreur lors de la suppression de la candidature']);
        }
    }
    
    // ================================
    // MÉTHODES DE VALIDATION
    // ================================
    
    private function validateUserData($data, $userId = null) {
        $errors = [];
        
        if (empty($data['first_name']) || strlen($data['first_name']) < 2) {
            $errors[] = 'Le prénom doit contenir au moins 2 caractères';
        }
        
        if (empty($data['last_name']) || strlen($data['last_name']) < 2) {
            $errors[] = 'Le nom doit contenir au moins 2 caractères';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide';
        }
        
        if (($userId === null && empty($data['password'])) || (!empty($data['password']) && strlen($data['password']) < 6)) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        }
        
        $validRoles = ['candidate', 'recruiter', 'admin'];
        if (empty($data['role']) || !in_array($data['role'], $validRoles)) {
            $errors[] = 'Rôle invalide';
        }
        
        return $errors;
    }
    
    private function validateCompanyData($data) {
        $errors = [];
        
        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors[] = 'Le nom de l\'entreprise doit contenir au moins 2 caractères';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide';
        }
        
        return $errors;
    }
    
    private function validateOfferData($data) {
        $errors = [];
        
        if (empty($data['title']) || strlen($data['title']) < 3) {
            $errors[] = 'Le titre doit contenir au moins 3 caractères';
        }
        
        if (empty($data['description']) || strlen($data['description']) < 10) {
            $errors[] = 'La description doit contenir au moins 10 caractères';
        }
        
        if (empty($data['location'])) {
            $errors[] = 'La localisation est requise';
        }
        
        // Validation du mode d'entreprise
        $companyMode = $data['company_mode'] ?? 'existing';
        
        if ($companyMode === 'existing') {
            // Mode entreprise existante : id_companies requis
            if (empty($data['id_companies']) || !is_numeric($data['id_companies'])) {
                $errors[] = 'Entreprise invalide';
            }
        } else if ($companyMode === 'manual') {
            // Mode saisie manuelle : nom et email de l'entreprise requis
            if (empty($data['company_name']) || strlen($data['company_name']) < 2) {
                $errors[] = 'Le nom de l\'entreprise doit contenir au moins 2 caractères';
            }
            
            if (empty($data['company_email']) || !filter_var($data['company_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email de l\'entreprise invalide';
            }
            
            // Vérifier que l'entreprise n'existe pas déjà
            if (!empty($data['company_name']) && !empty($data['company_email'])) {
                $existingCompany = $this->adminModel->findCompanyByNameOrEmail($data['company_name'], $data['company_email']);
                if ($existingCompany) {
                    $errors[] = 'Une entreprise avec ce nom ou cet email existe déjà. Veuillez utiliser le mode "Entreprise existante".';
                }
            }
        }
        
        $validContractTypes = ['CDI', 'CDD', 'Stage', 'Freelance', 'Alternance'];
        if (empty($data['contract_type']) || !in_array($data['contract_type'], $validContractTypes)) {
            $errors[] = 'Type de contrat invalide';
        }
        
        if (!empty($data['salary']) && (!is_numeric($data['salary']) || $data['salary'] < 0)) {
            $errors[] = 'Salaire invalide';
        }
        
        return $errors;
    }
}
?>