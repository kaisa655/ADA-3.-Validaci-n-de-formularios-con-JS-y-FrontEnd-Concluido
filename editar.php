<?php
require_once __DIR__.'/includes/funciones.php';requerir_login();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);$uid=(int)$_SESSION['usuario_id'];
$q=$conexion->prepare('SELECT * FROM juegos WHERE id=? AND usuario_id=?');$q->bind_param('ii',$id,$uid);$q->execute();$j=$q->get_result()->fetch_assoc();
if(!$j){http_response_code(404);exit('No se encontró el juego o no tienes permiso para editarlo.');}
$errores=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 validar_csrf();$titulo=trim($_POST['titulo']??'');$descripcion=trim($_POST['descripcion']??'');$categoria=trim($_POST['categoria']??'');$url=trim($_POST['url_juego']??'');
 if(mb_strlen($titulo)<3||mb_strlen($titulo)>120)$errores[]='Titulo no valido.';
 if(mb_strlen($descripcion)<10||mb_strlen($descripcion)>3000)$errores[]='La descripcion debe tener entre 10 y 3000 caracteres.';
 if($url!==''&&!filter_var($url,FILTER_VALIDATE_URL))$errores[]='La URL no es valida.';
 $portada=$j['portada'];
 if(isset($_FILES['portada'])&&$_FILES['portada']['error']!==UPLOAD_ERR_NO_FILE){
  $f=$_FILES['portada'];$mime=$f['error']===UPLOAD_ERR_OK?(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']):'';
  $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime]??null;
  if(!$ext)$errores[]='La nueva portada debe ser JPG, PNG o WEBP.';
  elseif($f['size']>MAX_UPLOAD_SIZE)$errores[]='La portada supera 5 MB.';
  else{$portada=bin2hex(random_bytes(16)).'.'.$ext;}
 }
 if(!$errores){
  if($portada!==$j['portada']){if(!is_dir(__DIR__.'/uploads/portadas'))mkdir(__DIR__.'/uploads/portadas',0755,true);if(!move_uploaded_file($_FILES['portada']['tmp_name'],__DIR__.'/uploads/portadas/'.$portada))$errores[]='No se pudo guardar la imagen.';}
  if(!$errores){$u=$conexion->prepare('UPDATE juegos SET titulo=?,descripcion=?,categoria=?,url_juego=?,portada=? WHERE id=? AND usuario_id=?');$u->bind_param('sssssii',$titulo,$descripcion,$categoria,$url,$portada,$id,$uid);$u->execute();
   if($j['portada']&&$portada!==$j['portada']&&is_file(__DIR__.'/uploads/portadas/'.$j['portada']))@unlink(__DIR__.'/uploads/portadas/'.$j['portada']);
   $_SESSION['flash']=['tipo'=>'success','texto'=>'Los cambios se guardaron correctamente.'];redirigir('juego.php?id='.$id);}
 }
}
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><div class="row justify-content-center"><div class="col-lg-8"><div class="info-card p-4 p-md-5"><p class="eyebrow">ADMINISTRAR PUBLICACION</p><h1>Editar juego</h1>
<?php foreach($errores as $er): ?><div class="alert alert-danger"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<label class="form-label">Nombre</label><input class="form-control mb-3" name="titulo" required maxlength="120" value="<?= e($_POST['titulo']??$j['titulo']) ?>">
<label class="form-label">Descripcion</label><textarea class="form-control mb-3" name="descripcion" rows="5" required><?= e($_POST['descripcion']??$j['descripcion']) ?></textarea>
<label class="form-label">Categoria</label><input class="form-control mb-3" name="categoria" required value="<?= e($_POST['categoria']??$j['categoria']) ?>">
<label class="form-label">Enlace del juego</label><input class="form-control mb-3" type="url" name="url_juego" value="<?= e($_POST['url_juego']??$j['url_juego']) ?>">
<label class="form-label">Cambiar portada (opcional)</label><input class="form-control mb-4" type="file" name="portada" accept="image/jpeg,image/png,image/webp">
<button class="btn btn-primary">Guardar cambios</button> <a class="btn btn-outline-light" href="mis_juegos.php">Cancelar</a>
</form></div></div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
