<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Connexion</title>
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
        .auth-section {
            padding: 4rem 0;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .auth-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2c5282 50%, var(--primary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .auth-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .auth-form {
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

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 87, 167, 0.1);
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

        .form-options {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-login {
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

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-login:disabled {
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

        @media (max-width: 768px) {
            .auth-section {
                padding: 2rem 0;
            }
            
            .auth-header {
                padding: 1.5rem;
            }
            
            .auth-form {
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
    
    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-header">
                    <h1>Connexion</h1>
                    <p>Accédez à votre espace personnel Job Finder</p>
                </div>
                
                <div class="auth-form">
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle"></i>
                        <span id="successMessage">Connexion réussie! Redirection en cours...</span>
                    </div>
                    
                    <div class="alert alert-error" id="errorAlert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="errorMessage">Email ou mot de passe incorrect</span>
                    </div>
                    
                    <div class="loading-spinner" id="loadingSpinner">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Connexion en cours...</p>
                    </div>
                    
                    <form id="loginForm">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="votre.email@exemple.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                                <button type="button" class="toggle-password" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                        </div>
                        
                        <button type="submit" class="btn-login" id="loginBtn">
                            <i class="fas fa-sign-in-alt"></i>
                            Se connecter
                        </button>
                    </form>
                </div>
                
                <div class="auth-footer">
                    <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
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
        
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };
            
            showLoading(true);
            hideAlerts();
            
            try {
                const response = await fetch('../api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess('Connexion réussie! Redirection en cours...');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1500);
                } else {
                    showError(result.message || 'Email ou mot de passe incorrect');
                }
            } catch (error) {
                console.error('Erreur lors de la connexion:', error);
                showError('Erreur de connexion au serveur');
            } finally {
                showLoading(false);
            }
        });
        
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
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
            const form = document.getElementById('loginForm');
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