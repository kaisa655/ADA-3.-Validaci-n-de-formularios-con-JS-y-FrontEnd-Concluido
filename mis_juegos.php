<?php
require_once __DIR__.'/includes/funciones.php';requerir_login();
$uid=(int)$_SESSION['usuario_id'];$q=$conexion->prepare('SELECT * FROM juegos WHERE usuario_id=? ORDER BY fecha_publicacion DESC');$q->bind_param('i',$uid);$q->execute();$juegos=$q->get_result()->fetch_all(MYSQLI_ASSOC);
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><p class="eyebrow">TU CONTENIDO</p><h1>Mis juegos</h1><p class="text-secondary">Administra los videojuegos que has publicado.</p>
<div class="row g-4"><?php if(!$juegos): ?><div class="col-12"><div class="info-card p-4">Todavía no has publicado juegos. <a class="text-link" href="publicar.php">Publicar el primero</a></div></div><?php endif; ?>
<?php foreach($juegos as $j): ?><div class="col-md-6 col-lg-4"><article class="game-card h-100"><img class="w-100" style="height:180px;object-fit:cover" src="<?= e(portada_url($j['portada'])) ?>" alt=""><div class="p-3"><h3><?= e($j['titulo']) ?></h3><p><?= e(mb_strimwidth($j['descripcion'],0,100,'…')) ?></p><a class="btn btn-primary btn-sm" href="juego.php?id=<?= (int)$j['id'] ?>">Ver</a> <a class="btn btn-outline-light btn-sm" href="editar.php?id=<?= (int)$j['id'] ?>">Editar</a>
<form class="d-inline" method="post" action="eliminar.php" onsubmit="return confirm('¿Seguro que quieres eliminar este juego?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$j['id'] ?>"><button class="btn btn-outline-danger btn-sm">Eliminar</button></form></div></article></div><?php endforeach; ?></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
