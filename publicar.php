<?php
require_once __DIR__.'/includes/funciones.php'; requerir_login();
$errores=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 validar_csrf();
 $titulo=trim($_POST['titulo']??'');$descripcion=trim($_POST['descripcion']??'');$categoria=trim($_POST['categoria']??'');$url=trim($_POST['url_juego']??'');
 if(mb_strlen($titulo)<3||mb_strlen($titulo)>120)$errores[]='El titulo debe tener entre 3 y 120 caracteres.';
 if(mb_strlen($descripcion)<10||mb_strlen($descripcion)>3000)$errores[]='La descripcion debe tener entre 10 y 3000 caracteres.';
 if(mb_strlen($categoria)<2||mb_strlen($categoria)>50)$errores[]='Escribe una categoria valida.';
 if($url!==''&&!filter_var($url,FILTER_VALIDATE_URL))$errores[]='La URL del juego no es valida.';
 $portada=null;
 if(isset($_FILES['portada'])&&$_FILES['portada']['error']!==UPLOAD_ERR_NO_FILE){
  $f=$_FILES['portada'];
  if($f['error']!==UPLOAD_ERR_OK)$errores[]='No se pudo subir la imagen.';
  elseif($f['size']>MAX_UPLOAD_SIZE)$errores[]='La portada supera el limite de 5 MB.';
  else{
   $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
   $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime]??null;
   if(!$ext)$errores[]='La portada debe ser JPG, PNG o WEBP.';
   else{$portada=bin2hex(random_bytes(16)).'.'.$ext;}
  }
 }
 if(!$errores){
  if($portada){$dir=__DIR__.'/uploads/portadas';if(!is_dir($dir))mkdir($dir,0755,true);if(!move_uploaded_file($_FILES['portada']['tmp_name'],$dir.'/'.$portada))$errores[]='No se pudo guardar la portada.';}
  if(!$errores){
   $uid=(int)$_SESSION['usuario_id'];
   $q=$conexion->prepare('INSERT INTO juegos(usuario_id,titulo,descripcion,categoria,url_juego,portada) VALUES(?,?,?,?,?,?)');
   $q->bind_param('isssss',$uid,$titulo,$descripcion,$categoria,$url,$portada);$q->execute();
   $_SESSION['flash']=['tipo'=>'success','texto'=>'Tu juego se publico correctamente!'];redirigir('juego.php?id='.$conexion->insert_id);
  }
 }
}
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><div class="row justify-content-center"><div class="col-lg-8"><div class="info-card p-4 p-md-5">
<p class="eyebrow">COMPARTE CON LA COMUNIDAD</p><h1>Publicar un juego</h1><p class="text-secondary">Completa la información para agregar tu videojuego al catálogo.</p>
<?php foreach($errores as $er): ?><div class="alert alert-danger"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<label class="form-label">Nombre del juego</label><input class="form-control mb-3" name="titulo" maxlength="120" required value="<?= e($_POST['titulo']??'') ?>">
<label class="form-label">Descripcion</label><textarea class="form-control mb-3" name="descripcion" rows="5" maxlength="3000" required><?= e($_POST['descripcion']??'') ?></textarea>
<label class="form-label">Categoria</label><select class="form-select mb-3" name="categoria" required><option value="">Selecciona...</option><?php foreach(['Acción','Aventura','Arcade','Carreras','Deportes','Estrategia','Plataformas','Puzzle','RPG','Terror','Otro'] as $c): ?><option <?= ($_POST['categoria']??'')===$c?'selected':'' ?>><?= e($c) ?></option><?php endforeach; ?></select>
<label class="form-label">Enlace para jugar (opcional)</label><input class="form-control mb-3" type="url" name="url_juego" placeholder="https://..." value="<?= e($_POST['url_juego']??'') ?>">
<label class="form-label">Portada (JPG, PNG o WEBP; maximo 5 MB)</label><input class="form-control mb-4" type="file" name="portada" accept="image/jpeg,image/png,image/webp">
<button class="btn btn-primary">Publicar juego</button> <a class="btn btn-outline-light" href="explorar.php">Cancelar</a>
</form></div></div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
