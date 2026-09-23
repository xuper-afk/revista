<?php
require_once __DIR__ . '/../includes/auth.php';
requerirLogin();
$db = getConexion();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $stmt = $db->prepare("DELETE FROM autores WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        header('Location: index.php?err=1');
        exit;
    }
}
header('Location: index.php?ok=1');
exit;