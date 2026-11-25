<?php
require_once __DIR__ . '/includes/funciones.php';
if (usuario_autenticado()) redirigir('index.php');
$errores=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
 validar_csrf();
 $nombre=trim($_POST['nombre']??''); $correo=trim($_POST['correo']??''); $clave=$_POST['contrasena']??''; $confirmar=$_POST['confirmar']??'';
 if (mb_strlen($nombre)<3 || mb_strlen($nombre)>80) $errores[]='El nombre debe tener entre 3 y 80 caracteres.';
 if (!filter_var($correo,FILTER_VALIDATE_EMAIL)) $errores[]='Escribe un correo valido.';
 if (strlen($clave)<8) $errores[]='La contrasena debe tener al menos 8 caracteres.';
 if ($clave!==$confirmar) $errores[]='Las contrasenas no coinciden.';
 if (!$errores) {
  $q=$conexion->prepare('SELECT id FROM usuarios WHERE correo=?'); $q->bind_param('s',$correo); $q->execute();
  if ($q->get_result()->num_rows) $errores[]='Ese correo ya esta registrado.';
 }
 if (!$errores) {
  $hash=password_hash($clave,PASSWORD_DEFAULT);
  $q=$conexion->prepare('INSERT INTO usuarios(nombre,correo,contrasena) VALUES(?,?,?)'); $q->bind_param('sss',$nombre,$correo,$hash); $q->execute();
  $_SESSION['usuario_id']=$conexion->insert_id; $_SESSION['usuario_nombre']=$nombre;
  $_SESSION['flash']=['tipo'=>'success','texto'=>'Tu cuenta se creo correctamente! Ya puedes publicar juegos.'];
  redirigir('index.php');
 }
}
require __DIR__.'/includes/header.php';
?>
<section class="container py-5"><div class="row justify-content-center"><div class="col-md-7 col-lg-5">
 <div class="info-card p-4 p-md-5"><p class="eyebrow">UNETE A LA COMUNIDAD</p><h1>Crear cuenta</h1><p class="text-secondary">Registrate para publicar y administrar tus juegos.</p>
 <?php foreach($errores as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
 <form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label class="form-label">Nombre de usuario</label><input class="form-control mb-3" name="nombre" maxlength="80" required value="<?= e($_POST['nombre']??'') ?>">
  <label class="form-label">Correo electronico</label><input class="form-control mb-3" type="email" name="correo" required value="<?= e($_POST['correo']??'') ?>">
  <label class="form-label">Contrasena</label><input class="form-control mb-3" type="password" name="contrasena" minlength="8" required>
  <label class="form-label">Confirmar contrasena</label><input class="form-control mb-4" type="password" name="confirmar" minlength="8" required>
  <button class="btn btn-primary w-100">Registrarme</button>
 </form><p class="mt-3 mb-0">Ya tienes cuenta? <a class="text-link" href="login.php">Inicia sesion</a></p></div>
</div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
