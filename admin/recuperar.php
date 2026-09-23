<?php
require_once __DIR__ . '/includes/auth.php';

$db = getConexion();
$mensaje = '';
$enlace = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u) {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', time() + 3600); // 1 hora

        $stmt = $db->prepare("INSERT INTO password_resets (usuario_id, token, expira) VALUES (?, ?, ?)");
        $stmt->execute([$u['id'], $token, $expira]);

        $enlace = baseUrl('admin/restablecer.php?token=' . $token);
        $mensaje = 'Enlace generado (cópialo y ábrelo):';
    } else {
        $mensaje = 'Si el correo existe, se generará el enlace.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="<?= baseUrl('admin/assets/css/app.css') ?>">
</head>
<body>
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center mt-4">
                        <h1 class="h2">Recuperar contraseña - Revista Digital PJ</h1>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-3">
                                <?php if ($mensaje): ?>
                                    <div class="alert alert-info">
                                        <?= htmlspecialchars($mensaje) ?>
                                        <?php if ($enlace): ?>
                                            <div class="mt-2">
                                                <a href="<?= $enlace ?>"><?= $enlace ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Tu email</label>
                                        <input type="email" name="email" class="form-control form-control-lg" required>
                                    </div>
                                    <div class="d-grid gap-2 mt-3">
                                        <button class="btn btn-lg btn-primary">Generar enlace</button>
                                    </div>
                                </form>
                                <div class="text-center mt-3">
                                    <a href="login.php">Volver al login</a>
                                </div>
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