<?php
require_once __DIR__ . '/../includes/auth.php';
requerirRol(['admin']);

$db = getConexion();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombres   = trim($_POST['nombres'] ?? '');
        $apPaterno = trim($_POST['ap_paterno'] ?? '');
        $apMaterno = trim($_POST['ap_materno'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $rol       = $_POST['rol'] ?? 'redactor';

        if ($nombres === '' || $apPaterno === '' || $email === '' || $password === '') {
            throw new Exception('Completa todos los campos obligatorios.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email inválido.');
        }
        if (strlen($password) < 6) {
            throw new Exception('La contraseña debe tener al menos 6 caracteres.');
        }
        if (!in_array($rol, ['admin','editor','redactor'])) {
            $rol = 'redactor';
        }

        $check = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) throw new Exception('Ese email ya está registrado.');

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO usuarios (nombres, ap_paterno, ap_materno, email, password_hash, rol) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombres, $apPaterno, $apMaterno, $email, $hash, $rol]);

        header('Location: index.php?ok=1'); exit;
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$titulo = 'Nuevo usuario';
$seccionActiva = 'usuarios';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="row mb-3"><div class="col-12"><h1 class="h3">Nuevo usuario</h1></div></div>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST">
    <div class="card"><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombres" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido paterno *</label>
                <input type="text" name="ap_paterno" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" name="ap_materno" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña *</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select">
                <option value="redactor">Redactor</option>
                <option value="editor">Editor</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
    </div></div>
    <div class="mb-3">
        <button class="btn btn-primary">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>