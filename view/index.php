<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder - Trouvez votre emploi idéal</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/index.css">
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

                <div class="auth-buttons">
                    <a href="login.php" class="btn-link">Se connecter</a>
                    <a href="register.php" class="btn-primary">Créer un compte</a>
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
            <div class="jobs-grid">
                <div class="job-card" 
                     data-full-description="Rejoignez notre équipe de développement dynamique pour créer des applications web innovantes utilisant les dernières technologies. Vous travaillerez sur des projets variés incluant le développement d'APIs REST, l'intégration de bases de données, et la création d'interfaces utilisateur modernes. Nous recherchons une personne passionnée par le code propre, les bonnes pratiques et l'innovation technologique."
                     data-full-salary="45 000 € - 60 000 € par an (selon expérience) + primes de performance + participation aux bénéfices + tickets restaurant"
                     data-working-time="39h/semaine - Horaires flexibles de 8h à 19h - Télétravail hybride 2-3 jours/semaine"
                     data-precise-location="Paris 11ème arrondissement - Métro République (lignes 3, 5, 8, 9, 11) - Bureaux modernes avec terrasse"
                     data-company-info="TechCorp - Startup en forte croissance (150 employés) spécialisée dans les solutions SaaS pour entreprises"
                     data-benefits="Mutuelle 100% prise en charge, CSE, formation continue, MacBook Pro fourni, café/snacks illimités">
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
                        Rejoignez notre équipe de développement dynamique pour créer des applications web innovantes
                    </div>
                    <div class="job-description-more" style="display: none;"></div>
                    <div class="job-footer">
                        <span class="job-type">CDI</span>
                        <span class="job-date">Il y a 2 jours</span>
                        <button type="button" class="btn-learn-more">En savoir plus</button>
                    </div>
                </div>
                <div class="job-card"
                     data-full-description="Nous recherchons un chef de projet digital expérimenté pour piloter nos projets clients de A à Z. Vous serez responsable de la planification, du suivi et de la livraison de projets web et mobile complexes. Votre mission inclut la coordination des équipes techniques, la gestion des budgets, et la relation client. Vous maîtrisez les méthodologies agiles et avez une excellente capacité de communication."
                     data-full-salary="50 000 € - 65 000 € par an (selon expérience) + variable sur objectifs + intéressement + chèques vacances"
                     data-working-time="37h/semaine - Horaires de bureau 9h-17h30 - Télétravail possible 1-2 jours/semaine"
                     data-precise-location="Lyon Part-Dieu - Proche gare SNCF et métro - Parking entreprise disponible"
                     data-company-info="DigitalAgency - Agence digitale reconnue (80 employés) avec des clients grands comptes dans toute la France"
                     data-benefits="Mutuelle famille, 6 semaines de congés, formation certifiante, prime transport, événements équipe">
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
                        Nous recherchons un chef de projet digital expérimenté pour piloter nos projets clients de A à Z
                    </div>
                    <div class="job-description-more" style="display: none;"></div>
                    <div class="job-footer">
                        <span class="job-type">CDI</span>
                        <span class="job-date">Il y a 1 jour</span>
                        <button type="button" class="btn-learn-more">En savoir plus</button>
                    </div>
                </div>
                <div class="job-card"
                     data-full-description="Opportunité exceptionnelle de développer votre carrière commerciale dans le secteur en pleine croissance des solutions logicielles B2B. Vous serez responsable du développement commercial sur la région PACA, de la prospection à la signature de contrats. Votre mission : identifier de nouveaux clients, présenter nos solutions, négocier et fidéliser un portefeuille clients."
                     data-full-salary="35 000 € - 50 000 € fixe + commission sans plafond (moyenne 15k€/an) + prime d'objectifs trimestrielle + voiture de fonction"
                     data-working-time="39h/semaine - Horaires flexibles - Déplacements régionaux 2-3 jours/semaine - Base bureau Marseille"
                     data-precise-location="Marseille 8ème - Quartier Prado - Accès métro ligne 2 - Parking gratuit pour commerciaux"
                     data-company-info="SalesPro - Leader français des solutions CRM (200 employés) avec 15 ans d'expérience et croissance de 25%/an"
                     data-benefits="Véhicule + essence, téléphone, laptop, mutuelle premium, RTT, séminaires incentive, plan épargne entreprise">
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
                        Opportunité exceptionnelle de développer votre carrière commerciale dans le secteur en pleine croissance des solutions logicielles B2B
                    </div>
                    <div class="job-description-more" style="display: none;"></div>
                    <div class="job-footer">
                        <span class="job-type">CDI</span>
                        <span class="job-date">Il y a 3 jours</span>
                        <button type="button" class="btn-learn-more">En savoir plus</button>
                    </div>
                </div>
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
        // Fonction pour la recherche avancée
        function toggleAdvancedSearch() {
            const advancedSearch = document.getElementById('advancedSearch');
            if (advancedSearch) {
                advancedSearch.style.display = advancedSearch.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>


