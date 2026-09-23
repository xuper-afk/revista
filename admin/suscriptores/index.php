<?php
$titulo = 'Suscriptores';
$seccionActiva = 'suscriptores';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
requerirRol(['admin', 'editor']);

$db = getConexion();
$suscriptores = $db->query("SELECT * FROM suscriptores ORDER BY created_at DESC")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Suscriptores</h1>
        <a href="exportar.php" class="btn btn-outline-primary">Exportar CSV</a>
    </div>
</div>

<div class="card"><div class="card-body">
    <table class="table table-hover">
        <thead><tr><th>#</th><th>Nombre</th><th>Email</th><th>Fecha</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($suscriptores)): ?>
            <tr><td colspan="5" class="text-center text-muted">Aún no hay suscriptores.</td></tr>
        <?php else: foreach ($suscriptores as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['nombre'] ?? '—') ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                <td class="text-end">
                    <a href="eliminar.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar suscriptor?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>