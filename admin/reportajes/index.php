<?php
$titulo = 'Reportajes';
$seccionActiva = 'reportajes';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$db = getConexion();
$reportajes = $db->query("
    SELECT r.*, a.nombres AS autor_nombre, a.ap_paterno AS autor_ap
    FROM reportajes r
    LEFT JOIN autores a ON a.id = r.autor_id
    ORDER BY r.fecha_publicacion DESC, r.id DESC
")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Reportajes</h1>
        <a href="crear.php" class="btn btn-primary">+ Nuevo reportaje</a>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operación realizada correctamente.</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Destacado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reportajes)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No hay reportajes.</td></tr>
                <?php else: foreach ($reportajes as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['titulo']) ?></td>
                        <td>
                            <?php if ($r['autor_id']): ?>
                                <?= htmlspecialchars($r['autor_nombre'] . ' ' . $r['autor_ap']) ?>
                            <?php else: ?>
                                <span class="text-muted">Redacción</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r['fecha_publicacion']) ?></td>
                        <td>
                            <?php if ($r['estado'] === 'publicado'): ?>
                                <span class="badge bg-success">Publicado</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Borrador</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($r['es_destacado']): ?>
                                <span class="badge bg-success">Sí</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="editar.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="eliminar.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar este reportaje?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>