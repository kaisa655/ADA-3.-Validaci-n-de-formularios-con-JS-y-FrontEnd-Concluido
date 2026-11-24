<?php

$servidor = "sql201.infinityfree.com";
$baseDatos = "if0_43469109_PublicArcade";
$usuario = "if0_43469109";
$contrasena = "3RjF8P4KsCGbCg";

try {
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $error) {
    error_log($error->getMessage());
    die("Error al conectar con la base de datos.");
}
?>