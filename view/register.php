<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Créer un compte</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        .register-section {
            padding: 4rem 0;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .register-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2c5282 50%, var(--primary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .register-form {
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

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0.25rem;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        .btn-register {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-register:disabled {
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

        .auth-footer {
            text-align: center;
            padding: 1rem 2rem 2rem;
            color: #666;
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
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

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            background: #e53e3e;
            width: 0%;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .register-section {
                padding: 2rem 0;
            }
            
            .register-header {
                padding: 1.5rem;
            }
            
            .register-form {
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
    
    <section class="register-section">
        <div class="container">
            <div class="register-container">
                <div class="register-header">
                    <h1>Créer un compte</h1>
                    <p>Rejoignez Job Finder et trouvez votre emploi idéal</p>
                </div>
                
                <div class="register-form">
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle"></i>
                        <span id="successMessage">Compte créé avec succès! Redirection en cours...</span>
                    </div>
                    
                    <div class="alert alert-error" id="errorAlert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="errorMessage">Une erreur est survenue</span>
                    </div>
                    
                    <div class="loading-spinner" id="loadingSpinner">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Création du compte en cours...</p>
                    </div>
                    
                    <form id="registerForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Prénom *</label>
                                <input type="text" id="first_name" name="first_name" placeholder="Votre prénom" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Nom *</label>
                                <input type="text" id="last_name" name="last_name" placeholder="Votre nom" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone" placeholder="06 12 34 56 78">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Mot de passe *</label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" placeholder="Minimum 8 caractères" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <span class="strength-text" id="strengthText">Entrez votre mot de passe</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword">Confirmer le mot de passe *</label>
                            <div class="password-input">
                                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                    <i class="fas fa-eye" id="confirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Type de profil</label>
                            <select id="role" name="role">
                                <option value="candidate">Candidat</option>
                                <option value="recruiter">Recruteur</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-register" id="registerBtn">
                            <i class="fas fa-user-plus"></i>
                            Créer mon compte
                        </button>
                    </form>
                </div>
                
                <div class="auth-footer">
                    <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
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
        async function checkUserSession() {
            try {
                const response = await fetch('../api/check-session.php');
                const data = await response.json();
                
                if (data.success && data.is_logged_in) {
                    window.location.href = 'index.php';
                } else {
                    document.getElementById('authButtons').style.display = 'flex';
                    document.getElementById('userMenu').style.display = 'none';
                }
            } catch (error) {
                console.error('Erreur lors de la vérification de session:', error);
            }
        }
        
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            if (data.password !== data.confirmPassword) {
                showError('Les mots de passe ne correspondent pas');
                return;
            }
            
            if (data.password.length < 8) {
                showError('Le mot de passe doit contenir au moins 8 caractères');
                return;
            }
            
            showLoading(true);
            hideAlerts();
            
            try {
                const response = await fetch('../api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess('Compte créé avec succès! Vous pouvez maintenant vous connecter.');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    showError(result.message || 'Erreur lors de la création du compte');
                }
            } catch (error) {
                console.error('Erreur lors de l\'inscription:', error);
                showError('Erreur de connexion au serveur');
            } finally {
                showLoading(false);
            }
        });
        
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + 'ToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let text = 'Très faible';
            let color = '#e53e3e';
            
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            if (strength >= 75) {
                text = 'Fort';
                color = '#38a169';
            } else if (strength >= 50) {
                text = 'Moyen';
                color = '#ed8936';
            } else if (strength >= 25) {
                text = 'Faible';
                color = '#f56565';
            }
            
            strengthFill.style.width = strength + '%';
            strengthFill.style.background = color;
            strengthText.textContent = text;
        });
        
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
        
        function showLoading(show) {
            const spinner = document.getElementById('loadingSpinner');
            const form = document.getElementById('registerForm');
            spinner.style.display = show ? 'block' : 'none';
            form.style.display = show ? 'none' : 'block';
        }
        
        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            const messageSpan = document.getElementById('successMessage');
            messageSpan.textContent = message;
            alert.style.display = 'block';
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
        
        document.addEventListener('DOMContentLoaded', checkUserSession);
    </script>
</body>
</html>

