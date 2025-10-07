<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - Job Finder</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="home-btn"><i class="fas fa-home"></i> Accueil</a></li>
                    </ul>
                </nav>
                <div class="auth-buttons">
                    <a href="login.php" class="btn-link">Se connecter</a>
                    <a href="register.php" class="btn-primary">Créer un compte</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Bar -->
    <section class="search-bar-section">
        <div class="container">
            <form class="search-form compact" action="search-results.html" method="GET">
                <div class="search-container">
                    <div class="search-field">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" placeholder="Intitulé de poste, mots-clés ou nom d'entreprise" class="search-input" value="Développeur">
                    </div>
                    <div class="location-field">
                        <i class="fas fa-map-marker-alt location-icon"></i>
                        <input type="text" name="location" placeholder="Ville, département, région ou code postal" class="location-input" value="Paris">
                    </div>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Results Section -->
    <section class="results-section">
        <div class="container">
            <div class="results-layout">
                <!-- Sidebar Filters -->
                <aside class="filters-sidebar">
                    <div class="filters-header">
                        <h3>Affiner votre recherche</h3>
                        <button class="clear-filters">Effacer tout</button>
                    </div>

                    <!-- Date Posted Filter -->
                    <div class="filter-group">
                        <h4>Date de publication</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="radio" name="datePosted" value="1">
                                <span class="checkmark"></span>
                                Dernières 24 heures
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="datePosted" value="3">
                                <span class="checkmark"></span>
                                3 derniers jours
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="datePosted" value="7">
                                <span class="checkmark"></span>
                                7 derniers jours
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="datePosted" value="14">
                                <span class="checkmark"></span>
                                14 derniers jours
                            </label>
                        </div>
                    </div>

                    <!-- Job Type Filter -->
                    <div class="filter-group">
                        <h4>Type de contrat</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="jobType" value="cdi">
                                <span class="checkmark"></span>
                                CDI
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="jobType" value="cdd">
                                <span class="checkmark"></span>
                                CDD
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="jobType" value="freelance">
                                <span class="checkmark"></span>
                                Freelance
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="jobType" value="stage">
                                <span class="checkmark"></span>
                                Stage
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="jobType" value="alternance">
                                <span class="checkmark"></span>
                                Alternance
                            </label>
                        </div>
                    </div>

                    <!-- Salary Range Filter -->
                    <div class="filter-group">
                        <h4>Salaire annuel</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="salary" value="25000">
                                <span class="checkmark"></span>
                                25 000 € et plus
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="salary" value="35000">
                                <span class="checkmark"></span>
                                35 000 € et plus
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="salary" value="45000">
                                <span class="checkmark"></span>
                                45 000 € et plus
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="salary" value="60000">
                                <span class="checkmark"></span>
                                60 000 € et plus
                            </label>
                        </div>
                    </div>

                    <!-- Company Filter -->
                    <div class="filter-group">
                        <h4>Entreprise</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="company" value="techcorp">
                                <span class="checkmark"></span>
                                TechCorp (12)
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="company" value="digitalagency">
                                <span class="checkmark"></span>
                                DigitalAgency (8)
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="company" value="salespro">
                                <span class="checkmark"></span>
                                SalesPro (6)
                            </label>
                        </div>
                    </div>

                    <!-- Remote Work Filter -->
                    <div class="filter-group">
                        <h4>Télétravail</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="remote" value="full">
                                <span class="checkmark"></span>
                                Télétravail complet
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="remote" value="hybrid">
                                <span class="checkmark"></span>
                                Télétravail hybride
                            </label>
                        </div>
                    </div>
                </aside>

                <!-- Main Results -->
                <main class="results-main">
                    <!-- Results Header -->
                    <div class="results-header">
                        <div class="results-info">
                            <h1>Emplois Développeur à Paris</h1>
                            <p class="results-count">1 247 offres d'emploi trouvées</p>
                        </div>
                        <div class="sort-options">
                            <label for="sortBy">Trier par :</label>
                            <select id="sortBy" name="sortBy">
                                <option value="relevance">Pertinence</option>
                                <option value="date">Date</option>
                                <option value="salary">Salaire</option>
                                <option value="company">Entreprise</option>
                            </select>
                        </div>
                    </div>

                    <!-- Job Listings -->
                    <div class="job-listings">
                        <!-- Job Item 1 -->
                        <div class="job-item" onclick="window.location.href='job-detail.html?id=1'">
                            <div class="job-main-content">
                                <div class="job-header">
                                    <h3 class="job-title">Développeur Full Stack Senior</h3>
                                    <div class="job-actions">
                                        <button class="save-job" onclick="event.stopPropagation();">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="company-info">
                                    <span class="company-name">TechCorp</span>
                                    <div class="company-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="rating-score">4.2</span>
                                        <span class="reviews-count">(124 avis)</span>
                                    </div>
                                </div>
                                <div class="job-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Paris 8ème (75008)
                                </div>
                                <div class="job-salary">55 000 € - 70 000 € par an</div>
                                <div class="job-summary">
                                    Rejoignez notre équipe de développement pour créer des applications web innovantes. Vous travaillerez sur des projets variés en utilisant React, Node.js et MongoDB...
                                </div>
                                <div class="job-tags">
                                    <span class="tag">React</span>
                                    <span class="tag">Node.js</span>
                                    <span class="tag">MongoDB</span>
                                    <span class="tag">Télétravail hybride</span>
                                </div>
                            </div>
                            <div class="job-meta">
                                <div class="job-type">CDI</div>
                                <div class="posted-date">Il y a 2 jours</div>
                                <div class="urgency urgent">Urgent</div>
                            </div>
                        </div>

                        <!-- Job Item 2 -->
                        <div class="job-item" onclick="window.location.href='job-detail.html?id=2'">
                            <div class="job-main-content">
                                <div class="job-header">
                                    <h3 class="job-title">Développeur Front-End React</h3>
                                    <div class="job-actions">
                                        <button class="save-job" onclick="event.stopPropagation();">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="company-info">
                                    <span class="company-name">DigitalAgency</span>
                                    <div class="company-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="rating-score">4.8</span>
                                        <span class="reviews-count">(89 avis)</span>
                                    </div>
                                </div>
                                <div class="job-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Paris 2ème (75002)
                                </div>
                                <div class="job-salary">45 000 € - 55 000 € par an</div>
                                <div class="job-summary">
                                    Vous développerez des interfaces utilisateur modernes et responsives pour nos clients. Expérience avec React, TypeScript et les outils de build modernes requise...
                                </div>
                                <div class="job-tags">
                                    <span class="tag">React</span>
                                    <span class="tag">TypeScript</span>
                                    <span class="tag">Sass</span>
                                    <span class="tag">Webpack</span>
                                </div>
                            </div>
                            <div class="job-meta">
                                <div class="job-type">CDI</div>
                                <div class="posted-date">Il y a 1 jour</div>
                            </div>
                        </div>

                        <!-- Job Item 3 -->
                        <div class="job-item" onclick="window.location.href='job-detail.html?id=3'">
                            <div class="job-main-content">
                                <div class="job-header">
                                    <h3 class="job-title">Développeur Python/Django</h3>
                                    <div class="job-actions">
                                        <button class="save-job" onclick="event.stopPropagation();">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="company-info">
                                    <span class="company-name">DataTech Solutions</span>
                                    <div class="company-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="rating-score">4.1</span>
                                        <span class="reviews-count">(67 avis)</span>
                                    </div>
                                </div>
                                <div class="job-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Paris 11ème (75011)
                                </div>
                                <div class="job-salary">50 000 € - 65 000 € par an</div>
                                <div class="job-summary">
                                    Développement d'applications backend robustes avec Python/Django. Vous travaillerez sur des projets de traitement de données à grande échelle...
                                </div>
                                <div class="job-tags">
                                    <span class="tag">Python</span>
                                    <span class="tag">Django</span>
                                    <span class="tag">PostgreSQL</span>
                                    <span class="tag">Docker</span>
                                </div>
                            </div>
                            <div class="job-meta">
                                <div class="job-type">CDI</div>
                                <div class="posted-date">Il y a 3 jours</div>
                                <div class="urgency new">Nouveau</div>
                            </div>
                        </div>

                        <!-- Job Item 4 -->
                        <div class="job-item" onclick="window.location.href='job-detail.html?id=4'">
                            <div class="job-main-content">
                                <div class="job-header">
                                    <h3 class="job-title">Développeur Mobile Flutter</h3>
                                    <div class="job-actions">
                                        <button class="save-job" onclick="event.stopPropagation();">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="company-info">
                                    <span class="company-name">MobileTech</span>
                                    <div class="company-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="rating-score">4.6</span>
                                        <span class="reviews-count">(45 avis)</span>
                                    </div>
                                </div>
                                <div class="job-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Paris 15ème (75015)
                                </div>
                                <div class="job-salary">48 000 € - 62 000 € par an</div>
                                <div class="job-summary">
                                    Créez des applications mobiles cross-platform avec Flutter. Rejoignez une équipe dynamique et travaillez sur des projets innovants...
                                </div>
                                <div class="job-tags">
                                    <span class="tag">Flutter</span>
                                    <span class="tag">Dart</span>
                                    <span class="tag">Firebase</span>
                                    <span class="tag">API REST</span>
                                </div>
                            </div>
                            <div class="job-meta">
                                <div class="job-type">CDI</div>
                                <div class="posted-date">Il y a 4 jours</div>
                            </div>
                        </div>

                        <!-- Job Item 5 -->
                        <div class="job-item" onclick="window.location.href='job-detail.html?id=5'">
                            <div class="job-main-content">
                                <div class="job-header">
                                    <h3 class="job-title">Développeur DevOps</h3>
                                    <div class="job-actions">
                                        <button class="save-job saved" onclick="event.stopPropagation();">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="company-info">
                                    <span class="company-name">CloudSystems</span>
                                    <div class="company-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="rating-score">4.3</span>
                                        <span class="reviews-count">(156 avis)</span>
                                    </div>
                                </div>
                                <div class="job-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Paris 9ème (75009)
                                </div>
                                <div class="job-salary">60 000 € - 80 000 € par an</div>
                                <div class="job-summary">
                                    Automatisation des déploiements et gestion de l'infrastructure cloud. Expertise AWS/Azure et Kubernetes souhaitée...
                                </div>
                                <div class="job-tags">
                                    <span class="tag">AWS</span>
                                    <span class="tag">Kubernetes</span>
                                    <span class="tag">Docker</span>
                                    <span class="tag">Télétravail complet</span>
                                </div>
                            </div>
                            <div class="job-meta">
                                <div class="job-type">CDI</div>
                                <div class="posted-date">Il y a 5 jours</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <button class="pagination-btn prev" disabled>
                            <i class="fas fa-chevron-left"></i>
                            Précédent
                        </button>
                        <div class="pagination-numbers">
                            <button class="pagination-number active">1</button>
                            <button class="pagination-number">2</button>
                            <button class="pagination-number">3</button>
                            <button class="pagination-number">4</button>
                            <button class="pagination-number">5</button>
                            <span class="pagination-dots">...</span>
                            <button class="pagination-number">25</button>
                        </div>
                        <button class="pagination-btn next">
                            Suivant
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </main>
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