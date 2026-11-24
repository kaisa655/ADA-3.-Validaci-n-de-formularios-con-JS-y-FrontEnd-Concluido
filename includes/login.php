<?php
session_start();

require_once "includes/conexion.php";

$mensaje = "";

if (isset($_GET["registro"]) && $_GET["registro"] === "exitoso") {
    $mensaje = "Tu cuenta se creó correctamente. Ahora puedes iniciar sesión.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($correo === "" || $contrasena === "") {
        $mensaje = "Completa todos los campos.";

    } else {

        // Buscar al usuario por su correo
        $consulta = $conexion->prepare(
            "SELECT * FROM usuarios WHERE correo = :correo"
        );

        $consulta->execute(["correo" => $correo]);

        $usuario = $consulta->fetch();

        // Verificar la contraseña
        if ($usuario && password_verify($contrasena, $usuario["contrasena"])) {

            // Evitar reutilizar el identificador anterior de sesión
            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario"] = $usuario["nombre"];
            $_SESSION["correo"] = $usuario["correo"];
            $_SESSION["rol"] = $usuario["rol"];

            $_SESSION["carrito"] = [];

            // Redirigir según el tipo de usuario
            if ($usuario["rol"] === "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }

            exit;

        } else {
            $mensaje = "Correo o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | PublicArcade</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header class="encabezado">
    <h1>PublicArcade</h1>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="registro.php">Crear cuenta</a>
    </nav>
</header>

<main class="contenedor">

    <section class="bienvenida">
        <h2>Iniciar sesión</h2>
        <p>Ingresa tus datos para acceder a PublicArcade.</p>

        <?php if ($mensaje !== ""): ?>
            <p class="mensaje-error">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form method="POST" class="formulario">

            <label for="correo">Correo electrónico</label>
            <input
                type="email"
                id="correo"
                name="correo"
                maxlength="150"
                required
            >

            <label for="contrasena">Contraseña</label>
            <input
                type="password"
                id="contrasena"
                name="contrasena"
                required
            >

            <button type="submit">Entrar</button>

        </form>

        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
    </section>

</main>

</body>
</html>
