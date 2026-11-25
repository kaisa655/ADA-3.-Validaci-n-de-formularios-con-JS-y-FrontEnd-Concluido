<?php
require_once __DIR__.'/includes/funciones.php';
$busqueda=trim($_GET['q']??''); $categoria=trim($_GET['categoria']??'');
$sql="SELECT j.id,j.titulo,j.descripcion,j.portada,j.categoria,u.nombre AS autor FROM juegos j INNER JOIN usuarios u ON u.id=j.usuario_id WHERE 1=1";
$tipos=''; $params=[];
if($busqueda!==''){ $sql.=" AND (j.titulo LIKE ? OR j.descripcion LIKE ?)"; $like='%'.$busqueda.'%'; $params[]=$like;$params[]=$like;$tipos.='ss'; }
if($categoria!==''){ $sql.=" AND j.categoria=?";$params[]=$categoria;$tipos.='s'; }
$sql.=" ORDER BY j.fecha_publicacion DESC";
$q=$conexion->prepare($sql); if($params)$q->bind_param($tipos,...$params); $q->execute(); $juegos=$q->get_result()->fetch_all(MYSQLI_ASSOC);
$categorias=$conexion->query("SELECT DISTINCT categoria FROM juegos ORDER BY categoria")->fetch_all(MYSQLI_COLUMN);
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><p class="eyebrow">CATALOGO</p><h1>Explora videojuegos</h1><p class="text-secondary">Descubre juegos compartidos por la comunidad.</p>
<form class="row g-2 my-4" method="get"><div class="col-md-7"><input class="form-control" name="q" placeholder="Buscar por nombre o descripcion..." value="<?= e($busqueda) ?>"></div><div class="col-md-3"><select class="form-select" name="categoria"><option value="">Todas las categorias</option><?php foreach($categorias as $c): ?><option <?= $categoria===$c?'selected':'' ?> value="<?= e($c) ?>"><?= e($c) ?></option><?php endforeach; ?></select></div><div class="col-md-2"><button class="btn btn-primary w-100">Buscar</button></div></form>
<div class="row g-4"><?php if(!$juegos): ?><div class="col-12"><div class="info-card p-4">No encontramos juegos con esos criterios.</div></div><?php endif; ?>
<?php foreach($juegos as $j): ?><div class="col-md-6 col-lg-4"><article class="game-card h-100">
<img class="w-100" style="height:190px;object-fit:cover" src="<?= e(portada_url($j['portada'])) ?>" alt="Portada de <?= e($j['titulo']) ?>">
<div class="p-3"><span class="eyebrow"><?= e($j['categoria']) ?></span><h3><?= e($j['titulo']) ?></h3><p><?= e(mb_strimwidth($j['descripcion'],0,120,'…')) ?></p><small>Por <?= e($j['autor']) ?></small><div class="mt-3"><a class="btn btn-primary btn-sm" href="juego.php?id=<?= (int)$j['id'] ?>">Ver detalles</a></div></div>
</article></div><?php endforeach; ?></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
