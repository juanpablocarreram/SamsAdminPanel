<?php
// 1. Cargamos de forma obligatoria el autoloader de Composer para que reconozca phpdotenv
require_once __DIR__ . '/vendor/autoload.php';

// 2. Inicializamos la librería buscando el archivo .env en la raíz de este directorio
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // safeLoad evita que la app muera si el archivo .env no existe aún

class Database {
    // Mantener la propiedad estática para controlar la instancia única de PDO
    private static $cont = null;

    public function __construct() { exit('Init function is not allowed'); }

    public static function connect() {
        if (null == self::$cont) {
            try {
                // 3. Extraemos las credenciales dinámicamente desde $_ENV.
                // Usamos el operador '??' para asignar valores por defecto en caso de que falten en el .env
                $dbHost     = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $dbName     = $_ENV['DB_NAME'] ?? 'ICA_final';
                $dbUsername = $_ENV['DB_USER'];
                $dbPassword = $_ENV['DB_PASS'];

                // 4. Inyectamos las variables dinámicas en el DSN y la configuración de PDO
                self::$cont = new PDO(
                    "mysql:host=" . $dbHost . ";dbname=" . $dbName . ";charset=utf8",
                    $dbUsername,
                    $dbPassword,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Error de conexión: ' . $e->getMessage()]);
                exit();
            }
        }
        return self::$cont;
    }

    public static function disconnect() { self::$cont = null; }
}
?>