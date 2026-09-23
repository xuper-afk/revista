<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();

$q = trim($_GET['q'] ?? '');
$resultadosReportajes = [];
$resultadosNoticias = [];
$resultadosBoletines = [];

if ($q !== '') {
    $param = '%' . $q . '%';

    $stmt = $db->prepare("SELECT id, titulo, resumen_corto, foto_principal FROM reportajes WHERE estado='publicado' AND (titulo LIKE ? OR resumen_corto LIKE ? OR desarrollo LIKE ?) ORDER BY fecha_publicacion DESC LIMIT 20");
    $stmt->execute([$param, $param, $param]);
    $resultadosReportajes = $stmt->fetchAll();

    $stmt = $db->prepare("SELECT id, titulo, foto FROM noticias WHERE estado='publicado' AND titulo LIKE ? ORDER BY fecha_publicacion DESC LIMIT 20");
    $stmt->execute([$param]);
    $resultadosNoticias = $stmt->fetchAll();

    $stmt = $db->prepare("SELECT id, numero_boletin, resumen, foto_portada, archivo_pdf FROM boletines WHERE estado='publicado' AND (numero_boletin LIKE ? OR resumen LIKE ?) ORDER BY fecha_publicacion DESC LIMIT 20");
    $stmt->execute([$param, $param]);
    $resultadosBoletines = $stmt->fetchAll();
}

$titulo = $q !== '' ? 'Búsqueda: ' . $q : 'Buscar';
$paginaActual = '';
require_once __DIR__ . '/includes/header.php';

$totalResultados = count($resultadosReportajes) + count($resultadosNoticias) + count($resultadosBoletines);
?>

<h1 class="mb-4">Buscar</h1>

<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Buscar reportajes, noticias, boletines..." value="<?= htmlspecialchars($q) ?>" autofocus>
        <button class="btn btn-primary">Buscar</button>
    </div>
</form>

<?php if ($q !== ''): ?>
    <p class="text-muted"><?= $totalResultados ?> resultado(s) para "<strong><?= htmlspecialchars($q) ?></strong>"</p>

    <?php if ($totalResultados === 0): ?>
        <div class="alert alert-info">No se encontraron resultados.</div>
    <?php endif; ?>

    <?php if ($resultadosReportajes): ?>
        <h2 class="h4 mt-4">Reportajes</h2>
        <div class="row g-4">
            <?php foreach ($resultadosReportajes as $r): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if ($r['foto_principal']): ?>
                            <img src="<?= urlUpload($r['foto_principal']) ?>" class="card-img-top" style="height:180px;object-fit:cover">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5><?= htmlspecialchars($r['titulo']) ?></h5>
                            <p class="small text-muted"><?= htmlspecialchars($r['resumen_corto']) ?></p>
                            <a href="reportaje.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">Leer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($resultadosNoticias): ?>
        <h2 class="h4 mt-4">Noticias</h2>
        <div class="row g-4">
            <?php foreach ($resultadosNoticias as $n): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if ($n['foto']): ?>
                            <img src="<?= urlUpload($n['foto']) ?>" class="card-img-top" style="height:180px;object-fit:cover">
                        <?php endif; ?>
                        <div class="card-body">
                            <h6><?= htmlspecialchars($n['titulo']) ?></h6>
                            <a href="noticias.php" class="btn btn-sm btn-outline-primary">Ver noticias</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($resultadosBoletines): ?>
        <h2 class="h4 mt-4">Boletines</h2>
        <div class="row g-4">
            <?php foreach ($resultadosBoletines as $b): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if ($b['foto_portada']): ?>
                            <img src="<?= urlUpload($b['foto_portada']) ?>" class="card-img-top" style="height:200px;object-fit:cover">
                        <?php endif; ?>
                        <div class="card-body">
                            <h6><?= htmlspecialchars($b['numero_boletin']) ?></h6>
                            <p class="small text-muted"><?= htmlspecialchars($b['resumen']) ?></p>
                            <a href="<?= urlUpload($b['archivo_pdf']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver PDF</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>