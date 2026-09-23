<?php
require_once __DIR__ . '/includes/auth.php';

$db = getConexion();
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$error = '';
$ok = false;

$reset = null;
if ($token !== '') {
    $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND usado = 0 AND expira >= NOW() LIMIT 1");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();
}

if (!$reset) {
    $error = 'Token inválido o expirado.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (strlen($pass1) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($pass1 !== $pass2) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        $db->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?")
           ->execute([$hash, $reset['usuario_id']]);
        $db->prepare("UPDATE password_resets SET usado = 1 WHERE id = ?")
           ->execute([$reset['id']]);
        $ok = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="<?= baseUrl('admin/assets/css/app.css') ?>">
</head>
<body>
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center mt-4">
                        <h1 class="h2">Restablecer contraseña</h1>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-3">
                                <?php if ($ok): ?>
                                    <div class="alert alert-success">
                                        Contraseña actualizada. <a href="login.php">Inicia sesión</a>.
                                    </div>
                                <?php else: ?>
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php endif; ?>
                                    <?php if ($reset): ?>
                                        <form method="POST">
                                            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Nueva contraseña</label>
                                                <input type="password" name="password" class="form-control form-control-lg" required minlength="6">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Repetir contraseña</label>
                                                <input type="password" name="password2" class="form-control form-control-lg" required minlength="6">
                                            </div>
                                            <div class="d-grid gap-2 mt-3">
                                                <button class="btn btn-lg btn-primary">Cambiar contraseña</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="<?= baseUrl('admin/assets/js/app.js') ?>"></script>
</body>
</html>