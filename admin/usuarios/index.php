<?php
$titulo = 'Usuarios';
$seccionActiva = 'usuarios';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
requerirRol(['admin']);

$db = getConexion();
$usuarios = $db->query("SELECT * FROM usuarios ORDER BY id")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Usuarios</h1>
        <a href="crear.php" class="btn btn-primary">+ Nuevo usuario</a>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operación realizada correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['err']) && $_GET['err'] === 'self'): ?>
    <div class="alert alert-danger">No puedes eliminar tu propio usuario.</div>
<?php endif; ?>

<div class="card"><div class="card-body">
    <table class="table table-hover">
        <thead><tr><th>#</th><th>Nombre</th><th>Email</th><th>Rol</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nombres'] . ' ' . $u['ap_paterno']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge bg-info"><?= htmlspecialchars($u['rol']) ?></span></td>
                <td class="text-end">
                    <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                        <a href="eliminar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>