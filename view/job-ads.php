<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces d'Emploi - Job Finder</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/job-ads.css">
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>Dernières Annonces d'Emploi</h1>
                <p class="page-subtitle">Découvrez nos opportunités d'emploi disponibles</p>
            </div>

            <!-- Job Ads Grid -->
            <div class="job-ads-grid">
                
                <!-- Job Ad 1 -->
                <article class="job-ad-card" data-job-id="1">
                    <div class="job-ad-header">
                        <h2 class="job-title">Développeur Full Stack Senior</h2>
                        <div class="company-info">
                            <span class="company-name">TechCorp</span>
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i> Paris, France</span>
                        </div>
                    </div>
                    
                    <div class="job-summary">
                        <p>Rejoignez notre équipe dynamique pour développer des applications web innovantes. Nous recherchons un développeur expérimenté maîtrisant React et Node.js.</p>
                    </div>
                    
                    <div class="job-meta">
                        <span class="job-type">CDI</span>
                        <span class="salary-range">55k - 70k €</span>
                        <span class="posted-date">Il y a 2 jours</span>
                    </div>
                    
                    <div class="job-actions">
                        <button class="learn-more-btn" onclick="toggleJobDetails(1)">
                            <span class="btn-text">Learn More</span>
                            <i class="fas fa-chevron-down btn-icon"></i>
                        </button>
                    </div>
                    
                    <!-- Detailed Information (Hidden by default) -->
                    <div class="job-details" id="job-details-1" style="display: none;">
                        <div class="details-content">
                            <div class="detail-section">
                                <h3>Full Job Description</h3>
                                <p>As a Senior Full Stack Developer, you will be responsible for designing, developing, and maintaining complex web applications. You will work closely with our design and product management teams to create exceptional user experiences.</p>
                                
                                <h4>Main Responsibilities:</h4>
                                <ul>
                                    <li>Develop front-end applications with React.js</li>
                                    <li>Create robust APIs with Node.js and Express</li>
                                    <li>Optimize application performance</li>
                                    <li>Participate in code reviews and mentoring</li>
                                    <li>Collaborate with UX/UI teams</li>
                                </ul>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Detailed Information</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Salary:</strong>
                                        <span>€55,000 - €70,000 gross/year + benefits</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Location:</strong>
                                        <span>Paris 8th arrondissement (75008)</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Working Time:</strong>
                                        <span>Full-time - 39h/week</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Experience Required:</strong>
                                        <span>5+ years in web development</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Contract Type:</strong>
                                        <span>Permanent - 3 months probation</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Remote Work:</strong>
                                        <span>Hybrid - 3 days/week possible</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Required Skills</h3>
                                <div class="skills-list">
                                    <span class="skill-tag">React.js</span>
                                    <span class="skill-tag">Node.js</span>
                                    <span class="skill-tag">MongoDB</span>
                                    <span class="skill-tag">PostgreSQL</span>
                                    <span class="skill-tag">Git</span>
                                    <span class="skill-tag">Docker</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Job Ad 2 -->
                <article class="job-ad-card" data-job-id="2">
                    <div class="job-ad-header">
                        <h2 class="job-title">Designer UX/UI</h2>
                        <div class="company-info">
                            <span class="company-name">Creative Studio</span>
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i> Lyon, France</span>
                        </div>
                    </div>
                    
                    <div class="job-summary">
                        <p>Nous cherchons un(e) designer créatif(ve) pour concevoir des interfaces utilisateur intuitives et esthétiques. Rejoignez une équipe passionnée par l'innovation.</p>
                    </div>
                    
                    <div class="job-meta">
                        <span class="job-type">CDI</span>
                        <span class="salary-range">42k - 55k €</span>
                        <span class="posted-date">Il y a 1 jour</span>
                    </div>
                    
                    <div class="job-actions">
                        <button class="learn-more-btn" onclick="toggleJobDetails(2)">
                            <span class="btn-text">Learn More</span>
                            <i class="fas fa-chevron-down btn-icon"></i>
                        </button>
                    </div>
                    
                    <!-- Detailed Information -->
                    <div class="job-details" id="job-details-2" style="display: none;">
                        <div class="details-content">
                            <div class="detail-section">
                                <h3>Description complète du poste</h3>
                                <p>En tant que Designer UX/UI, vous serez au cœur de la création d'expériences utilisateur exceptionnelles. Vous concevrez des interfaces innovantes et ergonomiques pour nos clients dans divers secteurs.</p>
                                
                                <h4>Missions principales :</h4>
                                <ul>
                                    <li>Concevoir des wireframes et prototypes interactifs</li>
                                    <li>Créer des interfaces visuellement attractives</li>
                                    <li>Mener des tests utilisateurs et analyser les résultats</li>
                                    <li>Collaborer avec les équipes de développement</li>
                                    <li>Maintenir et faire évoluer les design systems</li>
                                </ul>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Informations détaillées</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Salaire :</strong>
                                        <span>42 000 € - 55 000 € brut/an + primes</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Lieu :</strong>
                                        <span>Lyon 3ème arrondissement (69003)</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Temps de travail :</strong>
                                        <span>Temps plein - 35h/semaine</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Expérience requise :</strong>
                                        <span>3+ années en design UX/UI</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Type de contrat :</strong>
                                        <span>CDI - Période d'essai 2 mois</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Télétravail :</strong>
                                        <span>Flexible - jusqu'à 2 jours/semaine</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Compétences requises</h3>
                                <div class="skills-list">
                                    <span class="skill-tag">Figma</span>
                                    <span class="skill-tag">Adobe Creative Suite</span>
                                    <span class="skill-tag">Sketch</span>
                                    <span class="skill-tag">Prototyping</span>
                                    <span class="skill-tag">User Research</span>
                                    <span class="skill-tag">HTML/CSS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Job Ad 3 -->
                <article class="job-ad-card" data-job-id="3">
                    <div class="job-ad-header">
                        <h2 class="job-title">Chef de Projet Digital</h2>
                        <div class="company-info">
                            <span class="company-name">Digital Agency</span>
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i> Marseille, France</span>
                        </div>
                    </div>
                    
                    <div class="job-summary">
                        <p>Pilotez des projets digitaux d'envergure et coordonnez des équipes multidisciplinaires. Une opportunité unique dans une agence en forte croissance.</p>
                    </div>
                    
                    <div class="job-meta">
                        <span class="job-type">CDI</span>
                        <span class="salary-range">50k - 65k €</span>
                        <span class="posted-date">Il y a 3 jours</span>
                    </div>
                    
                    <div class="job-actions">
                        <button class="learn-more-btn" onclick="toggleJobDetails(3)">
                            <span class="btn-text">Learn More</span>
                            <i class="fas fa-chevron-down btn-icon"></i>
                        </button>
                    </div>
                    
                    <!-- Detailed Information -->
                    <div class="job-details" id="job-details-3" style="display: none;">
                        <div class="details-content">
                            <div class="detail-section">
                                <h3>Description complète du poste</h3>
                                <p>En tant que Chef de Projet Digital, vous serez responsable de la planification, de l'exécution et de la livraison de projets digitaux complexes. Vous gérerez les relations clients et coordonnerez les équipes techniques et créatives.</p>
                                
                                <h4>Responsabilités :</h4>
                                <ul>
                                    <li>Gérer le cycle de vie complet des projets digitaux</li>
                                    <li>Coordonner les équipes techniques et créatives</li>
                                    <li>Assurer la relation client et le suivi budgétaire</li>
                                    <li>Planifier et optimiser les ressources projet</li>
                                    <li>Garantir la qualité et les délais de livraison</li>
                                </ul>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Informations détaillées</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Salaire :</strong>
                                        <span>50 000 € - 65 000 € brut/an + variable</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Lieu :</strong>
                                        <span>Marseille 2ème arrondissement (13002)</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Temps de travail :</strong>
                                        <span>Temps plein - 37h/semaine</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Expérience requise :</strong>
                                        <span>4+ années en gestion de projet digital</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Type de contrat :</strong>
                                        <span>CDI - Période d'essai 3 mois</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Télétravail :</strong>
                                        <span>Occasionnel - 1 jour/semaine max</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Compétences requises</h3>
                                <div class="skills-list">
                                    <span class="skill-tag">Gestion de projet</span>
                                    <span class="skill-tag">Scrum/Agile</span>
                                    <span class="skill-tag">Jira</span>
                                    <span class="skill-tag">Relation client</span>
                                    <span class="skill-tag">Budget</span>
                                    <span class="skill-tag">Leadership</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Job Ad 4 -->
                <article class="job-ad-card" data-job-id="4">
                    <div class="job-ad-header">
                        <h2 class="job-title">Data Scientist</h2>
                        <div class="company-info">
                            <span class="company-name">AI Solutions</span>
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i> Toulouse, France</span>
                        </div>
                    </div>
                    
                    <div class="job-summary">
                        <p>Analysez des données complexes et développez des modèles prédictifs pour optimiser nos algorithmes d'intelligence artificielle. Poste à fort impact technique.</p>
                    </div>
                    
                    <div class="job-meta">
                        <span class="job-type">CDI</span>
                        <span class="salary-range">60k - 80k €</span>
                        <span class="posted-date">Il y a 5 jours</span>
                    </div>
                    
                    <div class="job-actions">
                        <button class="learn-more-btn" onclick="toggleJobDetails(4)">
                            <span class="btn-text">Learn More</span>
                            <i class="fas fa-chevron-down btn-icon"></i>
                        </button>
                    </div>
                    
                    <!-- Detailed Information -->
                    <div class="job-details" id="job-details-4" style="display: none;">
                        <div class="details-content">
                            <div class="detail-section">
                                <h3>Description complète du poste</h3>
                                <p>En tant que Data Scientist, vous exploiterez des volumes importants de données pour en extraire des insights métier et développer des modèles d'apprentissage automatique. Vous travaillerez sur des projets innovants en IA.</p>
                                
                                <h4>Missions :</h4>
                                <ul>
                                    <li>Analyser et traiter de gros volumes de données</li>
                                    <li>Développer des modèles de machine learning</li>
                                    <li>Créer des visualisations de données impactantes</li>
                                    <li>Optimiser les algorithmes existants</li>
                                    <li>Collaborer avec les équipes produit et engineering</li>
                                </ul>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Informations détaillées</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Salaire :</strong>
                                        <span>60 000 € - 80 000 € brut/an + stock-options</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Lieu :</strong>
                                        <span>Toulouse Centre (31000)</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Temps de travail :</strong>
                                        <span>Temps plein - 39h/semaine</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Expérience requise :</strong>
                                        <span>3+ années en data science/ML</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Type de contrat :</strong>
                                        <span>CDI - Période d'essai 4 mois</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Télétravail :</strong>
                                        <span>Full remote possible</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Compétences requises</h3>
                                <div class="skills-list">
                                    <span class="skill-tag">Python</span>
                                    <span class="skill-tag">TensorFlow</span>
                                    <span class="skill-tag">Pandas</span>
                                    <span class="skill-tag">SQL</span>
                                    <span class="skill-tag">Machine Learning</span>
                                    <span class="skill-tag">Statistics</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Job Ad 5 -->
                <article class="job-ad-card" data-job-id="5">
                    <div class="job-ad-header">
                        <h2 class="job-title">DevOps Engineer</h2>
                        <div class="company-info">
                            <span class="company-name">CloudTech</span>
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i> Nantes, France</span>
                        </div>
                    </div>
                    
                    <div class="job-summary">
                        <p>Automatisez et optimisez notre infrastructure cloud. Rejoignez une équipe technique de haut niveau dans un environnement technologique de pointe.</p>
                    </div>
                    
                    <div class="job-meta">
                        <span class="job-type">CDI</span>
                        <span class="salary-range">55k - 75k €</span>
                        <span class="posted-date">Il y a 1 semaine</span>
                    </div>
                    
                    <div class="job-actions">
                        <button class="learn-more-btn" onclick="toggleJobDetails(5)">
                            <span class="btn-text">Learn More</span>
                            <i class="fas fa-chevron-down btn-icon"></i>
                        </button>
                    </div>
                    
                    <!-- Detailed Information -->
                    <div class="job-details" id="job-details-5" style="display: none;">
                        <div class="details-content">
                            <div class="detail-section">
                                <h3>Description complète du poste</h3>
                                <p>En tant que DevOps Engineer, vous serez responsable de l'automatisation, du déploiement et de la maintenance de notre infrastructure cloud. Vous travaillerez sur des systèmes critiques à haute disponibilité.</p>
                                
                                <h4>Responsabilités :</h4>
                                <ul>
                                    <li>Automatiser les déploiements et la CI/CD</li>
                                    <li>Gérer et optimiser l'infrastructure cloud (AWS/Azure)</li>
                                    <li>Monitoring et alerting des systèmes de production</li>
                                    <li>Sécurisation des environnements et données</li>
                                    <li>Support technique et résolution d'incidents</li>
                                </ul>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Informations détaillées</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Salaire :</strong>
                                        <span>55 000 € - 75 000 € brut/an + astreintes</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Lieu :</strong>
                                        <span>Nantes Centre-ville (44000)</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Temps de travail :</strong>
                                        <span>Temps plein - 37h/semaine + astreintes</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Expérience requise :</strong>
                                        <span>4+ années en DevOps/Infrastructure</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Type de contrat :</strong>
                                        <span>CDI - Période d'essai 3 mois</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Télétravail :</strong>
                                        <span>Hybride - 2-3 jours/semaine</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Compétences requises</h3>
                                <div class="skills-list">
                                    <span class="skill-tag">AWS/Azure</span>
                                    <span class="skill-tag">Docker</span>
                                    <span class="skill-tag">Kubernetes</span>
                                    <span class="skill-tag">Terraform</span>
                                    <span class="skill-tag">Jenkins</span>
                                    <span class="skill-tag">Linux</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Job Ad 6 -->
                <article class="job-ad-card" data-job-id="6">
                    <div class="job-ad-header">
                        <h2 class="job-title">Commercial B2B</h2>
                        <div class="company-info">
                            <span class="company-name">Sales Pro</span>
                            <span class="job-location"><i class="fas fa-map-marker-alt"></i> Bordeaux, France</span>
                        </div>
                    </div>
                    
                    <div class="job-summary">
                        <p>Développez notre portefeuille client et générez de nouveaux revenus. Excellent package de rémunération avec commission attractive pour un commercial ambitieux.</p>
                    </div>
                    
                    <div class="job-meta">
                        <span class="job-type">CDI</span>
                        <span class="salary-range">40k - 60k €</span>
                        <span class="posted-date">Il y a 4 jours</span>
                    </div>
                    
                    <div class="job-actions">
                        <button class="learn-more-btn" onclick="toggleJobDetails(6)">
                            <span class="btn-text">Learn More</span>
                            <i class="fas fa-chevron-down btn-icon"></i>
                        </button>
                    </div>
                    
                    <!-- Detailed Information -->
                    <div class="job-details" id="job-details-6" style="display: none;">
                        <div class="details-content">
                            <div class="detail-section">
                                <h3>Description complète du poste</h3>
                                <p>En tant que Commercial B2B, vous développerez notre présence sur le marché en prospectant de nouveaux clients et en fidélisant notre portefeuille existant. Vous bénéficierez d'un excellent environnement de travail et d'outils performants.</p>
                                
                                <h4>Missions principales :</h4>
                                <ul>
                                    <li>Prospecter et développer un portefeuille clients B2B</li>
                                    <li>Négocier et conclure des contrats commerciaux</li>
                                    <li>Assurer le suivi et la fidélisation des clients</li>
                                    <li>Analyser le marché et identifier les opportunités</li>
                                    <li>Participer aux salons et événements professionnels</li>
                                </ul>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Informations détaillées</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Salaire :</strong>
                                        <span>40 000 € fixe + jusqu'à 20 000 € variable</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Lieu :</strong>
                                        <span>Bordeaux + déplacements régionaux (33000)</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Temps de travail :</strong>
                                        <span>Temps plein - 39h/semaine</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Expérience requise :</strong>
                                        <span>2+ années en vente B2B</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Type de contrat :</strong>
                                        <span>CDI - Période d'essai 2 mois</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Télétravail :</strong>
                                        <span>Limité - principalement terrain</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Compétences requises</h3>
                                <div class="skills-list">
                                    <span class="skill-tag">Négociation</span>
                                    <span class="skill-tag">Prospection</span>
                                    <span class="skill-tag">CRM</span>
                                    <span class="skill-tag">Relationnel</span>
                                    <span class="skill-tag">Présentation</span>
                                    <span class="skill-tag">Anglais</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2025 Job Finder. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/job-ads.js"></script>
</body>
</html>