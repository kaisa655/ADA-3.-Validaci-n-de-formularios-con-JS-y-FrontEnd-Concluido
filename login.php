<?php
require_once __DIR__.'/includes/funciones.php';
if(usuario_autenticado()) redirigir('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 validar_csrf();
 $correo=trim($_POST['correo']??''); $clave=$_POST['contrasena']??'';
 $q=$conexion->prepare('SELECT id,nombre,contrasena FROM usuarios WHERE correo=?'); $q->bind_param('s',$correo); $q->execute(); $u=$q->get_result()->fetch_assoc();
 if($u && password_verify($clave,$u['contrasena'])){
  session_regenerate_id(true); $_SESSION['usuario_id']=(int)$u['id']; $_SESSION['usuario_nombre']=$u['nombre'];
  $_SESSION['flash']=['tipo'=>'success','texto'=>'Has iniciado sesion correctamente.']; redirigir('index.php');
 } else $error='Correo o contrasena incorrectos.';
}
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="info-card p-4 p-md-5">
 <p class="eyebrow">QUE BUENO VERTE</p><h1>Iniciar sesion</h1><p class="text-secondary">Accede a tu cuenta de PublicArcade.</p>
 <?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
 <form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
 <label class="form-label">Correo electronico</label><input class="form-control mb-3" type="email" name="correo" required value="<?= e($_POST['correo']??'') ?>">
 <label class="form-label">Contraseña</label><input class="form-control mb-4" type="password" name="contrasena" required>
 <button class="btn btn-primary w-100">Entrar</button></form>
 <p class="mt-3 mb-0">No tienes cuenta? <a class="text-link" href="registro.php">Registrate</a></p>
</div></div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
