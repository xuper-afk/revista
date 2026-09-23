<?php
require_once __DIR__ . '/../includes/auth.php';
requerirLogin();

$db = getConexion();
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $db->prepare("DELETE FROM reportajes WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php?ok=1');
exit;