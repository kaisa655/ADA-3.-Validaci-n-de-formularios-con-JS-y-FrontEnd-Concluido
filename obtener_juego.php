
<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/includes/conexion.php";

// Verificar sesión
if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Debes iniciar sesión."
    ]);
    exit;
}

// Obtener y validar ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    http_response_code(400);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Identificador de videojuego inválido."
    ]);
    exit;
}

try {
    // Consultar únicamente videojuegos del usuario
    $consulta = $conexion->prepare(
        "SELECT id, titulo, descripcion, categoria,
                url_juego, portada
         FROM juegos
         WHERE id = :id AND usuario_id = :usuario_id"
    );

    $consulta->execute([
        ":id" => $id,
        ":usuario_id" => (int) $_SESSION["usuario_id"]
    ]);

    $juego = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$juego) {
        http_response_code(404);
        echo json_encode([
            "exito" => false,
            "mensaje" => "No se encontró el videojuego o no tienes permiso para editarlo."
        ]);
        exit;
    }

    echo json_encode([
        "exito" => true,
        "juego" => $juego
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $error) {
    error_log($error->getMessage());

    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo consultar el videojuego."
    ], JSON_UNESCAPED_UNICODE);
}
