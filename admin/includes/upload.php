<?php
function subirArchivo($archivo, $carpeta, $tiposPermitidos = ['jpg','jpeg','png','webp','gif','pdf','doc','docx','xls','xlsx','ppt','pptx','mp3','wav','mp4','avi','mov','avif']) {
    if (!isset($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo.');
    }

    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $tiposPermitidos)) {
        throw new Exception('Tipo de archivo no permitido: ' . $ext);
    }

    $destinoDir = __DIR__ . '/../../uploads/' . $carpeta . '/';
    if (!is_dir($destinoDir)) {
        mkdir($destinoDir, 0777, true);
    }

    $nombre = uniqid('', true) . '.' . $ext;
    $destino = $destinoDir . $nombre;

    if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
        throw new Exception('No se pudo mover el archivo.');
    }

    return $carpeta . '/' . $nombre;
}