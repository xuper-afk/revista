<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

$db = getConexion();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $numero  = trim($_POST['numero_boletin'] ?? '');
        $resumen = trim($_POST['resumen'] ?? '');
        $fecha   = $_POST['fecha_publicacion'] ?? date('Y-m-d');
        $estado  = $_POST['estado'] ?? 'publicado';

        if (!in_array($estado, ['borrador','publicado'])) $estado = 'publicado';
        if ($numero === '') throw new Exception('El número de boletín es obligatorio.');
        if (empty($_FILES['archivo_pdf']['name'])) throw new Exception('El PDF es obligatorio.');

        $pdf     = subirArchivo($_FILES['archivo_pdf'], 'boletines');
        $portada = subirArchivo($_FILES['foto_portada'] ?? null, 'boletines');

        $stmt = $db->prepare("INSERT INTO boletines (numero_boletin, resumen, foto_portada, archivo_pdf, estado, fecha_publicacion, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numero, $resumen, $portada, $pdf, $estado, $fecha, $_SESSION['usuario_id']]);

        header('Location: index.php?ok=1');
        exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Nuevo boletín';
$seccionActiva = 'boletines';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Nuevo boletín</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="card"><div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Número de boletín *</label>
                <input type="text" name="numero_boletin" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de publicación</label>
                <input type="date" name="fecha_publicacion" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Resumen</label>
            <textarea name="resumen" class="form-control" rows="3" maxlength="500"></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Imagen de portada</label>
                <input type="file" name="foto_portada" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Archivo PDF *</label>
                <input type="file" name="archivo_pdf" class="form-control" accept="application/pdf" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="publicado">Publicado</option>
                <option value="borrador">Borrador</option>
            </select>
        </div>
    </div></div>
    <div class="mb-3">
        <button class="btn btn-primary">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>