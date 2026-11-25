<?php
require_once __DIR__.'/includes/funciones.php';requerir_login();validar_csrf();
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);$uid=(int)$_SESSION['usuario_id'];
$q=$conexion->prepare('SELECT portada FROM juegos WHERE id=? AND usuario_id=?');$q->bind_param('ii',$id,$uid);$q->execute();$j=$q->get_result()->fetch_assoc();
if($j){$d=$conexion->prepare('DELETE FROM juegos WHERE id=? AND usuario_id=?');$d->bind_param('ii',$id,$uid);$d->execute();if($j['portada']&&is_file(__DIR__.'/uploads/portadas/'.$j['portada']))@unlink(__DIR__.'/uploads/portadas/'.$j['portada']);$_SESSION['flash']=['tipo'=>'success','texto'=>'El juego fue eliminado.'];}
else $_SESSION['flash']=['tipo'=>'warning','texto'=>'No se encontró el juego o no tienes permiso para eliminarlo.'];
redirigir('mis_juegos.php');
