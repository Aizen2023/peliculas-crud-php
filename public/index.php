<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$peliculas = $pdo
    ->query('SELECT * FROM peliculas ORDER BY creada_en DESC, id DESC')
    ->fetchAll();

$mensaje = $_GET['mensaje'] ?? null;
$setup = isset($_GET['setup']);

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
    <title>Catalogo de peliculas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg app-nav">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Cine CRUD</a>
            <div class="d-flex gap-2">
                <a class="btn btn-light btn-sm" href="setup.php">Actualizar BD</a>
                <a class="btn btn-accent btn-sm" href="form.php">Nueva pelicula</a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <section class="hero mb-4">
            <div>
                <span class="eyebrow">PHP + MySQL + Bootstrap</span>
                <h1>Catalogo de peliculas</h1>
                <p>Registra peliculas con portada, descripcion, reparto, director, generos y calificacion.</p>
            </div>
        </section>

        <?php if ($setup): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Base de datos actualizada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= h($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php if (!$peliculas): ?>
            <div class="empty-state">
                <h2>No hay peliculas registradas</h2>
                <p>Agrega la primera pelicula para verla aqui.</p>
                <a class="btn btn-accent" href="form.php">Nueva pelicula</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php foreach ($peliculas as $pelicula): ?>
                <?php
                $poster = $pelicula['poster_url'] ?: 'https://placehold.co/360x540/0f172a/ffffff?text=Sin+poster';
                $modalId = 'movieModal' . (int) $pelicula['id'];
                ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="movie-card h-100">
                        <img class="movie-poster" src="<?= h($poster) ?>" alt="Poster de <?= h($pelicula['titulo']) ?>">
                        <div class="movie-body">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div>
                                    <h2><?= h($pelicula['titulo']) ?></h2>
                                    <p class="movie-meta"><?= (int) $pelicula['anio'] ?> · <?= h($pelicula['duracion']) ?></p>
                                </div>
                                <span class="rating"><?= number_format((float) $pelicula['calificacion'], 1) ?></span>
                            </div>
                            <p class="genre"><?= h($pelicula['genero']) ?></p>
                            <p class="description"><?= h($pelicula['descripcion']) ?></p>
                            <div class="d-flex flex-wrap gap-2 mt-auto">
                                <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">Ver detalles</button>
                                <a class="btn btn-light btn-sm" href="form.php?id=<?= (int) $pelicula['id'] ?>">Editar</a>
                                <form action="delete.php" method="post" onsubmit="return confirm('Eliminar esta pelicula?');">
                                    <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                                    <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content movie-modal">
                            <div class="modal-header">
                                <h2 class="modal-title fs-4" id="<?= $modalId ?>Label"><?= h($pelicula['titulo']) ?></h2>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <img class="modal-poster" src="<?= h($poster) ?>" alt="Poster de <?= h($pelicula['titulo']) ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <dl class="detail-list">
                                            <dt>Director</dt>
                                            <dd><?= h($pelicula['director']) ?></dd>
                                            <dt>Reparto</dt>
                                            <dd><?= h($pelicula['reparto']) ?></dd>
                                            <dt>Genero</dt>
                                            <dd><?= h($pelicula['genero']) ?></dd>
                                            <dt>Pais / idioma</dt>
                                            <dd><?= h($pelicula['pais']) ?> · <?= h($pelicula['idioma']) ?></dd>
                                            <dt>Descripcion</dt>
                                            <dd><?= h($pelicula['descripcion']) ?></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
