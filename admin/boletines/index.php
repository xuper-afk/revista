<?php
$titulo = 'Boletines';
$seccionActiva = 'boletines';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$db = getConexion();
$boletines = $db->query("SELECT * FROM boletines ORDER BY fecha_publicacion DESC, id DESC")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Boletines</h1>
        <a href="crear.php" class="btn btn-primary">+ Nuevo boletín</a>
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
                    <th>Portada</th>
                    <th>N°</th>
                    <th>Resumen</th>
                    <th>Fecha</th>
                    <th>PDF</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($boletines)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No hay boletines.</td></tr>
                <?php else: foreach ($boletines as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td>
                            <?php if ($b['foto_portada']): ?>
                                <img src="<?= baseUrl('uploads/' . $b['foto_portada']) ?>" style="width:60px">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($b['numero_boletin']) ?></td>
                        <td><?= htmlspecialchars($b['resumen']) ?></td>
                        <td><?= htmlspecialchars($b['fecha_publicacion']) ?></td>
                        <td><a href="<?= baseUrl('uploads/' . $b['archivo_pdf']) ?>" target="_blank">Ver PDF</a></td>
                        <td class="text-end">
                            <a href="editar.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="eliminar.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>