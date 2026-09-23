<?php
require_once __DIR__ . '/../includes/auth.php';
requerirLogin();
$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $db->prepare("DELETE FROM suscriptores WHERE id = ?")->execute([$id]);
}
header('Location: index.php');
exit;