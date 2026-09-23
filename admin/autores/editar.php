<?php
require_once __DIR__ . '/../includes/auth.php';

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM autores WHERE id = ?");
$stmt->execute([$id]);
$a = $stmt->fetch();
if (!$a) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombres   = trim($_POST['nombres'] ?? '');
        $apPaterno = trim($_POST['ap_paterno'] ?? '');
        $apMaterno = trim($_POST['ap_materno'] ?? '');
        $nickname  = trim($_POST['nickname'] ?? '');
        $esNick    = isset($_POST['es_nickname']) ? 1 : 0;

        if ($nombres === '') throw new Exception('El nombre es obligatorio.');

        $stmt = $db->prepare("UPDATE autores SET nombres=?, ap_paterno=?, ap_materno=?, nickname=?, es_nickname=? WHERE id=?");
        $stmt->execute([$nombres, $apPaterno, $apMaterno, $nickname, $esNick, $id]);

        header('Location: index.php?ok=1'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Editar autor';
$seccionActiva = 'autores';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Editar autor</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
    <div class="card"><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($a['nombres']) ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido paterno</label>
                <input type="text" name="ap_paterno" class="form-control" value="<?= htmlspecialchars($a['ap_paterno']) ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" name="ap_materno" class="form-control" value="<?= htmlspecialchars($a['ap_materno']) ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="form-label">Nickname</label>
                <input type="text" name="nickname" class="form-control" value="<?= htmlspecialchars($a['nickname']) ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label d-block">Usar nickname</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="es_nickname" id="es_nickname" <?= $a['es_nickname'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="es_nickname">Mostrar nickname</label>
                </div>
            </div>
        </div>
    </div></div>
    <div class="mb-3">
        <button class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>