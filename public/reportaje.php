<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();

$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("
    SELECT r.*, a.nombres AS autor_nombres, a.ap_paterno AS autor_ap, a.nickname, a.es_nickname
    FROM reportajes r
    LEFT JOIN autores a ON a.id = r.autor_id
    WHERE r.id = ? AND r.estado = 'publicado'
");
$stmt->execute([$id]);
$r = $stmt->fetch();

if (!$r) { header('Location: index.php'); exit; }

$stmtFotos = $db->prepare("SELECT * FROM reportajes_fotos WHERE reportaje_id = ? ORDER BY orden");
$stmtFotos->execute([$id]);
$fotos = $stmtFotos->fetchAll();

$titulo = $r['titulo'];
$descripcion = $r['resumen_corto'];
$imagenOG = $r['foto_principal'];
$paginaActual = 'reportajes';
require_once __DIR__ . '/includes/header.php';

if ($r['autor_id']) {
    $autorMostrar = ($r['es_nickname'] && $r['nickname'])
        ? $r['nickname']
        : trim($r['autor_nombres'] . ' ' . $r['autor_ap']);
} else {
    $autorMostrar = 'Redacción';
}
?>
<article class="mb-5">
    <h1 class="mb-3"><?= htmlspecialchars($r['titulo']) ?></h1>
    <p class="text-muted">
        Por <strong><?= htmlspecialchars($autorMostrar) ?></strong> ·
        <?= date('d/m/Y', strtotime($r['fecha_publicacion'])) ?>
    </p>

    <?php if ($r['resumen_corto']): ?>
        <p class="lead"><?= htmlspecialchars($r['resumen_corto']) ?></p>
    <?php endif; ?>

    <?php if ($r['foto_principal']): ?>
        <img src="<?= urlUpload($r['foto_principal']) ?>" class="img-fluid rounded my-3 w-100">
    <?php endif; ?>

    <div class="contenido">
        <?= $r['desarrollo'] ?>
    </div>

    <?php if ($fotos): ?>
        <div class="row g-3 mt-4">
            <?php foreach ($fotos as $f): ?>
                <div class="col-md-4">
                    <img src="<?= urlUpload($f['url_foto']) ?>" class="img-fluid rounded">
                    <?php if ($f['descripcion']): ?>
                        <p class="small text-muted mt-1"><?= htmlspecialchars($f['descripcion']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($r['pdf_adjunto']): ?>
        <div class="mt-4">
            <a href="<?= urlUpload($r['pdf_adjunto']) ?>" target="_blank" class="btn btn-outline-primary">
                Descargar PDF adjunto
            </a>
        </div>
    <?php endif; ?>
</article>

<a href="index.php" class="btn btn-secondary mb-5">← Volver al inicio</a>

<?php require_once __DIR__ . '/includes/footer.php'; ?>