<?php
require_once __DIR__ . '/../includes/auth.php';
requerirRol(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id === (int)$_SESSION['usuario_id']) {
    header('Location: index.php?err=self');
    exit;
}

$db = getConexion();
if ($id > 0) {
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: index.php?ok=1');
exit;