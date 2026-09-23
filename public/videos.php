<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();
$items = $db->query("SELECT * FROM videos WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC")->fetchAll();

$titulo = 'Videos';
$paginaActual = 'videos';
require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Videos</h1>

<div class="row g-4">
    <?php if (empty($items)): ?>
        <div class="col-12"><p class="text-muted">No hay videos publicados aún.</p></div>
    <?php else: foreach ($items as $v): ?>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= htmlspecialchars($v['titulo']) ?></h5>
                    <p class="small text-muted"><?= date('d/m/Y', strtotime($v['fecha_publicacion'])) ?></p>
                    <div class="ratio ratio-16x9">
                        <iframe src="<?= htmlspecialchars($v['url_embed']) ?>" allowfullscreen loading="lazy" style="border-radius:6px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>