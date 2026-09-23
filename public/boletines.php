<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();
$boletines = $db->query("SELECT * FROM boletines ORDER BY fecha_publicacion DESC")->fetchAll();

$titulo = 'Boletines NTEP';
require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Boletines NTEP</h1>

<div class="row g-4">
    <?php foreach ($boletines as $b): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <?php if ($b['foto_portada']): ?>
                    <img src="<?= urlUpload($b['foto_portada']) ?>" class="card-img-top" style="height:250px;object-fit:cover">
                <?php endif; ?>
                <div class="card-body">
                    <h5><?= htmlspecialchars($b['numero_boletin']) ?></h5>
                    <p class="small text-muted"><?= htmlspecialchars($b['fecha_publicacion']) ?></p>
                    <p><?= htmlspecialchars($b['resumen']) ?></p>
                    <a href="<?= urlUpload($b['archivo_pdf']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver PDF</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>