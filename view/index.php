<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Trouvez votre emploi idéal</title>
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
    
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Trouvez votre emploi idéal</h1>
                <p class="hero-subtitle">Découvrez des milliers d'opportunités professionnelles adaptées à votre profil</p>
                
                <form class="enhanced-search-form" action="job-ads.html" method="GET">
                    <div class="search-wrapper">
                        <div class="search-container">
                            <div class="search-field">
                                <div class="field-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" name="q" placeholder="Poste, entreprise ou mots-clés..." class="search-input" autocomplete="off">
                                <div class="search-suggestions" id="jobSuggestions"></div>
                            </div>
                            <div class="location-field">
                                <div class="field-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <input type="text" name="location" placeholder="Ville ou région..." class="location-input" autocomplete="off">
                                <div class="search-suggestions" id="locationSuggestions"></div>
                            </div>
                            <button type="submit" class="enhanced-search-btn">
                                <i class="fas fa-search"></i>
                                <span>Rechercher</span>
                            </button>
                        </div>
                        
                        <button type="button" class="advanced-toggle" onclick="toggleAdvancedSearch()">
                            <i class="fas fa-sliders-h"></i>
                            Recherche avancée
                        </button>
                    </div>
                    
                    <div class="advanced-search" id="advancedSearch">
                        <div class="advanced-grid">
                            <div class="advanced-field">
                                <label>Type de contrat</label>
                                <select name="contract">
                                    <option value="">Tous les contrats</option>
                                    <option value="cdi">CDI</option>
                                    <option value="cdd">CDD</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="stage">Stage</option>
                                </select>
                            </div>
                            <div class="advanced-field">
                                <label>Salaire minimum</label>
                                <select name="salary">
                                    <option value="">Non spécifié</option>
                                    <option value="25000">25 000 € et plus</option>
                                    <option value="35000">35 000 € et plus</option>
                                    <option value="45000">45 000 € et plus</option>
                                    <option value="60000">60 000 € et plus</option>
                                </select>
                            </div>
                            <div class="advanced-field">
                                <label>Télétravail</label>
                                <select name="remote">
                                    <option value="">Peu importe</option>
                                    <option value="full">Télétravail complet</option>
                                    <option value="hybrid">Télétravail hybride</option>
                                    <option value="none">Présentiel</option>
                                </select>
                            </div>
                            <div class="advanced-field">
                                <label>Expérience</label>
                                <select name="experience">
                                    <option value="">Tous niveaux</option>
                                    <option value="junior">0-2 ans</option>
                                    <option value="mid">3-5 ans</option>
                                    <option value="senior">5+ ans</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    

    
    <section class="featured-jobs">
        <div class="container">
            <h2>Offres d'emploi en vedette</h2>
            <div class="jobs-grid" id="featuredJobsGrid">
                <!-- Les offres seront chargées dynamiquement via l'API -->
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Chargement des offres...</p>
                </div>
            </div>
            <div class="view-all-jobs">
                <a href="search-results.html" class="btn-secondary">Voir toutes les offres</a>
            </div>
        </div>
    </section>
    
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">15 000+</div>
                    <div class="stat-label">Offres d'emploi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5 000+</div>
                    <div class="stat-label">Entreprises partenaires</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50 000+</div>
                    <div class="stat-label">Candidats inscrits</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1 200+</div>
                    <div class="stat-label">Recrutements réussis</div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="categories">
        <div class="container">
            <h2>Emplois par secteur</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Informatique & Tech</h3>
                    <p>2,450 offres</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Commercial & Vente</h3>
                    <p>1,890 offres</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Marketing & Communication</h3>
                    <p>1,234 offres</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-user-tie"></i>
                    <h3>Management</h3>
                    <p>987 offres</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-calculator"></i>
                    <h3>Finance & Comptabilité</h3>
                    <p>756 offres</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-users"></i>
                    <h3>Ressources Humaines</h3>
                    <p>543 offres</p>
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
    <script src="../assets/js/job-cards-simple.js"></script>
    <script>
        function toggleAdvancedSearch() {
            const advancedSearch = document.getElementById('advancedSearch');
            if (advancedSearch) {
                advancedSearch.style.display = advancedSearch.style.display === 'none' ? 'block' : 'none';
            }
        }
        
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
                } else {
                    // Utilisateur non connecté
                    document.getElementById('authButtons').style.display = 'flex';
                    document.getElementById('userMenu').style.display = 'none';
                }
            } catch (error) {
                console.error('Erreur lors de la vérification de session:', error);
            }
        }
        
        // Fonction de déconnexion
        async function logout() {
            try {
                const response = await fetch('../api/logout.php', { method: 'POST' });
                if (response.ok) {
                    window.location.reload(); // Recharger la page
                }
            } catch (error) {
                console.error('Erreur lors de la déconnexion:', error);
                window.location.reload();
            }
        }
        
        // Vérifier la session au chargement de la page
        document.addEventListener('DOMContentLoaded', checkUserSession);
    </script>
    <script src="../assets/js/featured-jobs.js"></script>
    <script src="../assets/js/application-manager.js"></script>
</body>
</html>


