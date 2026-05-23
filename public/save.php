<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$id = isset($_POST['id']) ? (int) $_POST['id'] : null;
$titulo = trim((string) ($_POST['titulo'] ?? ''));
$director = trim((string) ($_POST['director'] ?? ''));
$anio = (int) ($_POST['anio'] ?? 0);
$genero = trim((string) ($_POST['genero'] ?? ''));
$calificacion = (float) ($_POST['calificacion'] ?? 0);

if ($titulo === '' || $director === '' || $genero === '' || $anio < 1888 || $anio > 2100 || $calificacion < 0 || $calificacion > 10) {
    http_response_code(422);
    exit('Datos invalidos');
}

if ($id) {
    $stmt = $pdo->prepare(
        'UPDATE peliculas SET titulo = ?, director = ?, anio = ?, genero = ?, calificacion = ? WHERE id = ?'
    );
    $stmt->execute([$titulo, $director, $anio, $genero, $calificacion, $id]);
    $mensaje = 'Pelicula actualizada.';
} else {
    $stmt = $pdo->prepare(
        'INSERT INTO peliculas (titulo, director, anio, genero, calificacion) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$titulo, $director, $anio, $genero, $calificacion]);
    $mensaje = 'Pelicula registrada.';
}

header('Location: index.php?mensaje=' . urlencode($mensaje));
exit;
