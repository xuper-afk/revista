<?php
$titulo = 'Dashboard';
$seccionActiva = 'dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$db = getConexion();

$totalReportajes = (int)$db->query("SELECT COUNT(*) FROM reportajes")->fetchColumn();
$totalPublicados = (int)$db->query("SELECT COUNT(*) FROM reportajes WHERE estado = 'publicado'")->fetchColumn();
$totalBorradores = (int)$db->query("SELECT COUNT(*) FROM reportajes WHERE estado = 'borrador'")->fetchColumn();
$totalNoticias   = (int)$db->query("SELECT COUNT(*) FROM noticias")->fetchColumn();
$totalBoletines  = (int)$db->query("SELECT COUNT(*) FROM boletines")->fetchColumn();
$totalPodcasts   = (int)$db->query("SELECT COUNT(*) FROM podcasts")->fetchColumn();
$totalVideos     = (int)$db->query("SELECT COUNT(*) FROM videos")->fetchColumn();
$totalAutores    = (int)$db->query("SELECT COUNT(*) FROM autores")->fetchColumn();
$totalUsuarios   = (int)$db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

$ultimosReportajes = $db->query("
    SELECT r.id, r.titulo, r.fecha_publicacion, r.estado, a.nombres AS autor_nombre, a.ap_paterno AS autor_ap
    FROM reportajes r
    LEFT JOIN autores a ON a.id = r.autor_id
    ORDER BY r.created_at DESC
    LIMIT 5
")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12">
        <h1 class="h3">Dashboard</h1>
        <p class="text-muted">Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>
    </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-muted">Reportajes</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary"><i class="align-middle" data-feather="file-text"></i></div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3"><?= $totalReportajes ?></h1>
                <div class="mb-0">
                    <span class="text-success"><?= $totalPublicados ?> publicados</span>
                    · <span class="text-warning"><?= $totalBorradores ?> borradores</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-muted">Noticias</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary"><i class="align-middle" data-feather="zap"></i></div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3"><?= $totalNoticias ?></h1>
                <div class="mb-0"><span class="text-muted">Notas externas</span></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-muted">Boletines</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary"><i class="align-middle" data-feather="book"></i></div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3"><?= $totalBoletines ?></h1>
                <div class="mb-0"><span class="text-muted">NTEP publicados</span></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-muted">Multimedia</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary"><i class="align-middle" data-feather="play-circle"></i></div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3"><?= $totalPodcasts + $totalVideos ?></h1>
                <div class="mb-0">
                    <span class="text-muted"><?= $totalPodcasts ?> podcasts · <?= $totalVideos ?> videos</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimos reportajes -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Últimos reportajes</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ultimosReportajes)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No hay reportajes aún.</td></tr>
                        <?php else: foreach ($ultimosReportajes as $r): ?>
                            <tr>
                                <td>
                                    <a href="reportajes/editar.php?id=<?= $r['id'] ?>">
                                        <?= htmlspecialchars($r['titulo']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($r['autor_nombre']): ?>
                                        <?= htmlspecialchars($r['autor_nombre'] . ' ' . $r['autor_ap']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Redacción</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($r['fecha_publicacion'])) ?></td>
                                <td>
                                    <?php if ($r['estado'] === 'publicado'): ?>
                                        <span class="badge bg-success">Publicado</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Borrador</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos y stats secundarias -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="reportajes/crear.php" class="btn btn-primary">+ Nuevo reportaje</a>
                    <a href="noticias/crear.php" class="btn btn-outline-primary">+ Nueva noticia</a>
                    <a href="boletines/crear.php" class="btn btn-outline-primary">+ Nuevo boletín</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Equipo</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Autores</span>
                    <strong><?= $totalAutores ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Usuarios</span>
                    <strong><?= $totalUsuarios ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>