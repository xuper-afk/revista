<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

function requerirLogin() {
    if (!estaLogueado()) {
        header('Location: ' . baseUrl('admin/login.php'));
        exit;
    }
}

function requerirRol($roles = []) {
    requerirLogin();
    if (!in_array($_SESSION['usuario_rol'], $roles)) {
        http_response_code(403);
        die('Acceso denegado');
    }
}

function baseUrl($ruta = '') {
    return '/revista/' . ltrim($ruta, '/');
}