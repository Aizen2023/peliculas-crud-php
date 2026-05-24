<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$pelicula = [
    'imdb_id' => '',
    'titulo' => '',
    'director' => '',
    'anio' => date('Y'),
    'genero' => '',
    'calificacion' => '0.0',
    'duracion' => '',
    'reparto' => '',
    'descripcion' => '',
    'poster_url' => '',
    'pais' => '',
    'idioma' => '',
    'fuente_api' => '',
];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM peliculas WHERE id = ?');
    $stmt->execute([$id]);
    $pelicula = $stmt->fetch();

    if (!$pelicula) {
        http_response_code(404);
        exit('Pelicula no encontrada');
    }
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $id ? 'Editar' : 'Nueva' ?> pelicula</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg app-nav">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Aizen Movies</a>
            <a class="btn btn-light btn-sm" href="index.php">Volver</a>
        </div>
    </nav>

    <main class="container py-4">
        <section class="hero compact mb-4">
            <div>
                <span class="eyebrow"><?= $id ? 'Edicion' : 'Alta' ?></span>
                <h1><?= $id ? 'Editar pelicula' : 'Nueva pelicula' ?></h1>
            </div>
        </section>

        <div class="row g-4">
            <div class="col-lg-8">
                <form class="form-panel" action="save.php" method="post">
                    <?php if ($id): ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    <?php endif; ?>

                    <input type="hidden" name="imdb_id" id="imdb_id" value="<?= h($pelicula['imdb_id']) ?>">
                    <input type="hidden" name="fuente_api" id="fuente_api" value="<?= h($pelicula['fuente_api']) ?>">

                    <div class="api-box mb-4">
                        <label class="form-label" for="apiTitle">Importar informacion</label>
                        <div class="input-group">
                            <input class="form-control" id="apiTitle" value="<?= h($pelicula['titulo']) ?>" placeholder="Ej. The Matrix">
                            <button class="btn btn-accent" type="button" id="searchApi">Buscar</button>
                        </div>
                        <div class="form-text text-light-emphasis">Sin OMDb usa Wikipedia como respaldo; revisa los datos antes de guardar.</div>
                        <div class="alert mt-3 d-none" id="apiMessage" role="alert"></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="titulo">Titulo</label>
                            <input class="form-control" id="titulo" name="titulo" required maxlength="120" value="<?= h($pelicula['titulo']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="anio">Anio</label>
                            <input class="form-control" id="anio" name="anio" type="number" required min="1888" max="2100" value="<?= (int) $pelicula['anio'] ?>">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label" for="director">Director</label>
                            <input class="form-control" id="director" name="director" required maxlength="120" value="<?= h($pelicula['director']) ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="duracion">Duracion</label>
                            <input class="form-control" id="duracion" name="duracion" maxlength="40" value="<?= h($pelicula['duracion']) ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="genero">Generos</label>
                            <input class="form-control" id="genero" name="genero" required maxlength="160" value="<?= h($pelicula['genero']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="calificacion">Calificacion</label>
                            <input class="form-control" id="calificacion" name="calificacion" type="number" required min="0" max="10" step="0.1" value="<?= h((string) $pelicula['calificacion']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="reparto">Reparto</label>
                            <input class="form-control" id="reparto" name="reparto" value="<?= h($pelicula['reparto']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="descripcion">Descripcion</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?= h($pelicula['descripcion']) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="poster_url">URL de portada</label>
                            <input class="form-control" id="poster_url" name="poster_url" type="url" value="<?= h($pelicula['poster_url']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pais">Pais</label>
                            <input class="form-control" id="pais" name="pais" maxlength="120" value="<?= h($pelicula['pais']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="idioma">Idioma</label>
                            <input class="form-control" id="idioma" name="idioma" maxlength="120" value="<?= h($pelicula['idioma']) ?>">
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                        <a class="btn btn-outline-light" href="index.php">Cancelar</a>
                        <button class="btn btn-accent" type="submit">Guardar pelicula</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <aside class="preview-panel">
                    <img id="posterPreview" src="<?= h($pelicula['poster_url'] ?: 'https://placehold.co/360x540/0f172a/ffffff?text=Poster') ?>" alt="Vista previa de portada">
                    <h2 id="titlePreview"><?= h($pelicula['titulo'] ?: 'Titulo de la pelicula') ?></h2>
                    <p id="metaPreview"><?= (int) $pelicula['anio'] ?> · <?= h($pelicula['genero'] ?: 'Genero') ?></p>
                </aside>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
</body>
</html>
