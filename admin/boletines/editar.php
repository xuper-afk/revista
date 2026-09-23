<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM boletines WHERE id = ?");
$stmt->execute([$id]);
$b = $stmt->fetch();
if (!$b) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $numero  = trim($_POST['numero_boletin'] ?? '');
        $resumen = trim($_POST['resumen'] ?? '');
        $fecha   = $_POST['fecha_publicacion'] ?? date('Y-m-d');
        $estado  = $_POST['estado'] ?? 'publicado';
        if (!in_array($estado, ['borrador','publicado'])) $estado = 'publicado';

        if ($numero === '') throw new Exception('El número de boletín es obligatorio.');

        $pdf = $b['archivo_pdf'];
        if (!empty($_FILES['archivo_pdf']['name'])) {
            $nuevo = subirArchivo($_FILES['archivo_pdf'], 'boletines');
            if ($nuevo) $pdf = $nuevo;
        }

        $portada = $b['foto_portada'];
        if (!empty($_FILES['foto_portada']['name'])) {
            $nueva = subirArchivo($_FILES['foto_portada'], 'boletines');
            if ($nueva) $portada = $nueva;
        }

        $stmt = $db->prepare("UPDATE boletines SET numero_boletin=?, resumen=?, foto_portada=?, archivo_pdf=?, estado=?, fecha_publicacion=? WHERE id=?");
        $stmt->execute([$numero, $resumen, $portada, $pdf, $estado, $fecha, $id]);

        header('Location: index.php?ok=1'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Editar boletín';
$seccionActiva = 'boletines';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Editar boletín</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="card"><div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Número de boletín *</label>
                <input type="text" name="numero_boletin" class="form-control" value="<?= htmlspecialchars($b['numero_boletin']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de publicación</label>
                <input type="date" name="fecha_publicacion" class="form-control" value="<?= $b['fecha_publicacion'] ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Resumen</label>
            <textarea name="resumen" class="form-control" rows="3"><?= htmlspecialchars($b['resumen']) ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Imagen de portada</label>
                <?php if ($b['foto_portada']): ?>
                    <div class="mb-2"><img src="<?= baseUrl('uploads/' . $b['foto_portada']) ?>" style="max-width:150px"></div>
                <?php endif; ?>
                <input type="file" name="foto_portada" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Archivo PDF</label>
                <div class="mb-2"><a href="<?= baseUrl('uploads/' . $b['archivo_pdf']) ?>" target="_blank">Ver PDF actual</a></div>
                <input type="file" name="archivo_pdf" class="form-control" accept="application/pdf">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="publicado" <?= $b['estado']=='publicado'?'selected':'' ?>>Publicado</option>
                <option value="borrador"  <?= $b['estado']=='borrador'?'selected':'' ?>>Borrador</option>
            </select>
        </div>
    </div></div>
    <div class="mb-3">
        <button class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>