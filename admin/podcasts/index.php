<?php
$titulo = 'Podcasts';
$seccionActiva = 'podcasts';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$db = getConexion();
$items = $db->query("SELECT * FROM podcasts ORDER BY fecha_publicacion DESC, id DESC")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Podcasts</h1>
        <a href="crear.php" class="btn btn-primary">+ Nuevo podcast</a>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operación realizada correctamente.</div>
<?php endif; ?>

<div class="card"><div class="card-body">
    <table class="table table-hover">
        <thead><tr><th>#</th><th>Título</th><th>Fecha</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="5" class="text-center text-muted">No hay podcasts.</td></tr>
        <?php else: foreach ($items as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['titulo']) ?></td>
                <td><?= htmlspecialchars($p['fecha_publicacion']) ?></td>
                <td>
                    <?php if ($p['estado'] === 'publicado'): ?>
                        <span class="badge bg-success">Publicado</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Borrador</span>
                    <?php endif; ?>
                </td>
                <td class="text-end">
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>