<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Modifier mon profil</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Style pour le menu utilisateur */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .welcome-text {
            color: #333;
            font-weight: 500;
        }
        .user-menu .btn-link {
            color: #666;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .user-menu .btn-link:hover {
            background-color: #f5f5f5;
            color: #333;
        }
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Styles spécifiques pour la page de profil */
        .profile-section {
            padding: 4rem 0;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2c5282 50%, var(--primary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .profile-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .profile-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .profile-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 87, 167, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-update {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-update:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-update:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: none;
        }

        .alert-success {
            background: #f0fff4;
            border: 1px solid #9ae6b4;
            color: #276749;
        }

        .alert-error {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #c53030;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 1rem;
        }

        .loading-spinner i {
            font-size: 1.5rem;
            color: var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                padding: 1.5rem;
            }
            
            .profile-form {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    
    <header class="header header-white">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="index.php" class="logo-link">
                        <h1 class="logo-text">Job Finder</h1>
                    </a>
                </div>

                <div class="auth-buttons" id="authButtons">
                    <a href="login.php" class="btn-link">Se connecter</a>
                    <a href="register.php" class="btn-primary">Créer un compte</a>
                </div>
                
                <!-- User menu (hidden by default, shown when logged in) -->
                <div class="user-menu" id="userMenu" style="display: none;">
                    <span class="welcome-text">Bonjour, <span id="userName"></span></span>
                    <a href="profile-edit.php" class="btn-link">Mon profil</a>
                    <a href="#" class="btn-link" onclick="logout()">Déconnexion</a>
                </div>
            </div>
        </div>
    </header>
    
    <section class="profile-section">
        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <h1>Modifier mon profil</h1>
                    <p>Mettez à jour vos informations personnelles</p>
                </div>
                
                <div class="profile-form">
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle"></i>
                        <span id="successMessage">Profil mis à jour avec succès!</span>
                    </div>
                    
                    <div class="alert alert-error" id="errorAlert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="errorMessage">Une erreur est survenue</span>
                    </div>
                    
                    <div class="loading-spinner" id="loadingSpinner">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Chargement...</p>
                    </div>
                    
                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Prénom *</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Nom *</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Type de profil</label>
                            <select id="role" name="role">
                                <option value="candidate">Candidat</option>
                                <option value="recruiter">Recruteur</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-update" id="updateBtn">
                            <i class="fas fa-save"></i>
                            Mettre à jour mon profil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Job Finder</h4>
                </div>
                <div class="footer-section">
                    <h4>Candidats</h4>
                </div>
                <div class="footer-section">
                    <h4>Employeurs</h4>
                </div>
                <div class="footer-section">
                    <h4>Suivez-nous</h4>
                    <div class="social-links">
                        <span class="social-link"><i class="fab fa-facebook"></i></span>
                        <span class="social-link"><i class="fab fa-twitter"></i></span>
                        <span class="social-link"><i class="fab fa-linkedin"></i></span>
                        <span class="social-link"><i class="fab fa-instagram"></i></span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Job Finder. Tous droits réservés.</p>
                <div class="footer-links">
                    <span class="footer-link">Mentions légales</span>
                    <span class="footer-link">Politique de confidentialité</span>
                    <span class="footer-link">Contact</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        let currentUser = null;
        
        // Vérifier si l'utilisateur est connecté
        async function checkUserSession() {
            try {
                const response = await fetch('../api/check-session.php');
                const data = await response.json();
                
                if (data.success && data.is_logged_in) {
                    // Utilisateur connecté
                    document.getElementById('authButtons').style.display = 'none';
                    document.getElementById('userMenu').style.display = 'flex';
                    document.getElementById('userName').textContent = data.user.name;
                    
                    currentUser = data.user;
                    loadUserProfile();
                } else {
                    // Utilisateur non connecté - rediriger vers login
                    window.location.href = 'login.php';
                }
            } catch (error) {
                console.error('Erreur lors de la vérification de session:', error);
                window.location.href = 'login.php';
            }
        }
        
        // Charger les données du profil utilisateur
        async function loadUserProfile() {
            if (!currentUser) return;
            
            showLoading(true);
            
            try {
                const response = await fetch(`../api/user.php?id=${currentUser.user_id}`);
                const data = await response.json();
                
                if (data.success && data.user) {
                    // Préremplir le formulaire
                    document.getElementById('first_name').value = data.user.first_name || '';
                    document.getElementById('last_name').value = data.user.last_name || '';
                    document.getElementById('email').value = data.user.email || '';
                    document.getElementById('phone').value = data.user.phone || '';
                    document.getElementById('role').value = data.user.role || 'candidate';
                } else {
                    showError('Impossible de charger les données du profil');
                }
            } catch (error) {
                console.error('Erreur lors du chargement du profil:', error);
                showError('Erreur lors du chargement du profil');
            } finally {
                showLoading(false);
            }
        }
        
        // Soumettre le formulaire de mise à jour
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentUser) return;
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            showLoading(true);
            hideAlerts();
            
            try {
                const response = await fetch(`../api/user.php?id=${currentUser.user_id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess('Profil mis à jour avec succès!');
                    // Mettre à jour le nom dans la navbar si il a changé
                    if (data.first_name) {
                        document.getElementById('userName').textContent = data.first_name + ' ' + (data.last_name || '');
                    }
                } else {
                    showError(result.message || 'Erreur lors de la mise à jour');
                }
            } catch (error) {
                console.error('Erreur lors de la mise à jour:', error);
                showError('Erreur de connexion au serveur');
            } finally {
                showLoading(false);
            }
        });
        
        // Fonction de déconnexion
        async function logout() {
            try {
                const response = await fetch('../api/logout.php', { method: 'POST' });
                if (response.ok) {
                    window.location.href = 'index.php';
                }
            } catch (error) {
                console.error('Erreur lors de la déconnexion:', error);
                window.location.href = 'index.php';
            }
        }
        
        // Fonctions utilitaires pour l'interface
        function showLoading(show) {
            const spinner = document.getElementById('loadingSpinner');
            const form = document.getElementById('profileForm');
            spinner.style.display = show ? 'block' : 'none';
            form.style.display = show ? 'none' : 'block';
        }
        
        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            const messageSpan = document.getElementById('successMessage');
            messageSpan.textContent = message;
            alert.style.display = 'block';
            
            // Masquer après 5 secondes
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }
        
        function showError(message) {
            const alert = document.getElementById('errorAlert');
            const messageSpan = document.getElementById('errorMessage');
            messageSpan.textContent = message;
            alert.style.display = 'block';
        }
        
        function hideAlerts() {
            document.getElementById('successAlert').style.display = 'none';
            document.getElementById('errorAlert').style.display = 'none';
        }
        
        // Vérifier la session au chargement de la page
        document.addEventListener('DOMContentLoaded', checkUserSession);
    </script>
</body>
</html>