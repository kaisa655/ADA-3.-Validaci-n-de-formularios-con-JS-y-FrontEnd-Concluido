<?php
session_start();

require_once "includes/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($nombre === "" || $correo === "" || $contrasena === "") {
        $mensaje = "Todos los campos son obligatorios.";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Ingresa un correo válido.";

    } elseif (strlen($contrasena) < 8) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres.";

    } else {

        // Comprobar si el correo ya está registrado
        $consulta = $conexion->prepare(
            "SELECT id FROM usuarios WHERE correo = :correo"
        );

        $consulta->execute(["correo" => $correo]);

        if ($consulta->fetch()) {
            $mensaje = "Este correo ya está registrado.";

        } else {

            // Proteger la contraseña
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Registrar como cliente
            $consulta = $conexion->prepare(
                "INSERT INTO usuarios (nombre, correo, contrasena, rol)
                 VALUES (:nombre, :correo, :contrasena, 'cliente')"
            );

            $consulta->execute([
                "nombre" => $nombre,
                "correo" => $correo,
                "contrasena" => $hash
            ]);

            header("Location: login.php?registro=exitoso");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | TechStore</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header class="encabezado">
    <h1>TechStore</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="login.php">Iniciar sesión</a>
    </nav>
</header>

<main class="contenedor">

    <section class="bienvenida">
        <h2>Crear una cuenta</h2>
        <p>Regístrate para comenzar a comprar.</p>

        <?php if ($mensaje !== ""): ?>
            <p class="mensaje-error">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form method="POST" class="formulario">

            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre"
                   maxlength="100" required>

            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo"
                   maxlength="150" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena"
                   minlength="8" required>

            <button type="submit">Registrarme</button>

        </form>

        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>.</p>
    </section>

</main>
</body>
</html>
