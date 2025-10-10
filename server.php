<?php


$uri = $_SERVER['REQUEST_URI'];
$file = __DIR__ . $uri;

if ($uri == '/') {
    include __DIR__ . '/view/index.php';
    exit;
}

if (is_file($file)) {
    return false; 
}


switch ($uri) {
    case '/index.php':
    case '/index':
        include __DIR__ . '/view/index.php';
        break;
    case '/login.php':
    case '/login':
        include __DIR__ . '/view/login.php';
        break;
    case '/register.php':
    case '/register':
        include __DIR__ . '/view/register.php';
        break;
    case '/job-ads.php':
    case '/job-ads':
        include __DIR__ . '/view/job-ads.php';
        break;
    case '/job-detail.php':
    case '/job-detail':
        include __DIR__ . '/view/job-detail.php';
        break;
    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
}
?>