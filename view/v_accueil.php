<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Board - Accueil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
        }
        
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
        }
        
        nav ul li {
            margin: 0 20px;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        
        nav ul li a:hover {
            text-decoration: underline;
        }
        
        .hero {
            background-color: #3498db;
            color: white;
            text-align: center;
            padding: 4rem 0;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #c0392b;
        }
        
        .features {
            padding: 3rem 0;
            background-color: white;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .feature {
            text-align: center;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .feature h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            nav ul {
                flex-direction: column;
                align-items: center;
            }
            
            nav ul li {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#emplois">Emplois</a></li>
                    <li><a href="#entreprises">Entreprises</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Bienvenue sur Job Board</h1>
            <p>Trouvez l'emploi de vos rêves ou publiez vos offres d'emploi</p>
            <a href="#emplois" class="btn">Voir les emplois</a>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 1rem; color: #2c3e50;">Nos Services</h2>
            <div class="features-grid">
                <div class="feature">
                    <h3>🔍 Recherche d'Emploi</h3>
                    <p>Parcourez des milliers d'offres d'emploi dans tous les secteurs et trouvez le poste qui vous correspond.</p>
                </div>
                <div class="feature">
                    <h3>💼 Pour les Entreprises</h3>
                    <p>Publiez vos offres d'emploi et trouvez les meilleurs candidats pour votre entreprise.</p>
                </div>
                <div class="feature">
                    <h3>📊 Suivi des Candidatures</h3>
                    <p>Suivez l'état de vos candidatures et restez informé des réponses des employeurs.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 Job Board. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
