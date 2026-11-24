
<?php
session_start();

// Vaciar los datos de la sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Regresar al inicio
header("Location: login.php");
exit;
?>
