<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();

$porPagina = 9;
$pagina = max(1, (int)($_GET['p'] ?? 1));
$offset = ($pagina - 1) * $porPagina;

$total = (int)$db->query("SELECT COUNT(*) FROM reportajes WHERE estado = 'publicado'")->fetchColumn();
$paginas = ceil($total / $porPagina);

$stmt = $db->prepare("SELECT * FROM reportajes WHERE estado = 'publicado' ORDER BY fecha_publicacion DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $porPagina, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$reportajes = $stmt->fetchAll();

$titulo = 'Reportajes';
$paginaActual = 'reportajes';
require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mb-4">Reportajes</h1>

<div class="row g-4">
    <?php foreach ($reportajes as $r): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <?php if ($r['foto_principal']): ?>
                    <img src="<?= urlUpload($r['foto_principal']) ?>" class="card-img-top" style="height:200px;object-fit:cover">
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

<?php if ($paginas > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $paginas; $i++): ?>
                <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                    <a class="page-link" href="?p=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>