<?php
session_start();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: view/admin/index.php');
    exit();
} else {
    header('Location: view/index.php');
    exit();
}
?>