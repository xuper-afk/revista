<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

$db = getConexion();
$autores = $db->query("SELECT id, nombres, ap_paterno FROM autores ORDER BY nombres")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tituloRep    = trim($_POST['titulo'] ?? '');
        $resumen      = trim($_POST['resumen_corto'] ?? '');
        $desarrollo   = $_POST['desarrollo'] ?? '';
        $fecha        = $_POST['fecha_publicacion'] ?? date('Y-m-d');
        $esDestacado  = isset($_POST['es_destacado']) ? 1 : 0;
        $autorId      = !empty($_POST['autor_id']) ? (int)$_POST['autor_id'] : null;

        $estado = $_POST['estado'] ?? 'publicado';
        if (!in_array($estado, ['borrador','publicado'])) $estado = 'publicado';

        if ($tituloRep === '' || trim(strip_tags($desarrollo)) === '') {
            throw new Exception('Título y desarrollo son obligatorios.');
        }

        $fotoPrincipal = subirArchivo($_FILES['foto_principal'] ?? null, 'reportajes');
        $pdfAdjunto    = subirArchivo($_FILES['pdf_adjunto'] ?? null, 'reportajes');

        $stmt = $db->prepare("
            INSERT INTO reportajes
            (titulo, resumen_corto, desarrollo, foto_principal, pdf_adjunto,
             fecha_publicacion, es_destacado, estado, autor_id, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $tituloRep, $resumen, $desarrollo, $fotoPrincipal, $pdfAdjunto,
            $fecha, $esDestacado, $estado, $autorId, $_SESSION['usuario_id']
        ]);

        $reportajeId = $db->lastInsertId();

        if (!empty($_FILES['fotos_extra']['name'][0])) {
            $total = count($_FILES['fotos_extra']['name']);
            for ($i = 0; $i < $total; $i++) {
                $archivo = [
                    'name'     => $_FILES['fotos_extra']['name'][$i],
                    'type'     => $_FILES['fotos_extra']['type'][$i],
                    'tmp_name' => $_FILES['fotos_extra']['tmp_name'][$i],
                    'error'    => $_FILES['fotos_extra']['error'][$i],
                    'size'     => $_FILES['fotos_extra']['size'][$i],
                ];
                $ruta = subirArchivo($archivo, 'reportajes');
                if ($ruta) {
                    $stmt2 = $db->prepare("INSERT INTO reportajes_fotos (reportaje_id, url_foto, orden) VALUES (?, ?, ?)");
                    $stmt2->execute([$reportajeId, $ruta, $i]);
                }
            }
        }

        header('Location: index.php?ok=1');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$titulo = 'Nuevo reportaje';
$seccionActiva = 'reportajes';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3">
    <div class="col-12">
        <h1 class="h3">Nuevo reportaje</h1>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" id="formReportaje">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Resumen corto</label>
                <textarea name="resumen_corto" class="form-control" rows="2" maxlength="500"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Desarrollo *</label>
                <textarea name="desarrollo" class="form-control" rows="10"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto principal</label>
                    <input type="file" name="foto_principal" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PDF adjunto</label>
                    <input type="file" name="pdf_adjunto" class="form-control" accept="application/pdf">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Fotos adicionales (varias)</label>
                <input type="file" name="fotos_extra[]" class="form-control" accept="image/*" multiple>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Autor</label>
                    <select name="autor_id" class="form-select">
                        <option value="">— Redacción —</option>
                        <?php foreach ($autores as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombres'] . ' ' . $a['ap_paterno']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha_publicacion" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="publicado">Publicado</option>
                        <option value="borrador">Borrador</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label d-block">Destacado</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="es_destacado" id="es_destacado">
                        <label class="form-check-label" for="es_destacado">Destacado</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: 'textarea[name="desarrollo"]',
    height: 400,
    menubar: false,
    plugins: 'lists link image code',
    toolbar: 'undo redo | bold italic | bullist numlist | link image | code'
});

document.getElementById('formReportaje').addEventListener('submit', function(e) {
    tinymce.triggerSave();

    var contenido = tinymce.get('mce_0') ? tinymce.get('mce_0').getContent({format: 'text'}).trim() : '';
    if (contenido === '') {
        e.preventDefault();
        alert('El desarrollo no puede estar vacío.');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>