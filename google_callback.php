<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$client = new Google\Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID'] ?? '');
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET'] ?? '');
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI'] ?? '');
$client->addScope('email');
$client->addScope('profile');

// --- FLUJO INICIO: redirigir a Google ---
if (isset($_GET['start'])) {
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;
    $client->setState($state);
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
}

// --- FLUJO CALLBACK: intercambiar código ---
if (!isset($_GET['code'])) {
    header('Location: login.php?error=invalid');
    exit();
}

if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
    header('Location: login.php?error=invalid');
    exit();
}
unset($_SESSION['oauth_state']);

try {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        header('Location: login.php?error=server');
        exit();
    }

    $client->setAccessToken($token);

    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $google_id = $userInfo->getId();
    $email     = $userInfo->getEmail();
    $nombre    = $userInfo->getName();

    require_once __DIR__ . '/database.php';
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Buscar por google_id
    $stmt = $pdo->prepare(
        "SELECT u.id, u.nombre, u.rol, u.sucursal_id, s.nombre AS sucursal_nombre
         FROM usuarios u
         JOIN sucursales s ON s.id = u.sucursal_id
         WHERE u.google_id = ? LIMIT 1"
    );
    $stmt->execute([$google_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // 2. Buscar por email (vincular cuenta existente)
        $stmt = $pdo->prepare(
            "SELECT u.id, u.nombre, u.rol, u.sucursal_id, s.nombre AS sucursal_nombre
             FROM usuarios u
             JOIN sucursales s ON s.id = u.sucursal_id
             WHERE u.email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vincular google_id a cuenta existente
            $pdo->prepare("UPDATE usuarios SET google_id = ? WHERE id = ?")
                ->execute([$google_id, $user['id']]);
        }
    }

    if ($user) {
        // Login exitoso
        session_regenerate_id(true);
        $_SESSION['user_id']         = (int) $user['id'];
        $_SESSION['sucursal_id']     = (int) $user['sucursal_id'];
        $_SESSION['rol']             = $user['rol'];
        $_SESSION['nombre']          = $user['nombre'];
        $_SESSION['sucursal_nombre'] = $user['sucursal_nombre'];
        header('Location: index.php');
        exit();
    }

    // 3. Usuario nuevo — guardar datos en sesión y redirigir a registro
    $_SESSION['google_pending'] = [
        'google_id' => $google_id,
        'email'     => $email,
        'nombre'    => $nombre,
    ];
    header('Location: login.php?tab=register&google=1');
    exit();

} catch (Exception $e) {
    header('Location: login.php?error=server');
    exit();
}
