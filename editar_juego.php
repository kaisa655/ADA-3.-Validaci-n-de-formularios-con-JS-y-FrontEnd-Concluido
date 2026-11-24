
<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/includes/conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Debes iniciar sesión."
    ]);
    exit;
}

// Aceptar únicamente solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Método no permitido."
    ]);
    exit;
}

$usuarioId = (int) $_SESSION["usuario_id"];
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

$titulo = trim($_POST["titulo"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$urlJuego = trim($_POST["urlJuego"] ?? "");

$rutaPortada = null;

// Validar ID y campos
if (
    !$id ||
    mb_strlen($titulo) < 2 ||
    mb_strlen($titulo) > 50 ||
    mb_strlen($descripcion) < 10 ||
    mb_strlen($descripcion) > 500 ||
    $categoria === "" ||
    !filter_var($urlJuego, FILTER_VALIDATE_URL) ||
    !in_array(parse_url($urlJuego, PHP_URL_SCHEME), ["http", "https"], true)
) {
    http_response_code(400);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Verifica los datos del formulario."
    ]);
    exit;
}

try {
    // Comprobar que el videojuego pertenezca al usuario
    $consulta = $conexion->prepare(
        "SELECT portada FROM juegos
         WHERE id = :id AND usuario_id = :usuario_id"
    );

    $consulta->execute([
        ":id" => $id,
        ":usuario_id" => $usuarioId
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

    $portadaAnterior = $juego["portada"];

    // Procesar una portada nueva si se seleccionó
    if (
        isset($_FILES["imagenJuego"]) &&
        $_FILES["imagenJuego"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {
        $imagen = $_FILES["imagenJuego"];

        if ($imagen["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("No se pudo recibir la imagen.");
        }

        if ($imagen["size"] > 1024 * 1024) {
            throw new Exception("La imagen no debe superar 1 MB.");
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipo = $finfo->file($imagen["tmp_name"]);

        $tiposPermitidos = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        ];

        if (!isset($tiposPermitidos[$tipo])) {
            throw new Exception("Formato de imagen no permitido.");
        }

        $carpeta = __DIR__ . "/uploads/portadas/";

        if (!is_dir($carpeta) || !is_writable($carpeta)) {
            throw new Exception("La carpeta de portadas no está disponible.");
        }

        $nombreArchivo = bin2hex(random_bytes(16))
            . "." . $tiposPermitidos[$tipo];

        if (!move_uploaded_file(
            $imagen["tmp_name"],
            $carpeta . $nombreArchivo
        )) {
            throw new Exception("No se pudo guardar la nueva portada.");
        }

        $rutaPortada = "uploads/portadas/" . $nombreArchivo;
    }

    // Actualizar los datos del videojuego
    $consulta = $conexion->prepare(
        "UPDATE juegos SET
            titulo = :titulo,
            descripcion = :descripcion,
            categoria = :categoria,
            url_juego = :url_juego,
            portada = :portada
         WHERE id = :id AND usuario_id = :usuario_id"
    );

    $consulta->execute([
        ":titulo" => $titulo,
        ":descripcion" => $descripcion,
        ":categoria" => $categoria,
        ":url_juego" => $urlJuego,
        ":portada" => $rutaPortada ?? $portadaAnterior,
        ":id" => $id,
        ":usuario_id" => $usuarioId
    ]);

    // Eliminar la portada anterior si se reemplazó
    if (
        $rutaPortada !== null &&
        !empty($portadaAnterior) &&
        strpos($portadaAnterior, "uploads/portadas/") === 0 &&
        is_file(__DIR__ . "/" . $portadaAnterior)
    ) {
        unlink(__DIR__ . "/" . $portadaAnterior);
    }

    echo json_encode([
        "exito" => true,
        "mensaje" => "El videojuego se actualizó correctamente."
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $error) {

    // Limpiar la nueva portada si ocurrió un error
    if (
        $rutaPortada !== null &&
        is_file(__DIR__ . "/" . $rutaPortada)
    ) {
        unlink(__DIR__ . "/" . $rutaPortada);
    }

    error_log($error->getMessage());

    http_response_code(500);

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo actualizar el videojuego."
    ], JSON_UNESCAPED_UNICODE);
}

