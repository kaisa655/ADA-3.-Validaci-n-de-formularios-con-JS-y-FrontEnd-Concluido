<?php
require_once __DIR__.'/includes/funciones.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); if(!$id){http_response_code(404);exit('Juego no encontrado.');}
$q=$conexion->prepare('SELECT j.*,u.nombre AS autor FROM juegos j INNER JOIN usuarios u ON u.id=j.usuario_id WHERE j.id=?');$q->bind_param('i',$id);$q->execute();$j=$q->get_result()->fetch_assoc();
if(!$j){http_response_code(404);exit('Juego no encontrado.');}
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><div class="row g-4"><div class="col-lg-7"><img class="w-100 rounded-4" style="max-height:430px;object-fit:cover" src="<?= e(portada_url($j['portada'])) ?>" alt="Portada de <?= e($j['titulo']) ?>"></div>
<div class="col-lg-5"><p class="eyebrow"><?= e($j['categoria']) ?></p><h1><?= e($j['titulo']) ?></h1><p class="text-secondary">Publicado por <?= e($j['autor']) ?> · <?= e(date('d/m/Y',strtotime($j['fecha_publicacion']))) ?></p>
<div class="info-card p-3 my-3"><p><?= nl2br(e($j['descripcion'])) ?></p></div>
<?php if($j['url_juego']): ?><a class="btn btn-primary" target="_blank" rel="noopener noreferrer" href="<?= e($j['url_juego']) ?>">Abrir juego</a><?php endif; ?>
<?php if(usuario_autenticado()&&(int)$_SESSION['usuario_id']===(int)$j['usuario_id']): ?><a class="btn btn-outline-light" href="editar.php?id=<?= (int)$j['id'] ?>">Editar</a><?php endif; ?>
<a class="btn btn-outline-light" href="explorar.php">Volver</a></div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
