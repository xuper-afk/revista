<?php
require_once __DIR__ . '/../includes/auth.php';
requerirRol(['admin']);

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$u = $stmt->fetch();
if (!$u) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombres   = trim($_POST['nombres'] ?? '');
        $apPaterno = trim($_POST['ap_paterno'] ?? '');
        $apMaterno = trim($_POST['ap_materno'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $rol       = $_POST['rol'] ?? 'redactor';
        $password  = $_POST['password'] ?? '';

        if ($nombres === '' || $apPaterno === '' || $email === '') {
            throw new Exception('Completa los campos obligatorios.');
        }
        if (!in_array($rol, ['admin','editor','redactor'])) {
            $rol = 'redactor';
        }

        if ($password !== '') {
            if (strlen($password) < 6) throw new Exception('La contraseña debe tener al menos 6 caracteres.');
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE usuarios SET nombres=?, ap_paterno=?, ap_materno=?, email=?, rol=?, password_hash=? WHERE id=?");
            $stmt->execute([$nombres, $apPaterno, $apMaterno, $email, $rol, $hash, $id]);
        } else {
            $stmt = $db->prepare("UPDATE usuarios SET nombres=?, ap_paterno=?, ap_materno=?, email=?, rol=? WHERE id=?");
            $stmt->execute([$nombres, $apPaterno, $apMaterno, $email, $rol, $id]);
        }

        header('Location: index.php?ok=1'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Editar usuario';
$seccionActiva = 'usuarios';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Editar usuario</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
    <div class="card"><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($u['nombres']) ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido paterno *</label>
                <input type="text" name="ap_paterno" class="form-control" value="<?= htmlspecialchars($u['ap_paterno']) ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" name="ap_materno" class="form-control" value="<?= htmlspecialchars($u['ap_materno']) ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nueva contraseña (vacío = no cambiar)</label>
                <input type="password" name="password" class="form-control" minlength="6">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select">
                <option value="redactor" <?= $u['rol']=='redactor'?'selected':'' ?>>Redactor</option>
                <option value="editor"   <?= $u['rol']=='editor'?'selected':'' ?>>Editor</option>
                <option value="admin"    <?= $u['rol']=='admin'?'selected':'' ?>>Administrador</option>
            </select>
        </div>
    </div></div>
    <div class="mb-3">
        <button class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>