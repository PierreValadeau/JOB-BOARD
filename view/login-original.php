<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Job Finder</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="home-btn"><i class="fas fa-home"></i> Accueil</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <a href="login.php" class="btn-link active">Se connecter</a>
                    <a href="register.php" class="btn-primary">Créer un compte</a>
                </div>
            </div>
        </div>
    </header>
    
    <main class="auth-main">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1>Connexion</h1>
                        <p>Accédez à votre espace personnel Job Finder</p>
                    </div>
                    <form class="auth-form" id="loginForm">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Adresse email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="votre.email@exemple.com"
                                required
                            >
                            <div class="error-message" id="emailError"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i>
                                Mot de passe
                            </label>
                            <div class="password-input">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Votre mot de passe"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>
                        <div class="form-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember" id="remember">
                                <span class="checkmark"></span>
                                Se souvenir de moi
                            </label>
                            <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                        </div>
                        <button type="submit" class="auth-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            Se connecter
                        </button>
                        <div class="auth-divider">
                            <span>ou</span>
                        </div>
                        <div class="social-login">
                            <button type="button" class="social-btn google-btn">
                                <i class="fab fa-google"></i>
                                Continuer avec Google
                            </button>
                            <button type="button" class="social-btn linkedin-btn">
                                <i class="fab fa-linkedin"></i>
                                Continuer avec LinkedIn
                            </button>
                        </div>
                        <div class="auth-success" id="loginSuccess">
                            <i class="fas fa-check-circle"></i>
                            <p>Connexion réussie ! Redirection en cours...</p>
                        </div>
                        <div class="auth-error" id="loginError">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p id="loginErrorMessage">Une erreur est survenue. Veuillez réessayer.</p>
                        </div>
                    </form>
                    <div class="auth-footer">
                        <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
                    </div>
                </div>
                
                <div class="info-panel">
                    <div class="info-content">
                        <h2>Pourquoi se connecter ?</h2>
                        <ul class="benefits-list">
                            <li>
                                <i class="fas fa-bookmark"></i>
                                <div>
                                    <strong>Sauvegardez vos offres</strong>
                                    <p>Gardez vos annonces préférées pour postuler plus tard</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-bell"></i>
                                <div>
                                    <strong>Alertes personnalisées</strong>
                                    <p>Recevez des notifications pour les emplois qui vous intéressent</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-chart-line"></i>
                                <div>
                                    <strong>Suivi de candidatures</strong>
                                    <p>Gérez facilement toutes vos candidatures en un seul endroit</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-user-tie"></i>
                                <div>
                                    <strong>Profil professionnel</strong>
                                    <p>Créez un profil détaillé pour attirer les recruteurs</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Job Finder</h4>
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
    <script src="../assets/js/auth.js"></script>
</body>
</html>