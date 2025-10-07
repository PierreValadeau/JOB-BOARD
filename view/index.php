<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Trouvez votre emploi idéal</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header header-white">
        <div class="container">
            <div class="nav-wrapper">
                <nav class="nav">
                    <ul class="nav-list">
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <a href="login.php" class="btn-link">Se connecter</a>
                    <a href="register.php" class="btn-primary">Créer un compte</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Trouvez votre emploi idéal</h1>
                <p class="hero-subtitle">Découvrez des milliers d'opportunités professionnelles adaptées à votre profil</p>
                
                <!-- Enhanced Search Form -->
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
                        
                        <!-- Advanced Search Toggle -->
                        <button type="button" class="advanced-toggle" onclick="toggleAdvancedSearch()">
                            <i class="fas fa-sliders-h"></i>
                            Recherche avancée
                        </button>
                    </div>
                    
                    <!-- Advanced Search Panel -->
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

    <!-- Popular Searches -->
    <section class="popular-searches">
        <div class="container">
            <h2>Recherches populaires</h2>
            <div class="search-tags">
                <a href="#" class="tag">Développeur Web</a>
                <a href="#" class="tag">Commercial</a>
                <a href="#" class="tag">Comptable</a>
                <a href="#" class="tag">Infirmier</a>
                <a href="#" class="tag">Chef de projet</a>
                <a href="#" class="tag">Marketing</a>
                <a href="#" class="tag">Ressources Humaines</a>
                <a href="#" class="tag">Télétravail</a>
            </div>
        </div>
    </section>

    <!-- Featured Jobs -->
    <section class="featured-jobs">
        <div class="container">
            <h2>Offres d'emploi en vedette</h2>
            <div class="jobs-grid">
                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title">Développeur Full Stack</h3>
                        <span class="company-name">TechCorp</span>
                    </div>
                    <div class="job-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Paris (75)
                    </div>
                    <div class="job-salary">45 000 € - 60 000 € par an</div>
                    <div class="job-description">
                        Rejoignez notre équipe de développement pour créer des applications web innovantes...
                    </div>
                    <div class="job-footer">
                        <span class="job-type">CDI</span>
                        <span class="job-date">Il y a 2 jours</span>
                    </div>
                </div>

                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title">Chef de Projet Digital</h3>
                        <span class="company-name">DigitalAgency</span>
                    </div>
                    <div class="job-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Lyon (69)
                    </div>
                    <div class="job-salary">50 000 € - 65 000 € par an</div>
                    <div class="job-description">
                        Nous recherchons un chef de projet expérimenté pour piloter nos projets digitaux...
                    </div>
                    <div class="job-footer">
                        <span class="job-type">CDI</span>
                        <span class="job-date">Il y a 1 jour</span>
                    </div>
                </div>

                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title">Commercial B2B</h3>
                        <span class="company-name">SalesPro</span>
                    </div>
                    <div class="job-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Marseille (13)
                    </div>
                    <div class="job-salary">35 000 € - 50 000 € par an + commission</div>
                    <div class="job-description">
                        Opportunité de développer votre carrière commerciale dans un secteur en croissance...
                    </div>
                    <div class="job-footer">
                        <span class="job-type">CDI</span>
                        <span class="job-date">Il y a 3 jours</span>
                    </div>
                </div>
            </div>
            <div class="view-all-jobs">
                <a href="search-results.html" class="btn-secondary">Voir toutes les offres</a>
            </div>
        </div>
    </section>

    <!-- Statistics -->
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

    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <h2>Emplois par secteur</h2>
            <div class="categories-grid">
                <a href="#" class="category-card">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Informatique & Tech</h3>
                    <p>2,450 offres</p>
                </a>
                <a href="#" class="category-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Commercial & Vente</h3>
                    <p>1,890 offres</p>
                </a>
                <a href="#" class="category-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Marketing & Communication</h3>
                    <p>1,234 offres</p>
                </a>
                <a href="#" class="category-card">
                    <i class="fas fa-user-tie"></i>
                    <h3>Management</h3>
                    <p>987 offres</p>
                </a>
                <a href="#" class="category-card">
                    <i class="fas fa-calculator"></i>
                    <h3>Finance & Comptabilité</h3>
                    <p>756 offres</p>
                </a>
                <a href="#" class="category-card">
                    <i class="fas fa-users"></i>
                    <h3>Ressources Humaines</h3>
                    <p>543 offres</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Job Finder</h4>
                    <ul>
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Presse</a></li>
                        <li><a href="#">Carrières</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Candidats</h4>
                    <ul>
                        <li><a href="#">Parcourir les emplois</a></li>
                        <li><a href="#">Guide des salaires</a></li>
                        <li><a href="#">Conseils carrière</a></li>
                        <li><a href="#">Créer un CV</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Employeurs</h4>
                    <ul>
                        <li><a href="#">Publier une offre</a></li>
                        <li><a href="#">Solutions RH</a></li>
                        <li><a href="#">Tarifs</a></li>
                        <li><a href="#">Aide employeurs</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Suivez-nous</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Job Finder. Tous droits réservés.</p>
                <div class="footer-links">
                    <a href="#">Conditions d'utilisation</a>
                    <a href="#">Politique de confidentialité</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
</body>
</html>