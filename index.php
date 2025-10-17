<?php
// Point d'entrée principal de l'application Job Board
// Redirige vers la page d'accueil ou vers l'admin selon le rôle

session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    // Redirection vers l'interface admin
    header('Location: view/admin/index.php');
    exit();
} else {
    // Redirection vers la page d'accueil normale
    header('Location: view/index.php');
    exit();
}
?>