<?php
$titulo = 'Noticias';
$seccionActiva = 'noticias';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$db = getConexion();
$noticias = $db->query("SELECT * FROM noticias ORDER BY fecha_publicacion DESC, id DESC")->fetchAll();
?>

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3">Noticias</h1>
        <a href="crear.php" class="btn btn-primary">+ Nueva noticia</a>
    </div>
</div>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success">Operación realizada correctamente.</div>
<?php endif; ?>

<div class="card"><div class="card-body">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Foto</th><th>Título</th><th>Fecha</th><th>Estado</th><th>Link</th><th></th></tr>
        </thead>
        <tbody>
        <?php if (empty($noticias)): ?>
            <tr><td colspan="7" class="text-center text-muted">No hay noticias.</td></tr>
        <?php else: foreach ($noticias as $n): ?>
            <tr>
                <td><?= $n['id'] ?></td>
                <td>
                    <?php if ($n['foto']): ?>
                        <img src="<?= baseUrl('uploads/' . $n['foto']) ?>" style="width:60px">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($n['titulo']) ?></td>
                <td><?= htmlspecialchars($n['fecha_publicacion']) ?></td>
                <td>
                    <?php if ($n['estado'] === 'publicado'): ?>
                        <span class="badge bg-success">Publicado</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Borrador</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($n['link_externo']): ?>
                        <a href="<?= htmlspecialchars($n['link_externo']) ?>" target="_blank">Ver</a>
                    <?php endif; ?>
                </td>
                <td class="text-end">
                    <a href="editar.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="eliminar.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>