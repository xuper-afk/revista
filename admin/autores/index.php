<?php
$titulo = 'Autores';
$seccionActiva = 'autores';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$db = getConexion();
$autores = $db->query("SELECT * FROM autores ORDER BY nombres")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Autores</h1>
        <a href="crear.php" class="btn btn-primary">+ Nuevo autor</a>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operación realizada correctamente.</div>
<?php endif; ?>

<div class="card"><div class="card-body">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Nombre</th><th>Nickname</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($autores)): ?>
            <tr><td colspan="4" class="text-center text-muted">No hay autores.</td></tr>
        <?php else: foreach ($autores as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars(trim($a['nombres'] . ' ' . $a['ap_paterno'] . ' ' . $a['ap_materno'])) ?></td>
                <td>
                    <?php if ($a['es_nickname'] && $a['nickname']): ?>
                        <?= htmlspecialchars($a['nickname']) ?>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </td>
                <td class="text-end">
                    <a href="editar.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="eliminar.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>