<?php
require_once __DIR__ . '/../../config/database.php';

function urlPublic($ruta = '') {
    return '/revista/public/' . ltrim($ruta, '/');
}
function urlUpload($ruta = '') {
    return '/revista/uploads/' . ltrim($ruta, '/');
}

$urlActual = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($titulo ?? 'Revista Digital') ?></title>
    <meta name="description" content="<?= htmlspecialchars($descripcion ?? 'Revista digital con reportajes, noticias y boletines') ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($urlActual) ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($titulo ?? 'Revista Digital') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($descripcion ?? 'Revista digital') ?>">
    <meta property="og:type" content="<?= ($paginaActual ?? '') === 'reportajes' && isset($r) ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= htmlspecialchars($urlActual) ?>">
    <?php if (!empty($imagenOG)): ?>
        <meta property="og:image" content="<?= 'http://' . $_SERVER['HTTP_HOST'] . urlUpload($imagenOG) ?>">
    <?php endif; ?>

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($titulo ?? 'Revista Digital') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($descripcion ?? 'Revista digital') ?>">
    <?php if (!empty($imagenOG)): ?>
        <meta name="twitter:image" content="<?= 'http://' . $_SERVER['HTTP_HOST'] . urlUpload($imagenOG) ?>">
    <?php endif; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= urlPublic('assets/css/estilo.css') ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= urlPublic('index.php') ?>">Revista Digital PJ</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link <?= ($paginaActual ?? '') === 'inicio' ? 'active' : '' ?>" href="<?= urlPublic('index.php') ?>">Inicio</a></li>
                <li class="nav-item"><a class="nav-link <?= ($paginaActual ?? '') === 'reportajes' ? 'active' : '' ?>" href="<?= urlPublic('reportajes.php') ?>">Reportajes</a></li>
                <li class="nav-item"><a class="nav-link <?= ($paginaActual ?? '') === 'noticias' ? 'active' : '' ?>" href="<?= urlPublic('noticias.php') ?>">Noticias</a></li>
                <li class="nav-item"><a class="nav-link <?= ($paginaActual ?? '') === 'boletines' ? 'active' : '' ?>" href="<?= urlPublic('boletines.php') ?>">Boletines</a></li>
                <li class="nav-item"><a class="nav-link <?= ($paginaActual ?? '') === 'podcasts' ? 'active' : '' ?>" href="<?= urlPublic('podcasts.php') ?>">Podcasts</a></li>
                <li class="nav-item"><a class="nav-link <?= ($paginaActual ?? '') === 'videos' ? 'active' : '' ?>" href="<?= urlPublic('videos.php') ?>">Videos</a></li>
                <form class="d-flex ms-3" method="GET" action="<?= urlPublic('buscar.php') ?>">
                <input class="form-control form-control-sm" type="search" name="q" placeholder="Buscar..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </form>
            </ul>
            <div class="ms-3 d-flex gap-2">
             <a href="<?= urlPublic('suscribirse.php') ?>" class="btn btn-danger btn-sm">Suscribirse</a>
            </div>
        </div>
    </div>
</nav>

<main class="container my-4">