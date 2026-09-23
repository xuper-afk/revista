<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();

$destacado = $db->query("SELECT * FROM reportajes WHERE es_destacado = 1 AND estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT 1")->fetch();

$ultimosReportajes = $db->query("SELECT * FROM reportajes WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT 6")->fetchAll();
$ultimasNoticias   = $db->query("SELECT * FROM noticias WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT 4")->fetchAll();
$ultimosBoletines  = $db->query("SELECT * FROM boletines WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT 3")->fetchAll();
$ultimosPodcasts   = $db->query("SELECT * FROM podcasts WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT 2")->fetchAll();
$ultimosVideos     = $db->query("SELECT * FROM videos WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT 2")->fetchAll();

$titulo = 'Inicio';
$descripcion = 'Revista digital con reportajes, noticias, boletines, podcasts y videos';
$paginaActual = 'inicio';
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($destacado): ?>
    <section class="mb-5">
        <div class="row g-0 bg-light rounded overflow-hidden">
            <div class="col-md-7">
                <?php if ($destacado['foto_principal']): ?>
                    <img src="<?= urlUpload($destacado['foto_principal']) ?>" class="img-fluid w-100" style="height:400px;object-fit:cover">
                <?php else: ?>
                    <div style="height:400px;background:#eee;display:flex;align-items:center;justify-content:center;color:#999">Sin imagen</div>
                <?php endif; ?>
            </div>
            <div class="col-md-5 p-4 d-flex flex-column justify-content-center">
                <span class="badge bg-danger mb-2 align-self-start">DESTACADO</span>
                <h1 class="h3"><?= htmlspecialchars($destacado['titulo']) ?></h1>
                <p class="text-muted"><?= htmlspecialchars($destacado['resumen_corto']) ?></p>
                <a href="<?= urlPublic('reportaje.php?id=' . $destacado['id']) ?>" class="btn btn-primary align-self-start">Leer más</a>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="mb-5">
    <h2 class="h4 mb-3">Últimos reportajes</h2>
    <div class="row g-4">
        <?php if (empty($ultimosReportajes)): ?>
            <div class="col-12"><p class="text-muted">Aún no hay reportajes.</p></div>
        <?php else: foreach ($ultimosReportajes as $r): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <?php if ($r['foto_principal']): ?>
                        <img src="<?= urlUpload($r['foto_principal']) ?>" class="card-img-top" style="height:200px;object-fit:cover">
                    <?php else: ?>
                        <div style="height:200px;background:#eee;display:flex;align-items:center;justify-content:center;color:#999">Sin imagen</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($r['titulo']) ?></h5>
                        <p class="card-text small text-muted"><?= htmlspecialchars($r['resumen_corto']) ?></p>
                        <a href="<?= urlPublic('reportaje.php?id=' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">Leer</a>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<section class="mb-5">
    <h2 class="h4 mb-3">Noticias recientes</h2>
    <div class="row g-4">
        <?php if (empty($ultimasNoticias)): ?>
            <div class="col-12"><p class="text-muted">Aún no hay noticias.</p></div>
        <?php else: foreach ($ultimasNoticias as $n): ?>
            <div class="col-md-3">
                <div class="card h-100">
                    <?php if ($n['foto']): ?>
                        <img src="<?= urlUpload($n['foto']) ?>" class="card-img-top" style="height:150px;object-fit:cover">
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="card-title"><?= htmlspecialchars($n['titulo']) ?></h6>
                        <?php if ($n['link_externo']): ?>
                            <a href="<?= htmlspecialchars($n['link_externo']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Ver</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<section class="mb-5">
    <h2 class="h4 mb-3">Boletines NTEP</h2>
    <div class="row g-4">
        <?php if (empty($ultimosBoletines)): ?>
            <div class="col-12"><p class="text-muted">Aún no hay boletines.</p></div>
        <?php else: foreach ($ultimosBoletines as $b): ?>
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
        <?php endforeach; endif; ?>
    </div>
</section>

<section class="mb-5">
    <h2 class="h4 mb-3">Podcasts</h2>
    <div class="row g-4">
        <?php if (empty($ultimosPodcasts)): ?>
            <div class="col-12"><p class="text-muted">Aún no hay podcasts.</p></div>
        <?php else: foreach ($ultimosPodcasts as $p): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($p['titulo']) ?></h5>
                        <p class="small text-muted"><?= date('d/m/Y', strtotime($p['fecha_publicacion'])) ?></p>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= htmlspecialchars($p['url_embed']) ?>" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<section class="mb-5">
    <h2 class="h4 mb-3">Videos</h2>
    <div class="row g-4">
        <?php if (empty($ultimosVideos)): ?>
            <div class="col-12"><p class="text-muted">Aún no hay videos.</p></div>
        <?php else: foreach ($ultimosVideos as $v): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($v['titulo']) ?></h5>
                        <p class="small text-muted"><?= date('d/m/Y', strtotime($v['fecha_publicacion'])) ?></p>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= htmlspecialchars($v['url_embed']) ?>" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>