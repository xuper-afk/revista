<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar el entorno automáticamente
// En Azure las variables de entorno están definidas por App Service
// En local no existen, entonces se usan los valores por defecto (XAMPP)

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'revista_digital');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Para Azure MySQL, el puerto puede venir en la variable DB_PORT
define('DB_PORT', getenv('DB_PORT') ?: '3306');

function getConexion() {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        // Necesario para Azure MySQL (SSL obligatorio)
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
    try {
        return new PDO($dsn, DB_USER, DB_PASS, $opciones);
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}