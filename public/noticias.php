<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();
$noticias = $db->query("SELECT * FROM noticias ORDER BY fecha_publicacion DESC")->fetchAll();

$titulo = 'Noticias';
require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Noticias</h1>

<div class="row g-4">
    <?php foreach ($noticias as $n): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <?php if ($n['foto']): ?>
                    <img src="<?= urlUpload($n['foto']) ?>" class="card-img-top" style="height:180px;object-fit:cover">
                <?php endif; ?>
                <div class="card-body">
                    <h6><?= htmlspecialchars($n['titulo']) ?></h6>
                    <p class="small text-muted"><?= htmlspecialchars($n['fecha_publicacion']) ?></p>
                    <?php if ($n['link_externo']): ?>
                        <a href="<?= htmlspecialchars($n['link_externo']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Ver noticia completa</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>