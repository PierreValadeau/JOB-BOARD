<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Job Finder</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/register.css">
    <link href="https:
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
                    <a href="login.php" class="btn-link">Se connecter</a>
                    <a href="register.php" class="btn-primary active">Créer un compte</a>
                </div>
            </div>
        </div>
    </header>
    
    <main class="auth-main">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1>Créer un compte</h1>
                        <p>Rejoignez Job Finder et trouvez votre emploi idéal</p>
                    </div>
                    <form class="auth-form" id="registerForm">
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="firstName">
                                    <i class="fas fa-user"></i>
                                    Prénom
                                </label>
                                <input 
                                    type="text" 
                                    id="firstName" 
                                    name="firstName" 
                                    placeholder="Votre prénom"
                                    required
                                >
                                <div class="error-message" id="firstNameError"></div>
                            </div>
                            <div class="form-group half">
                                <label for="lastName">
                                    <i class="fas fa-user"></i>
                                    Nom
                                </label>
                                <input 
                                    type="text" 
                                    id="lastName" 
                                    name="lastName" 
                                    placeholder="Votre nom"
                                    required
                                >
                                <div class="error-message" id="lastNameError"></div>
                            </div>
                        </div>
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
                            <label for="phone">
                                <i class="fas fa-phone"></i>
                                Téléphone (optionnel)
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                placeholder="06 12 34 56 78"
                            >
                            <div class="error-message" id="phoneError"></div>
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
                                    placeholder="Minimum 8 caractères"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar">
                                    <div class="strength-fill"></div>
                                </div>
                                <span class="strength-text">Entrez votre mot de passe</span>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">
                                <i class="fas fa-lock"></i>
                                Confirmer le mot de passe
                            </label>
                            <div class="password-input">
                                <input 
                                    type="password" 
                                    id="confirmPassword" 
                                    name="confirmPassword" 
                                    placeholder="Confirmez votre mot de passe"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="error-message" id="confirmPasswordError"></div>
                        </div>
                        <div class="form-group">
                            <label for="userType">
                                <i class="fas fa-briefcase"></i>
                                Je suis
                            </label>
                            <select id="userType" name="userType" required>
                                <option value="">Sélectionnez votre profil</option>
                                <option value="candidate">Candidat en recherche d'emploi</option>
                                <option value="employed">Salarié en veille</option>
                                <option value="student">Étudiant</option>
                                <option value="recruiter">Recruteur</option>
                            </select>
                            <div class="error-message" id="userTypeError"></div>
                        </div>
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="terms" id="terms" required>
                                <span class="checkmark"></span>
                                J'accepte les <a href="
                            </label>
                            <div class="error-message" id="termsError"></div>
                        </div>
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="newsletter" id="newsletter">
                                <span class="checkmark"></span>
                                Je souhaite recevoir des alertes emploi et actualités par email
                            </label>
                        </div>
                        <button type="submit" class="auth-btn">
                            <i class="fas fa-user-plus"></i>
                            Créer mon compte
                        </button>
                        <div class="auth-divider">
                            <span>ou</span>
                        </div>
                        <div class="social-login">
                            <button type="button" class="social-btn google-btn">
                                <i class="fab fa-google"></i>
                                S'inscrire avec Google
                            </button>
                            <button type="button" class="social-btn linkedin-btn">
                                <i class="fab fa-linkedin"></i>
                                S'inscrire avec LinkedIn
                            </button>
                        </div>
                    </form>
                    <div class="auth-footer">
                        <p>Déjà un compte ? <a href="login.html">Se connecter</a></p>
                    </div>
                </div>
                
                <div class="info-panel">
                    <div class="info-content">
                        <h2>Rejoignez Job Finder</h2>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">15k+</div>
                                <div class="stat-label">Offres d'emploi</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">5k+</div>
                                <div class="stat-label">Entreprises</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50k+</div>
                                <div class="stat-label">Candidats</div>
                            </div>
                        </div>
                        <ul class="benefits-list">
                            <li>
                                <i class="fas fa-rocket"></i>
                                <div>
                                    <strong>Candidature rapide</strong>
                                    <p>Postulez en 1 clic avec votre profil pré-rempli</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-target"></i>
                                <div>
                                    <strong>Offres personnalisées</strong>
                                    <p>Algorithme intelligent selon vos préférences</p>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <strong>100% gratuit</strong>
                                    <p>Toutes nos fonctionnalités sont gratuites</p>
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
                <p>&copy; 2025 Job Finder. Tous droits réservés.</p>
                <div class="footer-links">
                    <a href="
                    <a href="
                </div>
            </div>
        </div>
    </footer>
    <script src="../assets/js/auth.js"></script>
</body>
</html>

