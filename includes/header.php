<?php
require_once __DIR__ . '/funciones.php';
$pagina = basename($_SERVER['PHP_SELF']);
$titulos = [
 'index.php'=>'Inicio','explorar.php'=>'Explorar','publicar.php'=>'Publicar',
 'registro.php'=>'Registro','login.php'=>'Iniciar sesion','acerca.php'=>'Acerca de',
 'editar.php'=>'Editar juego'
];
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>PublicArcade | <?= e($titulos[$pagina] ?? 'Videojuegos') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
 <div class="container">
  <a class="navbar-brand fw-bold" href="index.php"> Public<span>Arcade</span></a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="menu"><ul class="navbar-nav ms-auto">
   <li class="nav-item"><a class="nav-link <?= $pagina==='index.php'?'active':'' ?>" href="index.php">Inicio</a></li>
   <li class="nav-item"><a class="nav-link <?= $pagina==='explorar.php'?'active':'' ?>" href="explorar.php">Explorar juegos</a></li>
   <li class="nav-item"><a class="nav-link <?= $pagina==='publicar.php'?'active':'' ?>" href="publicar.php">Publicar juego</a></li>
   <?php if (usuario_autenticado()): ?>
    <li class="nav-item"><span class="nav-link">Hola, <?= e($_SESSION['usuario_nombre'] ?? 'usuario') ?></span></li>
    <li class="nav-item"><a class="nav-link" href="mis_juegos.php">Mis juegos</a></li>
    <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesion</a></li>
   <?php else: ?>
    <li class="nav-item"><a class="nav-link <?= $pagina==='registro.php'?'active':'' ?>" href="registro.php">Registrarse</a></li>
    <li class="nav-item"><a class="nav-link <?= $pagina==='login.php'?'active':'' ?>" href="login.php">Iniciar sesion</a></li>
   <?php endif; ?>
   <li class="nav-item"><a class="nav-link <?= $pagina==='acerca.php'?'active':'' ?>" href="acerca.php">Acerca de</a></li>
  </ul></div>
 </div>
</nav>
<main class="flex-grow-1">
<div class="container pt-3"><?php flash(); ?></div>
