<?php
declare(strict_types=1);

require __DIR__ . DIRECTORY_SEPARATOR . 'auth.php';
start_app_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}

if (!is_valid_csrf_token()) {
    http_response_code(400);
    exit('Invalid form token. Refresh the page and try again.');
}

$_SESSION = [];
$cookie = session_get_cookie_params();
setcookie(session_name(), '', [
    'expires' => time() - 42000,
    'path' => $cookie['path'],
    'domain' => $cookie['domain'],
    'secure' => $cookie['secure'],
    'httponly' => $cookie['httponly'],
    'samesite' => $cookie['samesite'],
]);
session_destroy();
redirect_to('index.php');
