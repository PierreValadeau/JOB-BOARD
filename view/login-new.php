<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Job Finder</title>
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

    <main style="padding: 4rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: calc(100vh - 200px);">
        <div class="container">
            <div style="max-width: 400px; margin: 0 auto; background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <h1 style="color: #2c3e50; font-size: 1.8rem; margin-bottom: 0.5rem;">Connexion</h1>
                    <p style="color: #6c757d;">Accédez à votre espace Job Finder</p>
                </div>
                
                <form method="POST" action="#" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div>
                        <label for="email" style="display: block; margin-bottom: 0.5rem; color: #2c3e50; font-weight: 500;">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="votre@email.com"
                            required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                            onfocus="this.style.borderColor='#2557a7'"
                            onblur="this.style.borderColor='#e9ecef'"
                        >
                    </div>
                    
                    <div>
                        <label for="password" style="display: block; margin-bottom: 0.5rem; color: #2c3e50; font-weight: 500;">Mot de passe</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Votre mot de passe"
                            required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                            onfocus="this.style.borderColor='#2557a7'"
                            onblur="this.style.borderColor='#e9ecef'"
                        >
                    </div>
                    
                    <button 
                        type="submit" 
                        style="background: linear-gradient(135deg, #2557a7 0%, #1e4a8c 100%); color: white; border: none; padding: 0.9rem 1.5rem; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; width: 100%;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(37,87,167,0.3)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        Se connecter
                    </button>
                    
                    <div style="text-align: center; margin-top: 1rem;">
                        <p style="color: #6c757d;">Pas encore de compte ? 
                            <a href="register.php" style="color: #2557a7; text-decoration: none; font-weight: 500; transition: color 0.3s ease;"
                               onmouseover="this.style.color='#1e4a8c'"
                               onmouseout="this.style.color='#2557a7'">
                               Créer un compte
                            </a>
                        </p>
                    </div>
                </form>
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
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Job Finder. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>