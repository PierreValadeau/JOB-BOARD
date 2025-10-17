<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrateur - Job Board</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
    <?php
    session_start();
    
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: login.php?redirect=' . urlencode('admin.php'));
        exit();
    }
    ?>
    
    <!-- Header -->
    <header class="admin-header">
        <h1><i class="fas fa-cogs"></i> Panel Administrateur</h1>
        <div class="admin-user-info">
            <span>Connecté en tant que <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
            <a href="../api/logout.php?redirect=index" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </header>

    <!-- Container principal -->
    <div class="admin-container">
        <!-- Messages d'alerte -->
        <div id="alert-container"></div>
        
        <!-- Navigation par onglets -->
        <div class="admin-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="dashboard">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </button>
                <button class="tab-btn" data-tab="users">
                    <i class="fas fa-users"></i> Utilisateurs
                </button>
                <button class="tab-btn" data-tab="companies">
                    <i class="fas fa-building"></i> Entreprises
                </button>
                <button class="tab-btn" data-tab="offers">
                    <i class="fas fa-briefcase"></i> Offres d'emploi
                </button>
                <button class="tab-btn" data-tab="applications">
                    <i class="fas fa-file-alt"></i> Candidatures
                </button>
            </div>

            <!-- Contenu Dashboard -->
            <div id="dashboard" class="tab-content active">
                <h2>Statistiques générales</h2>
                <div class="stats-grid" id="stats-grid">
                    <!-- Les stats seront chargées dynamiquement -->
                </div>
                
                <div class="admin-actions">
                    <button class="btn btn-primary" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt"></i> Actualiser
                    </button>
                </div>
            </div>

            <!-- Contenu Utilisateurs -->
            <div id="users" class="tab-content">
                <h2>Gestion des utilisateurs</h2>

                <div class="admin-table" id="users-table">
                    <!-- Tableau des utilisateurs chargé dynamiquement -->
                </div>
                
                <div class="pagination" id="users-pagination">
                    <!-- Pagination chargée dynamiquement -->
                </div>
            </div>

            <!-- Contenu Entreprises -->
            <div id="companies" class="tab-content">
                <h2>Gestion des entreprises</h2>

                <div class="admin-table" id="companies-table">
                    <!-- Tableau des entreprises chargé dynamiquement -->
                </div>
                
                <div class="pagination" id="companies-pagination">
                    <!-- Pagination chargée dynamiquement -->
                </div>
            </div>

            <!-- Contenu Offres -->
            <div id="offers" class="tab-content">
                <h2>Gestion des offres d'emploi</h2>

                <div class="admin-table" id="offers-table">
                    <!-- Tableau des offres chargé dynamiquement -->
                </div>
                
                <div class="pagination" id="offers-pagination">
                    <!-- Pagination chargée dynamiquement -->
                </div>
            </div>

            <!-- Contenu Candidatures -->
            <div id="applications" class="tab-content">
                <h2>Gestion des candidatures</h2>

                <div class="admin-table" id="applications-table">
                    <!-- Tableau des candidatures chargé dynamiquement -->
                </div>
                
                <div class="pagination" id="applications-pagination">
                    <!-- Pagination chargée dynamiquement -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal utilisateur -->
    <div id="user-modal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 id="user-modal-title">Nouvel utilisateur</h2>
                <button class="modal-close" onclick="closeModal('user-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="user-form">
                    <input type="hidden" id="user-id" name="user_id">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="user-first-name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="user-last-name" name="last_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" id="user-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="user-phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mot de passe <span id="password-required">*</span></label>
                        <input type="password" class="form-control" id="user-password" name="password">
                        <small class="form-text">Laissez vide pour ne pas modifier le mot de passe</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rôle *</label>
                        <select class="form-control" id="user-role" name="role" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="candidate">Candidat</option>
                            <option value="recruiter">Recruteur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('user-modal')">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">
                    <span class="loading" style="display: none;"></span>
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>

    <!-- Modal entreprise -->
    <div id="company-modal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 id="company-modal-title">Nouvelle entreprise</h2>
                <button class="modal-close" onclick="closeModal('company-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="company-form">
                    <input type="hidden" id="company-id" name="company_id">
                    <div class="form-group">
                        <label class="form-label">Nom de l'entreprise *</label>
                        <input type="text" class="form-control" id="company-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" id="company-email" name="email" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="company-phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Localisation</label>
                            <input type="text" class="form-control" id="company-location" name="location">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site web</label>
                        <input type="url" class="form-control" id="company-website" name="website">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="company-description" name="description" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('company-modal')">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveCompany()">
                    <span class="loading" style="display: none;"></span>
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>

    <!-- Modal offre -->
    <div id="offer-modal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 id="offer-modal-title">Nouvelle offre</h2>
                <button class="modal-close" onclick="closeModal('offer-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="offer-form">
                    <input type="hidden" id="offer-id" name="offer_id">
                    <div class="form-group">
                        <label class="form-label">Titre du poste *</label>
                        <input type="text" class="form-control" id="offer-title" name="title" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Entreprise *</label>
                            <select class="form-control" id="offer-company" name="id_companies" required>
                                <option value="">Sélectionner une entreprise</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Type de contrat *</label>
                            <select class="form-control" id="offer-contract-type" name="contract_type" required>
                                <option value="">Sélectionner</option>
                                <option value="CDI">CDI</option>
                                <option value="CDD">CDD</option>
                                <option value="Stage">Stage</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Alternance">Alternance</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Localisation *</label>
                            <input type="text" class="form-control" id="offer-location" name="location" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Salaire (€)</label>
                            <input type="number" class="form-control" id="offer-salary" name="salary" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de publication</label>
                        <input type="date" class="form-control" id="offer-published-date" name="published_date">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description courte *</label>
                        <textarea class="form-control" id="offer-description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description détaillée</label>
                        <textarea class="form-control" id="offer-long-description" name="long_description" rows="5"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('offer-modal')">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveOffer()">
                    <span class="loading" style="display: none;"></span>
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div id="delete-modal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2>Confirmer la suppression</h2>
                <button class="modal-close" onclick="closeModal('delete-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <p id="delete-message">Êtes-vous sûr de vouloir supprimer cet élément ?</p>
                <p><strong>Cette action est irréversible.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('delete-modal')">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                    <span class="loading" style="display: none;"></span>
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>