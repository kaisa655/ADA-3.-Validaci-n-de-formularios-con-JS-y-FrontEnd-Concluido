<?php
require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conexion->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('No se pudo conectar con la base de datos. Revisa includes/config.php y asegurate de importar database.sql.');
}
