<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();
$items = $db->query("SELECT * FROM podcasts WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC")->fetchAll();

$titulo = 'Podcasts';
$paginaActual = 'podcasts';
require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Podcasts</h1>

<div class="row g-4">
    <?php if (empty($items)): ?>
        <div class="col-12"><p class="text-muted">No hay podcasts publicados aún.</p></div>
    <?php else: foreach ($items as $p): ?>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= htmlspecialchars($p['titulo']) ?></h5>
                    <p class="small text-muted"><?= date('d/m/Y', strtotime($p['fecha_publicacion'])) ?></p>
                    <div class="ratio ratio-16x9 mb-2">
                        <iframe src="<?= htmlspecialchars($p['url_embed']) ?>" allowfullscreen loading="lazy" style="border-radius:6px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>