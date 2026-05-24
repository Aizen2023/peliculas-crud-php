<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$titulo = trim((string) ($_GET['titulo'] ?? ''));

function fetch_json(string $url): ?array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_USERAGENT => 'Aizen Movies local demo',
        ]);
        $json = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if (is_string($json) && $status >= 200 && $status < 300) {
            $data = json_decode($json, true);
            return is_array($data) ? $data : null;
        }

        return null;
    }

    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Aizen Movies local demo\r\n",
            'timeout' => 12,
        ],
    ]);
    $json = @file_get_contents($url, false, $context);
    $data = $json ? json_decode($json, true) : null;

    return is_array($data) ? $data : null;
}

if ($titulo === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Escribe un titulo para buscar.']);
    exit;
}

if (OMDB_API_KEY !== '') {
    $url = 'https://www.omdbapi.com/?apikey=' . urlencode(OMDB_API_KEY) . '&t=' . urlencode($titulo) . '&plot=full';
    $data = fetch_json($url);

    if (is_array($data) && ($data['Response'] ?? 'False') === 'True') {
        echo json_encode([
            'ok' => true,
            'message' => 'Datos importados desde OMDb.',
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
        exit;
    }
}

$wikiTitle = str_replace(' ', '_', $titulo);
$fallbackUrl = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . rawurlencode($wikiTitle);
$result = fetch_json($fallbackUrl);

if (!is_array($result) || isset($result['title']) && $result['title'] === 'Not found.') {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'No se encontro informacion para ese titulo.']);
    exit;
}

$extract = (string) ($result['extract'] ?? '');
$description = (string) ($result['description'] ?? '');
$year = preg_match('/\b(19|20)\d{2}\b/', $extract, $yearMatch) ? (int) $yearMatch[0] : (int) date('Y');
$director = preg_match('/directed by ([^.,\n]+)/i', $extract, $directorMatch) ? trim($directorMatch[1]) : '';
$cast = preg_match('/stars ([^.]+)/i', $extract, $castMatch) ? trim($castMatch[1]) : '';
$poster = (string) ($result['thumbnail']['source'] ?? ($result['originalimage']['source'] ?? ''));
$genre = preg_match('/\b(science fiction|action|animated|animation|comedy|drama|horror|fantasy|adventure|thriller|musical)\b/i', $extract, $genreMatch)
    ? ucfirst(strtolower($genreMatch[1]))
    : '';

echo json_encode([
    'ok' => true,
    'message' => 'Datos importados desde Wikipedia. Puedes ajustar campos antes de guardar.',
    'movie' => [
        'imdb_id' => '',
        'titulo' => strip_tags((string) ($result['displaytitle'] ?? $result['title'] ?? $titulo)),
        'director' => $director,
        'anio' => $year,
        'genero' => $genre,
        'calificacion' => 0,
        'duracion' => '',
        'reparto' => $cast,
        'descripcion' => $extract,
        'poster_url' => $poster,
        'pais' => '',
        'idioma' => '',
        'fuente_api' => 'Wikipedia',
    ],
]);
