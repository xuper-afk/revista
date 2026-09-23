<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->execute([$id]);
$n = $stmt->fetch();
if (!$n) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tituloN = trim($_POST['titulo'] ?? '');
        $link    = trim($_POST['link_externo'] ?? '');
        $fecha   = $_POST['fecha_publicacion'] ?? date('Y-m-d');
        $estado  = $_POST['estado'] ?? 'publicado';

        if (!in_array($estado, ['borrador','publicado'])) $estado = 'publicado';
        if ($tituloN === '') throw new Exception('El título es obligatorio.');

        $foto = $n['foto'];
        if (!empty($_FILES['foto']['name'])) {
            $nueva = subirArchivo($_FILES['foto'], 'noticias');
            if ($nueva) $foto = $nueva;
        }

        $stmt = $db->prepare("UPDATE noticias SET titulo=?, foto=?, link_externo=?, estado=?, fecha_publicacion=? WHERE id=?");
        $stmt->execute([$tituloN, $foto, $link, $estado, $fecha, $id]);

        header('Location: index.php?ok=1'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Editar noticia';
$seccionActiva = 'noticias';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Editar noticia</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="card"><div class="card-body">
        <div class="mb-3">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($n['titulo']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto</label>
            <?php if ($n['foto']): ?>
                <div class="mb-2"><img src="<?= baseUrl('uploads/' . $n['foto']) ?>" style="max-width:150px"></div>
            <?php endif; ?>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Link externo</label>
            <input type="url" name="link_externo" class="form-control" value="<?= htmlspecialchars($n['link_externo']) ?>">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de publicación</label>
                <input type="date" name="fecha_publicacion" class="form-control" value="<?= $n['fecha_publicacion'] ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="publicado" <?= $n['estado']=='publicado'?'selected':'' ?>>Publicado</option>
                    <option value="borrador"  <?= $n['estado']=='borrador'?'selected':'' ?>>Borrador</option>
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