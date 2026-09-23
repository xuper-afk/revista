<?php
require_once __DIR__ . '/auth.php';
requerirLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Panel' ?> - Revista</title>
    <link rel="stylesheet" href="<?= baseUrl('admin/assets/css/app.css') ?>">
    <link rel="stylesheet" href="<?= baseUrl('admin/assets/css/personalizado.css') ?>">
</head>
<body>
<div class="wrapper">
