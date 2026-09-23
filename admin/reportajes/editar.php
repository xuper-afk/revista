<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/upload.php';

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM reportajes WHERE id = ?");
$stmt->execute([$id]);
$rep = $stmt->fetch();

if (!$rep) {
    header('Location: index.php');
    exit;
}

$autores = $db->query("SELECT id, nombres, ap_paterno FROM autores ORDER BY nombres")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tituloRep   = trim($_POST['titulo'] ?? '');
        $resumen     = trim($_POST['resumen_corto'] ?? '');
        $desarrollo  = $_POST['desarrollo'] ?? '';
        $fecha       = $_POST['fecha_publicacion'] ?? date('Y-m-d');
        $esDestacado = isset($_POST['es_destacado']) ? 1 : 0;
        $autorId     = !empty($_POST['autor_id']) ? (int)$_POST['autor_id'] : null;

        $estado = $_POST['estado'] ?? 'publicado';
        if (!in_array($estado, ['borrador','publicado'])) $estado = 'publicado';

        if ($tituloRep === '' || trim(strip_tags($desarrollo)) === '') {
            throw new Exception('Título y desarrollo son obligatorios.');
        }

        $fotoPrincipal = $rep['foto_principal'];
        if (!empty($_FILES['foto_principal']['name'])) {
            $nueva = subirArchivo($_FILES['foto_principal'], 'reportajes');
            if ($nueva) $fotoPrincipal = $nueva;
        }

        $pdfAdjunto = $rep['pdf_adjunto'];
        if (!empty($_FILES['pdf_adjunto']['name'])) {
            $nuevoPdf = subirArchivo($_FILES['pdf_adjunto'], 'reportajes');
            if ($nuevoPdf) $pdfAdjunto = $nuevoPdf;
        }

        $stmt = $db->prepare("
            UPDATE reportajes SET
                titulo = ?, resumen_corto = ?, desarrollo = ?,
                foto_principal = ?, pdf_adjunto = ?,
                fecha_publicacion = ?, es_destacado = ?, estado = ?, autor_id = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $tituloRep, $resumen, $desarrollo,
            $fotoPrincipal, $pdfAdjunto,
            $fecha, $esDestacado, $estado, $autorId, $id
        ]);

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
                    $stmt2->execute([$id, $ruta, $i]);
                }
            }
        }

        if (!empty($_POST['eliminar_foto'])) {
            foreach ($_POST['eliminar_foto'] as $fotoId) {
                $stmt3 = $db->prepare("DELETE FROM reportajes_fotos WHERE id = ? AND reportaje_id = ?");
                $stmt3->execute([(int)$fotoId, $id]);
            }
        }

        header('Location: index.php?ok=1');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$stmtFotos = $db->prepare("SELECT * FROM reportajes_fotos WHERE reportaje_id = ? ORDER BY orden");
$stmtFotos->execute([$id]);
$fotosExtra = $stmtFotos->fetchAll();

$titulo = 'Editar reportaje';
$seccionActiva = 'reportajes';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3">
    <div class="col-12">
        <h1 class="h3">Editar reportaje</h1>
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
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($rep['titulo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Resumen corto</label>
                <textarea name="resumen_corto" class="form-control" rows="2" maxlength="500"><?= htmlspecialchars($rep['resumen_corto']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Desarrollo *</label>
                <textarea name="desarrollo" class="form-control" rows="10"><?= htmlspecialchars($rep['desarrollo']) ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto principal</label>
                    <?php if ($rep['foto_principal']): ?>
                        <div class="mb-2"><img src="<?= baseUrl('uploads/' . $rep['foto_principal']) ?>" style="max-width:150px"></div>
                    <?php endif; ?>
                    <input type="file" name="foto_principal" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PDF adjunto</label>
                    <?php if ($rep['pdf_adjunto']): ?>
                        <div class="mb-2"><a href="<?= baseUrl('uploads/' . $rep['pdf_adjunto']) ?>" target="_blank">Ver PDF actual</a></div>
                    <?php endif; ?>
                    <input type="file" name="pdf_adjunto" class="form-control" accept="application/pdf">
                </div>
            </div>
            <?php if ($fotosExtra): ?>
                <div class="mb-3">
                    <label class="form-label">Fotos adicionales actuales</label>
                    <div class="row">
                        <?php foreach ($fotosExtra as $f): ?>
                            <div class="col-md-3 mb-2">
                                <img src="<?= baseUrl('uploads/' . $f['url_foto']) ?>" class="img-fluid mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="eliminar_foto[]" value="<?= $f['id'] ?>" id="ef<?= $f['id'] ?>">
                                    <label class="form-check-label" for="ef<?= $f['id'] ?>">Eliminar</label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label">Agregar fotos adicionales</label>
                <input type="file" name="fotos_extra[]" class="form-control" accept="image/*" multiple>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Autor</label>
                    <select name="autor_id" class="form-select">
                        <option value="">— Redacción —</option>
                        <?php foreach ($autores as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $rep['autor_id'] == $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nombres'] . ' ' . $a['ap_paterno']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha_publicacion" class="form-control" value="<?= $rep['fecha_publicacion'] ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="publicado" <?= $rep['estado']=='publicado'?'selected':'' ?>>Publicado</option>
                        <option value="borrador"  <?= $rep['estado']=='borrador'?'selected':'' ?>>Borrador</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label d-block">Destacado</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="es_destacado" id="es_destacado" <?= $rep['es_destacado'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="es_destacado">Destacado</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Actualizar</button>
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