<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Job Finder</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Améliorations des boutons */
        .auth-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 14px 28px !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            color: white !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }
        
        .auth-btn:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6) !important;
            background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%) !important;
        }
        
        .auth-btn:active {
            transform: translateY(-1px) !important;
        }
        
        .social-btn {
            border-radius: 10px !important;
            padding: 12px 20px !important;
            border: 2px solid #e2e8f0 !important;
            background: white !important;
            color: #4a5568 !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
        }
        
        .social-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            border-color: #cbd5e0 !important;
        }
        
        .google-btn:hover {
            border-color: #db4437 !important;
            color: #db4437 !important;
        }
        
        .linkedin-btn:hover {
            border-color: #0077b5 !important;
            color: #0077b5 !important;
        }
        
        /* Amélioration des champs de saisie */
        .form-group input, .form-group select {
            border-radius: 10px !important;
            border: 2px solid #e2e8f0 !important;
            padding: 12px 16px !important;
            transition: all 0.3s ease !important;
        }
        
        .form-group input:focus, .form-group select:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
            outline: none !important;
        }
        
        /* Amélioration de la carte auth */
        .auth-card {
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>
<body>
    
    <header class="header header-white">
        <div class="container">
            <div class="nav-wrapper" style="justify-content: center;">
                <div class="logo">
                    <a href="index.php" class="logo-link">
                        <h1 class="logo-text">Job Finder</h1>
                    </a>
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

                        <button type="submit" class="auth-btn">
                            <i class="fas fa-user-plus"></i>
                            Créer mon compte
                        </button>
                    </form>
                    <div class="auth-footer">
                        <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="../assets/js/auth.js"></script>
</body>
</html>

