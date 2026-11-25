<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/conexion.php';

function e(?string $valor): string {
    return htmlspecialchars($valor ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function redirigir(string $ruta): never {
    header('Location: ' . $ruta);
    exit;
}
function usuario_autenticado(): bool {
    return !empty($_SESSION['usuario_id']);
}
function requerir_login(): void {
    if (!usuario_autenticado()) {
        $_SESSION['flash'] = ['tipo' => 'warning', 'texto' => 'Inicia sesion para realizar esa accion.'];
        redirigir('login.php');
    }
}
function flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert alert-' . e($f['tipo']) . ' alert-dismissible fade show" role="alert">'
            . e($f['texto']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['flash']);
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function validar_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(403);
            exit('Solicitud no valida. Regresa e intentalo de nuevo.');
        }
    }
}
function portada_url(?string $nombre): string {
    return $nombre ? 'uploads/portadas/' . rawurlencode($nombre) : 'https://placehold.co/640x360/171725/b78cff?text=PublicArcade';
}
