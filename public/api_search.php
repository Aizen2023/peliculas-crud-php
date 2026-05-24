<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$titulo = trim((string) ($_GET['titulo'] ?? ''));

if ($titulo === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Escribe un titulo para buscar.']);
    exit;
}

if (OMDB_API_KEY === '') {
    http_response_code(503);
    echo json_encode([
        'ok' => false,
        'message' => 'Falta configurar OMDB_API_KEY en config/database.php.',
    ]);
    exit;
}

$url = 'https://www.omdbapi.com/?apikey=' . urlencode(OMDB_API_KEY) . '&t=' . urlencode($titulo) . '&plot=full';
$json = @file_get_contents($url);

if ($json === false) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'message' => 'No se pudo consultar la API.']);
    exit;
}

$data = json_decode($json, true);

if (!is_array($data) || ($data['Response'] ?? 'False') !== 'True') {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => $data['Error'] ?? 'Pelicula no encontrada.']);
    exit;
}

echo json_encode([
    'ok' => true,
    'movie' => [
        'imdb_id' => $data['imdbID'] ?? '',
        'titulo' => $data['Title'] ?? '',
        'director' => $data['Director'] ?? '',
        'anio' => (int) ($data['Year'] ?? date('Y')),
        'genero' => $data['Genre'] ?? '',
        'calificacion' => (float) ($data['imdbRating'] ?? 0),
        'duracion' => $data['Runtime'] ?? '',
        'reparto' => $data['Actors'] ?? '',
        'descripcion' => $data['Plot'] ?? '',
        'poster_url' => (($data['Poster'] ?? '') === 'N/A') ? '' : ($data['Poster'] ?? ''),
        'pais' => $data['Country'] ?? '',
        'idioma' => $data['Language'] ?? '',
        'fuente_api' => 'OMDb',
    ],
]);
