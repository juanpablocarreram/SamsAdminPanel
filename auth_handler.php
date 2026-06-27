<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database.php';

$action = $_POST['action'] ?? '';

if ($action === 'login') {

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        header('Location: login.php?error=campos');
        exit();
    }

    try {
        $pdo  = Database::connect();
        $stmt = $pdo->prepare(
            "SELECT u.id, u.nombre, u.password_hash, u.rol, u.sucursal_id,
                    s.nombre AS sucursal_nombre
             FROM usuarios u
             JOIN sucursales s ON s.id = u.sucursal_id
             WHERE u.email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header('Location: login.php?error=credenciales');
            exit();
        }

        if (!password_verify($password, $user['password_hash'])) {
            header('Location: login.php?error=credenciales');
            exit();
        }

        session_regenerate_id(true);

        $_SESSION['user_id']         = (int) $user['id'];
        $_SESSION['sucursal_id']     = (int) $user['sucursal_id'];
        $_SESSION['rol']             = $user['rol'];
        $_SESSION['nombre']          = $user['nombre'];
        $_SESSION['sucursal_nombre'] = $user['sucursal_nombre'];

        header('Location: index.php');
        exit();

    } catch (Exception $e) {
        header('Location: login.php?error=server');
        exit();
    }

} elseif ($action === 'register') {

    $nombre          = trim($_POST['nombre']          ?? '');
    $email           = trim($_POST['email']           ?? '');
    $password        = trim($_POST['password']        ?? '');
    $nombre_sucursal = trim($_POST['nombre_sucursal'] ?? '');

    if ($nombre === '' || $email === '' || $password === '' || $nombre_sucursal === '') {
        header('Location: login.php?error=campos');
        exit();
    }

    try {
        $pdo = Database::connect();

        // Verificar email único
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header('Location: login.php?error=email_exists');
            exit();
        }

        $pdo->beginTransaction();

        // INSERT sucursal con código temporal
        $tempCodigo = 'SUC-TEMP-' . time();
        $stmt = $pdo->prepare("INSERT INTO sucursales (nombre, codigo) VALUES (?, ?)");
        $stmt->execute([$nombre_sucursal, $tempCodigo]);
        $sucursal_id = (int) $pdo->lastInsertId();

        // UPDATE para generar código basado en ID (garantiza unicidad)
        $codigo = 'SUC-' . str_pad($sucursal_id, 4, '0', STR_PAD_LEFT);
        $pdo->prepare("UPDATE sucursales SET codigo = ? WHERE id = ?")->execute([$codigo, $sucursal_id]);

        // INSERT usuario
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare(
            "INSERT INTO usuarios (nombre, email, password_hash, rol, sucursal_id) VALUES (?, ?, ?, 'ADMIN', ?)"
        )->execute([$nombre, $email, $hash, $sucursal_id]);
        $user_id = (int) $pdo->lastInsertId();

        $pdo->commit();

    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header('Location: login.php?error=server');
        exit();
    }

    session_regenerate_id(true);

    $_SESSION['user_id']         = $user_id;
    $_SESSION['sucursal_id']     = $sucursal_id;
    $_SESSION['rol']             = 'ADMIN';
    $_SESSION['nombre']          = $nombre;
    $_SESSION['sucursal_nombre'] = $nombre_sucursal;

    header('Location: index.php');
    exit();

} elseif ($action === 'register_google') {

    if (empty($_SESSION['google_pending'])) {
        header('Location: login.php?error=invalid');
        exit();
    }

    $nombre_sucursal = trim($_POST['nombre_sucursal'] ?? '');

    if ($nombre_sucursal === '') {
        header('Location: login.php?error=campos&tab=register');
        exit();
    }

    $google_id = $_SESSION['google_pending']['google_id'];
    $email     = $_SESSION['google_pending']['email'];
    $nombre    = $_SESSION['google_pending']['nombre'];

    try {
        $pdo = Database::connect();

        $pdo->beginTransaction();

        // INSERT sucursal con código temporal
        $tempCodigo = 'SUC-TEMP-' . time();
        $stmt = $pdo->prepare("INSERT INTO sucursales (nombre, codigo) VALUES (?, ?)");
        $stmt->execute([$nombre_sucursal, $tempCodigo]);
        $sucursal_id = (int) $pdo->lastInsertId();

        // UPDATE para generar código basado en ID (garantiza unicidad)
        $codigo = 'SUC-' . str_pad($sucursal_id, 4, '0', STR_PAD_LEFT);
        $pdo->prepare("UPDATE sucursales SET codigo = ? WHERE id = ?")->execute([$codigo, $sucursal_id]);

        // INSERT usuario con google_id (sin password_hash)
        $pdo->prepare(
            "INSERT INTO usuarios (nombre, email, google_id, rol, sucursal_id) VALUES (?, ?, ?, 'ADMIN', ?)"
        )->execute([$nombre, $email, $google_id, $sucursal_id]);
        $user_id = (int) $pdo->lastInsertId();

        $pdo->commit();

    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header('Location: login.php?error=server');
        exit();
    }

    unset($_SESSION['google_pending']);

    $_SESSION['user_id']         = $user_id;
    $_SESSION['sucursal_id']     = $sucursal_id;
    $_SESSION['rol']             = 'ADMIN';
    $_SESSION['nombre']          = $nombre;
    $_SESSION['sucursal_nombre'] = $nombre_sucursal;

    header('Location: index.php');
    exit();

} elseif ($action === 'logout') {

    session_destroy();
    header('Location: login.php');
    exit();

} else {
    header('Location: login.php');
    exit();
}
