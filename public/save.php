<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$id = isset($_POST['id']) ? (int) $_POST['id'] : null;
$imdbId = trim((string) ($_POST['imdb_id'] ?? ''));
$titulo = trim((string) ($_POST['titulo'] ?? ''));
$director = trim((string) ($_POST['director'] ?? ''));
$anio = (int) ($_POST['anio'] ?? 0);
$genero = trim((string) ($_POST['genero'] ?? ''));
$calificacion = (float) ($_POST['calificacion'] ?? 0);
$duracion = trim((string) ($_POST['duracion'] ?? ''));
$reparto = trim((string) ($_POST['reparto'] ?? ''));
$descripcion = trim((string) ($_POST['descripcion'] ?? ''));
$posterUrl = trim((string) ($_POST['poster_url'] ?? ''));
$pais = trim((string) ($_POST['pais'] ?? ''));
$idioma = trim((string) ($_POST['idioma'] ?? ''));
$fuenteApi = trim((string) ($_POST['fuente_api'] ?? ''));

if ($titulo === '' || $director === '' || $genero === '' || $anio < 1888 || $anio > 2100 || $calificacion < 0 || $calificacion > 10) {
    http_response_code(422);
    exit('Datos invalidos');
}

if ($id) {
    $stmt = $pdo->prepare(
        'UPDATE peliculas
         SET imdb_id = ?, titulo = ?, director = ?, anio = ?, genero = ?, calificacion = ?, duracion = ?,
             reparto = ?, descripcion = ?, poster_url = ?, pais = ?, idioma = ?, fuente_api = ?
         WHERE id = ?'
    );
    $stmt->execute([$imdbId, $titulo, $director, $anio, $genero, $calificacion, $duracion, $reparto, $descripcion, $posterUrl, $pais, $idioma, $fuenteApi, $id]);
    $mensaje = 'Pelicula actualizada.';
} else {
    $stmt = $pdo->prepare(
        'INSERT INTO peliculas
            (imdb_id, titulo, director, anio, genero, calificacion, duracion, reparto, descripcion, poster_url, pais, idioma, fuente_api)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$imdbId, $titulo, $director, $anio, $genero, $calificacion, $duracion, $reparto, $descripcion, $posterUrl, $pais, $idioma, $fuenteApi]);
    $mensaje = 'Pelicula registrada.';
}

header('Location: index.php?mensaje=' . urlencode($mensaje));
exit;
