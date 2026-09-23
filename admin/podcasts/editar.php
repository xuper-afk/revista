<?php
require_once __DIR__ . '/../includes/auth.php';

function convertirAEmbed($url) {
    if (preg_match('/src="([^"]+)"/', $url, $m)) $url = $m[1];
    if (preg_match('/youtube\.com\/watch\?v=([^\&]+)/', $url, $m)) return 'https://www.youtube.com/embed/' . $m[1];
    if (preg_match('/youtu\.be\/([^\?]+)/', $url, $m)) return 'https://www.youtube.com/embed/' . $m[1];
    if (preg_match('#youtube\.com/embed/([^\?]+)#', $url, $m)) return 'https://www.youtube.com/embed/' . $m[1];
    return $url;
}

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM podcasts WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tituloP = trim($_POST['titulo'] ?? '');
        $url     = convertirAEmbed(trim($_POST['url_embed'] ?? ''));
        $fecha   = $_POST['fecha_publicacion'] ?? date('Y-m-d');
        $estado  = $_POST['estado'] ?? 'publicado';
        if (!in_array($estado, ['borrador','publicado'])) $estado = 'publicado';
        if ($tituloP === '' || $url === '') throw new Exception('Todos los campos son obligatorios.');

        $stmt = $db->prepare("UPDATE podcasts SET titulo=?, url_embed=?, estado=?, fecha_publicacion=? WHERE id=?");
        $stmt->execute([$tituloP, $url, $estado, $fecha, $id]);

        header('Location: index.php?ok=1'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Editar podcast';
$seccionActiva = 'podcasts';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Editar podcast</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
    <div class="card"><div class="card-body">
        <div class="mb-3">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($p['titulo']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">URL del podcast *</label>
            <input type="text" name="url_embed" class="form-control" value="<?= htmlspecialchars($p['url_embed']) ?>" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de publicación</label>
                <input type="date" name="fecha_publicacion" class="form-control" value="<?= $p['fecha_publicacion'] ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="publicado" <?= $p['estado']=='publicado'?'selected':'' ?>>Publicado</option>
                    <option value="borrador"  <?= $p['estado']=='borrador'?'selected':'' ?>>Borrador</option>
                </select>
            </div>
        </div>
    </div></div>
    <div class="mb-3">
        <button class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>