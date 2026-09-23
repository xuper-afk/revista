<?php
require_once __DIR__ . '/../includes/auth.php';
requerirLogin();

$db = getConexion();
$suscriptores = $db->query("SELECT nombre, email, created_at FROM suscriptores ORDER BY created_at DESC")->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=suscriptores.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['Nombre', 'Email', 'Fecha']);
foreach ($suscriptores as $s) {
    fputcsv($out, [$s['nombre'], $s['email'], $s['created_at']]);
}
fclose($out);
exit;