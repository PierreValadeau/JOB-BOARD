-- Script SQL pour recréer la base de données job avec structure optimisée
-- Avec champs JSON pour les détails des offres

-- ATTENTION: Ce script va supprimer et recréer la base 'job'
DROP DATABASE IF EXISTS `job`;
CREATE DATABASE `job` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `job`;

-- Table des entreprises
CREATE TABLE `companies` (
  `id_companies` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_companies`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des utilisateurs
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('candidate','recruiter','admin') NOT NULL DEFAULT 'candidate',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des offres d'emploi (avec nouveaux champs JSON)
CREATE TABLE `offers` (
  `offers_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_companies` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `long_description` text DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `contract_type` enum('CDI','CDD','Stage','Freelance','Alternance') NOT NULL DEFAULT 'CDI',
  `salary` decimal(10,2) DEFAULT NULL,
  `published_date` date NOT NULL,
  `job_requirements` json DEFAULT NULL,
  `company_info` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`offers_id`),
  KEY `id_companies` (`id_companies`),
  KEY `published_date` (`published_date`),
  KEY `location` (`location`),
  CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`id_companies`) REFERENCES `companies` (`id_companies`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des candidatures
CREATE TABLE `application` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `offers_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `cover_letter` text DEFAULT NULL,
  PRIMARY KEY (`application_id`),
  KEY `user_id` (`user_id`),
  KEY `offers_id` (`offers_id`),
  CONSTRAINT `application_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `application_ibfk_2` FOREIGN KEY (`offers_id`) REFERENCES `offers` (`offers_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des données d'exemple pour les entreprises
INSERT INTO `companies` (`name`, `email`, `phone`, `location`, `description`, `website`) VALUES
('TechNova', 'contact@technova.fr', '0123456789', 'Paris', 'Startup spécialisée dans le développement web', 'https://technova.fr'),
('DataCorp', 'rh@datacorp.fr', '0123456790', 'Lyon', 'Entreprise de conseil en data science', 'https://datacorp.fr'),
('WebAgency', 'jobs@webagency.fr', '0123456791', 'Marseille', 'Agence web créative', 'https://webagency.fr'),
('FinTech Solutions', 'careers@fintech.fr', '0123456792', 'Lille', 'Solutions fintech innovantes', 'https://fintech.fr'),
('CloudSys', 'recrutement@cloudsys.fr', '0123456793', 'Toulouse', 'Services cloud et infrastructure', 'https://cloudsys.fr'),
('Foodie', 'rh@foodie.fr', '0123456794', 'Paris', 'Plateforme de livraison alimentaire', 'https://foodie.fr'),
('SecureIT', 'contact@secureit.fr', '0123456795', 'Lille', 'Cybersécurité et audit', 'https://secureit.fr');

-- Insertion des utilisateurs d'exemple
INSERT INTO `users` (`first_name`, `last_name`, `email`, `phone`, `password`, `role`) VALUES
('Jean', 'Dupont', 'jean.dupont@email.fr', '0612345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate'),
('Marie', 'Martin', 'marie.martin@email.fr', '0612345679', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate'),
('Pierre', 'Durand', 'pierre.durand@email.fr', '0612345680', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recruiter'),
('Sophie', 'Bernard', 'sophie.bernard@email.fr', '0612345681', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'candidate');

-- Insertion des offres d'emploi avec les nouveaux champs JSON
INSERT INTO `offers` (`id_companies`, `title`, `description`, `long_description`, `location`, `contract_type`, `salary`, `published_date`, `job_requirements`, `company_info`) VALUES
(1, 'Développeur Backend', 'Développement en Python/Django.', 'Rejoignez notre équipe de développement pour créer des applications web innovantes. Vous participerez à la conception, au développement et à la maintenance de nos solutions SaaS. Vous travaillerez en méthode agile au sein d\'une équipe de 8 développeurs passionnés.', 'Paris', 'CDI', 42000.00, '2025-10-01', 
JSON_OBJECT(
    'skills', JSON_ARRAY('Python', 'Django', 'PostgreSQL', 'Docker', 'Git', 'API REST', 'HTML/CSS', 'JavaScript', 'React'),
    'experience', '2-5 ans d\'expérience en développement web',
    'education', 'Bac+3/5 en informatique ou équivalent'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Télétravail hybride', 'Mutuelle 100% prise en charge', 'Tickets restaurant', 'Formation continue', 'MacBook Pro fourni'),
    'working_conditions', '39h/semaine, horaires flexibles 8h-19h, bureau moderne Paris 11ème, café illimité'
)),

(2, 'Data Scientist', 'Analyse de données et machine learning.', 'Poste clé pour développer nos modèles prédictifs et analyser les données clients. Vous travaillerez sur des projets variés incluant l\'analyse comportementale, la segmentation client et l\'optimisation des performances.', 'Lyon', 'CDI', 48000.00, '2025-10-01',
JSON_OBJECT(
    'skills', JSON_ARRAY('Python', 'R', 'SQL', 'Machine Learning', 'Pandas', 'Scikit-learn', 'TensorFlow', 'Tableau'),
    'experience', '3-6 ans en data science',
    'education', 'Master en statistiques, mathématiques ou informatique'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Formation IA/ML', 'Conférences tech', 'Équipement haute performance', 'Prime innovation'),
    'working_conditions', '37h/semaine, full remote possible, bureau Lyon Part-Dieu'
)),

(3, 'Développeur Frontend', 'Interfaces utilisateur modernes.', 'Créez des interfaces utilisateur exceptionnelles pour nos clients. Vous serez responsable de l\'UX/UI, de l\'intégration des maquettes et de l\'optimisation des performances front-end.', 'Marseille', 'CDD', 38000.00, '2025-10-02',
JSON_OBJECT(
    'skills', JSON_ARRAY('JavaScript', 'React', 'Vue.js', 'CSS3', 'HTML5', 'Sass', 'Webpack', 'Figma'),
    'experience', '2-4 ans en développement frontend',
    'education', 'Bac+2/3 en développement web'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Outils créatifs', 'Formations design', 'Flex time', 'Prime résultat'),
    'working_conditions', 'CDD 12 mois renouvelable, Marseille centre, terrasse et espace détente'
)),

(4, 'DevOps Engineer', 'Infrastructure et déploiement.', 'Optimisez notre infrastructure cloud et automatisez nos processus de déploiement. Vous serez au cœur de notre transformation digitale et de notre passage au cloud native.', 'Paris', 'CDI', 52000.00, '2025-10-02',
JSON_OBJECT(
    'skills', JSON_ARRAY('Docker', 'Kubernetes', 'AWS', 'Terraform', 'Jenkins', 'Ansible', 'Linux', 'GitLab CI'),
    'experience', '4-7 ans en DevOps/SRE',
    'education', 'Ingénieur ou master en informatique'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Certifications AWS', 'Conférences DevOps', 'Budget formation 3k€', 'Stock options'),
    'working_conditions', '40h/semaine, astreintes compensées, bureau moderne La Défense'
)),

(5, 'Architecte Cloud', 'Architecture solutions cloud.', 'Concevez et supervisez l\'architecture de nos solutions cloud. Vous définirez les standards techniques et accompagnerez les équipes dans la migration cloud.', 'Toulouse', 'CDI', 65000.00, '2025-10-03',
JSON_OBJECT(
    'skills', JSON_ARRAY('AWS', 'Azure', 'Microservices', 'Kafka', 'Redis', 'Elasticsearch', 'Architecture', 'Sécurité'),
    'experience', '7-10 ans dont 3 ans minimum en architecture',
    'education', 'Master/Ingénieur en informatique'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Management technique', 'Conférences internationales', 'Voiture de fonction', 'Intéressement'),
    'working_conditions', '39h/semaine, management d\'équipe, déplacements clients Europe'
)),

(6, 'Chef de produit', 'Gestion de produits agroalimentaires.', 'Pilotez le développement de nos produits agroalimentaires innovants. Vous définirez la stratégie produit, coordonnerez les équipes R&D et marketing, et assurerez le lancement sur le marché.', 'Paris', 'CDD', 39000.00, '2025-10-02',
JSON_OBJECT(
    'skills', JSON_ARRAY('Gestion de projet', 'Agroalimentaire', 'Marketing produit', 'Analyse de marché', 'Budget'),
    'experience', '5-8 ans en chef de produit alimentaire',
    'education', 'École de commerce ou ingénieur agroalimentaire'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Primes variables', 'Chèques vacances', 'CE actif', 'Parking gratuit'),
    'working_conditions', 'CDD 18 mois (CDI possible), Paris La Défense, 2j télétravail/semaine'
)),

(7, 'Analyste sécurité', 'Audit et sécurité réseau.', 'Nous recherchons un analyste sécurité pour renforcer notre équipe cybersécurité. Vous serez responsable des audits de sécurité, de la surveillance des incidents et de la mise en place de mesures préventives.', 'Lille', 'CDI', 46000.00, '2025-10-03',
JSON_OBJECT(
    'skills', JSON_ARRAY('Sécurité réseau', 'SIEM', 'Firewall', 'Pentest', 'ISO 27001', 'CISSP', 'Wireshark', 'Nessus'),
    'experience', '3-7 ans en sécurité informatique',
    'education', 'Master en cybersécurité ou certification CISSP'
),
JSON_OBJECT(
    'benefits', JSON_ARRAY('Prime sur objectifs', 'Formation certifiante', 'Véhicule de fonction', 'Mutuelle famille'),
    'working_conditions', 'CDI, Lille centre, déplacements clients 20%, astreintes compensées'
));

-- Insertion de quelques candidatures d'exemple
INSERT INTO `application` (`user_id`, `offers_id`, `status`, `cover_letter`) VALUES
(1, 1, 'pending', 'Motivé par le développement web et les nouvelles technologies.'),
(2, 2, 'accepted', 'Expérience en data science et passion pour l\'analyse de données.'),
(1, 3, 'rejected', 'Intéressé par le développement frontend et l\'UX.'),
(4, 4, 'pending', 'Expérience DevOps et envie de rejoindre une équipe dynamique.');

-- Affichage des statistiques
SELECT 'Base de données job recréée avec succès !' as message;
SELECT COUNT(*) as total_companies FROM companies;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_offers FROM offers;
SELECT COUNT(*) as total_applications FROM application;