<?php
/**
 * Test de connexion à la base de données
 * Fichier à lancer pour vérifier que la configuration de la BDD fonctionne
 */

echo "<h2>🔌 Test de connexion à la base de données</h2>\n";
echo "<hr>\n";

// Vérification du fichier .env
echo "<h3>📁 Vérification des fichiers :</h3>\n";
if (file_exists(__DIR__ . '/.env')) {
    echo "✅ Fichier .env trouvé<br>\n";
} else {
    echo "❌ Fichier .env non trouvé<br>\n";
}

if (file_exists(__DIR__ . '/config/config.php')) {
    echo "✅ Fichier config.php trouvé<br>\n";
} else {
    echo "❌ Fichier config.php non trouvé<br>\n";
    exit;
}

// Inclusion de la configuration
echo "<h3>⚙️ Chargement de la configuration :</h3>\n";
try {
    require_once __DIR__ . '/config/config.php';
    echo "✅ Configuration chargée<br>\n";
    
    // Affichage des constantes
    echo "<strong>Constantes définies :</strong><br>\n";
    echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NON DÉFINI') . "<br>\n";
    echo "DB_PORT: " . (defined('DB_PORT') ? DB_PORT : 'NON DÉFINI') . "<br>\n";
    echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NON DÉFINI') . "<br>\n";
    echo "DB_USER: " . (defined('DB_USER') ? DB_USER : 'NON DÉFINI') . "<br>\n";
    echo "DB_CHARSET: " . (defined('DB_CHARSET') ? DB_CHARSET : 'NON DÉFINI') . "<br>\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement de la configuration: " . $e->getMessage() . "<br>\n";
    exit;
}

echo "<hr>\n";

try {
    // Test de la connexion
    $pdo = Database::getInstance()->getConnection();
    
    if ($pdo) {
        echo "✅ <strong>Connexion réussie !</strong><br>\n";
        
        // Affichage des informations de connexion (sans le mot de passe)
        echo "<h3>📋 Informations de connexion :</h3>\n";
        echo "<ul>\n";
        echo "<li><strong>Host:</strong> " . DB_HOST . "</li>\n";
        echo "<li><strong>Port:</strong> " . DB_PORT . "</li>\n";
        echo "<li><strong>Database:</strong> " . DB_NAME . "</li>\n";
        echo "<li><strong>Username:</strong> " . DB_USER . "</li>\n";
        echo "<li><strong>Charset:</strong> " . DB_CHARSET . "</li>\n";
        echo "</ul>\n";
        
        // Test de requête simple
        echo "<h3>🔍 Test de requête :</h3>\n";
        $stmt = $pdo->query("SELECT VERSION() as mysql_version, NOW() as current_datetime");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "<ul>\n";
            echo "<li><strong>Version MySQL:</strong> " . $result['mysql_version'] . "</li>\n";
            echo "<li><strong>Heure actuelle:</strong> " . $result['current_datetime'] . "</li>\n";
            echo "</ul>\n";
        }
        
        // Test des tables du projet
        echo "<h3>📊 Vérification des tables :</h3>\n";
        $tables = ['offers', 'companies', 'users'];
        echo "<ul>\n";
        
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "<li>✅ Table <strong>$table</strong> : $count enregistrement(s)</li>\n";
            } catch (Exception $e) {
                echo "<li>❌ Table <strong>$table</strong> : Non trouvée ou erreur</li>\n";
            }
        }
        echo "</ul>\n";
        
    } else {
        echo "❌ <strong>Erreur:</strong> Impossible d'obtenir la connexion PDO<br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ <strong>Erreur de connexion :</strong><br>\n";
    echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>\n";
    echo "<strong>Message d'erreur :</strong> " . $e->getMessage() . "<br>\n";
    echo "<strong>Code d'erreur :</strong> " . $e->getCode() . "<br>\n";
    echo "</div>\n";
    
    echo "<h3>🔧 Solutions possibles :</h3>\n";
    echo "<ul>\n";
    echo "<li>Vérifiez que votre serveur (MAMP/Laragon) est démarré</li>\n";
    echo "<li>Vérifiez les paramètres dans votre fichier <code>.env</code></li>\n";
    echo "<li>Vérifiez que la base de données existe</li>\n";
    echo "<li>Vérifiez les permissions utilisateur</li>\n";
    echo "</ul>\n";
    
    echo "<h3>📝 Configuration attendue :</h3>\n";
    echo "<div style='background: #f5f5f5; padding: 10px; font-family: monospace;'>\n";
    echo "DB_HOST=localhost<br>\n";
    echo "DB_PORT=8889 (MAMP) ou 3306 (Laragon)<br>\n";
    echo "DB_NAME=job<br>\n";
    echo "DB_USER=root<br>\n";
    echo "DB_PASS=root (MAMP) ou vide (Laragon)<br>\n";
    echo "</div>\n";
}

echo "<hr>\n";
echo "<p><em>Test effectué le " . date('Y-m-d H:i:s') . "</em></p>\n";

// Styles CSS pour améliorer l'affichage
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
h3 { color: #555; }
code { background: #f5f5f5; padding: 2px 4px; border-radius: 3px; }
ul { line-height: 1.6; }
li { margin: 5px 0; }
</style>";
?>