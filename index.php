<?php
require_once __DIR__ . '/includes/funciones.php';
$resultado = $conexion->query("SELECT j.id,j.titulo,j.descripcion,j.portada,j.categoria,u.nombre AS autor FROM juegos j INNER JOIN usuarios u ON u.id=j.usuario_id ORDER BY j.fecha_publicacion DESC LIMIT 6");
$juegos = $resultado->fetch_all(MYSQLI_ASSOC);
require __DIR__ . '/includes/header.php';
?>
<header class="hero"><div class="container">
 <p class="eyebrow">TU COMUNIDAD GAMER</p>
 <h1>Descubre. Comparte.<br><span>Juega.</span></h1>
 <p class="lead">Explora videojuegos creados y compartidos por la comunidad. Publica tus proyectos y encuentra tu proxima aventura.</p>
 <a class="btn btn-primary btn-lg me-2" href="explorar.php">Explorar juegos</a>
 <a class="btn btn-outline-light btn-lg" href="publicar.php">Publicar un juego</a>
</div></header>
<section class="container py-5">
 <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4"><div><p class="eyebrow mb-1">COMUNIDAD</p><h2 class="mb-0">Juegos recientes</h2></div><a class="text-link" href="explorar.php">Ver catalogo completo</a></div>
 <div class="row g-4">
 <?php if (!$juegos): ?><div class="col-12"><div class="info-card p-4"><h3>Aun no hay juegos publicados</h3><p class="mb-0">Se la primera persona en compartir uno.</p></div></div><?php endif; ?>
 <?php foreach ($juegos as $j): ?>
 <div class="col-md-6 col-lg-4"><article class="game-card h-100">
  <img class="w-100" style="height:190px;object-fit:cover" src="<?= e(portada_url($j['portada'])) ?>" alt="Portada de <?= e($j['titulo']) ?>">
  <div class="p-3"><span class="eyebrow"><?= e($j['categoria']) ?></span><h3><?= e($j['titulo']) ?></h3><p><?= e(mb_strimwidth($j['descripcion'],0,115,'…')) ?></p><small>Por <?= e($j['autor']) ?></small><div class="mt-3"><a class="btn btn-primary btn-sm" href="juego.php?id=<?= (int)$j['id'] ?>">Ver detalles</a></div></div>
 </article></div>
 <?php endforeach; ?>
 </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
