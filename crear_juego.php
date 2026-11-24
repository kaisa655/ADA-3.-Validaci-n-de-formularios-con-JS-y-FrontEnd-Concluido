
<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/includes/conexion.php";

// Comprobar que el usuario inició sesión
if (!isset($_SESSION["usuario_id"])) {
    http_response_code(401);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Debes iniciar sesión para publicar un juego."
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

// Recibir los datos del formulario
$usuarioId = (int) $_SESSION["usuario_id"];
$titulo = trim($_POST["titulo"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$urlJuego = trim($_POST["urlJuego"] ?? "");

$rutaPortada = null;

// Validar los campos
if (
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
    // Procesar la portada, si se seleccionó una
    if (isset($_FILES["imagenJuego"]) &&
        $_FILES["imagenJuego"]["error"] !== UPLOAD_ERR_NO_FILE) {

        $imagen = $_FILES["imagenJuego"];

        if ($imagen["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("No se pudo recibir la imagen.");
        }

        if ($imagen["size"] > 1024 * 1024) {
            throw new Exception("La imagen no debe superar 1 MB.");
        }

        // Comprobar el tipo real del archivo
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
            throw new Exception("La carpeta de portadas no está disponible para escritura.");
        }

        $nombreArchivo = bin2hex(random_bytes(16))
            . "." . $tiposPermitidos[$tipo];

        if (!move_uploaded_file(
            $imagen["tmp_name"],
            $carpeta . $nombreArchivo
        )) {
            throw new Exception("No se pudo guardar la portada.");
        }

        $rutaPortada = "uploads/portadas/" . $nombreArchivo;
    }

    // Insertar el juego en MySQL
    $consulta = $conexion->prepare(
        "INSERT INTO juegos
        (usuario_id, titulo, descripcion, categoria, url_juego, portada)
        VALUES
        (:usuario_id, :titulo, :descripcion, :categoria, :url_juego, :portada)"
    );

    $consulta->execute([
        ":usuario_id" => $usuarioId,
        ":titulo" => $titulo,
        ":descripcion" => $descripcion,
        ":categoria" => $categoria,
        ":url_juego" => $urlJuego,
        ":portada" => $rutaPortada
    ]);

    echo json_encode([
        "exito" => true,
        "mensaje" => "¡Tu juego se publicó correctamente!",
        "id" => $conexion->lastInsertId()
    ]);

} catch (Throwable $error) {

    // Si hubo un error después de subir la portada,
    // eliminar el archivo para no dejar archivos huérfanos.
    if ($rutaPortada !== null &&
        file_exists(__DIR__ . "/" . $rutaPortada)) {
        unlink(__DIR__ . "/" . $rutaPortada);
    }

    error_log($error->getMessage());

    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo publicar el juego. Inténtalo nuevamente."
    ]);
}
