
<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/includes/conexion.php";

try {
    $consulta = $conexion->prepare(
        "SELECT
            juegos.id,
            juegos.usuario_id,
            juegos.titulo,
            juegos.descripcion,
            juegos.categoria,
            juegos.url_juego,
            juegos.portada,
            juegos.fecha_publicacion,
            usuarios.nombre AS nombre_usuario
        FROM juegos
        INNER JOIN usuarios
            ON juegos.usuario_id = usuarios.id
        ORDER BY juegos.fecha_publicacion DESC"
    );

    $consulta->execute();

    $juegos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "exito" => true,
        "usuario_actual_id" => $_SESSION["usuario_id"] ?? null,
        "juegos" => $juegos
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $error) {
    error_log($error->getMessage());

    http_response_code(500);

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudieron consultar los videojuegos."
    ], JSON_UNESCAPED_UNICODE);
}
