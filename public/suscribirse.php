<?php
require_once __DIR__ . '/../config/database.php';
$db = getConexion();

$titulo = 'Suscribirse';
$paginaActual = '';
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ingresa un correo válido.';
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO suscriptores (nombre, email) VALUES (?, ?)");
            $stmt->execute([$nombre, $email]);
            $mensaje = '¡Gracias por suscribirte! Recibirás nuestras novedades.';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = 'Ese correo ya está suscrito.';
            } else {
                $error = 'Error al suscribir. Intenta de nuevo.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-3">Suscríbete</h1>
        <p class="lead">Recibe nuestros reportajes y boletines directamente en tu correo.</p>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nombre (opcional)</label>
                    <input type="text" name="nombre" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Suscribirme</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>