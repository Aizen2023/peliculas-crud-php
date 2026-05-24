<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$peliculas = $pdo
    ->query('SELECT * FROM peliculas ORDER BY anio DESC, creada_en DESC, id DESC')
    ->fetchAll();

$destacada = $peliculas[0] ?? null;
$mensaje = $_GET['mensaje'] ?? null;

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function poster(array $pelicula): string
{
    return $pelicula['poster_url'] ?: 'https://placehold.co/600x900/111827/ffffff?text=Sin+poster';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aizen Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg app-nav fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">Aizen Movies</a>
            <div class="ms-auto d-flex gap-2">
                <a class="btn btn-ghost btn-sm" href="form.php"><i class="bi bi-plus-lg"></i> Agregar</a>
            </div>
        </div>
    </nav>

    <main class="page-shell">
        <?php if ($mensaje): ?>
            <div class="toast-wrap">
                <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
                    <?= h($mensaje) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($destacada): ?>
            <section class="featured-panel">
                <div class="featured-copy">
                    <span class="badge text-bg-danger mb-3">Destacada</span>
                    <h1><?= h($destacada['titulo']) ?></h1>
                    <div class="meta-pills">
                        <span><?= (int) $destacada['anio'] ?></span>
                        <?php if ($destacada['duracion']): ?><span><?= h($destacada['duracion']) ?></span><?php endif; ?>
                        <span><?= number_format((float) $destacada['calificacion'], 1) ?>/10</span>
                    </div>
                    <p><?= h($destacada['descripcion']) ?></p>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-light btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#movieModal<?= (int) $destacada['id'] ?>">
                            <i class="bi bi-info-circle"></i> Ver informacion
                        </button>
                        <a class="btn btn-outline-light btn-lg" href="form.php?id=<?= (int) $destacada['id'] ?>">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                    </div>
                </div>
                <img class="featured-poster" src="<?= h(poster($destacada)) ?>" alt="Poster de <?= h($destacada['titulo']) ?>">
            </section>
        <?php else: ?>
            <section class="featured-panel">
                <div class="featured-copy">
                    <h1>Aizen Movies</h1>
                    <a class="btn btn-light btn-lg" href="form.php"><i class="bi bi-plus-lg"></i> Agregar pelicula</a>
                </div>
            </section>
        <?php endif; ?>

        <section class="catalog-section">
            <div class="section-heading">
                <h2>Peliculas guardadas</h2>
                <span><?= count($peliculas) ?> registros</span>
            </div>

            <div class="poster-row">
                <?php foreach ($peliculas as $pelicula): ?>
                    <?php $modalId = 'movieModal' . (int) $pelicula['id']; ?>
                    <article class="poster-card">
                        <button type="button" class="poster-button" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                            <img src="<?= h(poster($pelicula)) ?>" alt="Poster de <?= h($pelicula['titulo']) ?>">
                            <span class="poster-rating"><?= number_format((float) $pelicula['calificacion'], 1) ?></span>
                        </button>
                        <div class="poster-info">
                            <h3><?= h($pelicula['titulo']) ?></h3>
                            <p><?= (int) $pelicula['anio'] ?> &middot; <?= h($pelicula['genero']) ?></p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">Detalles</button>
                                <a class="btn btn-sm btn-outline-light" href="form.php?id=<?= (int) $pelicula['id'] ?>">Editar</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="records-section">
            <div class="section-heading">
                <h2>Administrar registros</h2>
            </div>
            <div class="table-responsive records-table">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>Año</th>
                            <th>Genero</th>
                            <th>Director</th>
                            <th>Calificacion</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peliculas as $pelicula): ?>
                            <tr>
                                <td class="fw-bold"><?= h($pelicula['titulo']) ?></td>
                                <td><?= (int) $pelicula['anio'] ?></td>
                                <td><?= h($pelicula['genero']) ?></td>
                                <td><?= h($pelicula['director'] ?: 'Sin dato') ?></td>
                                <td><?= number_format((float) $pelicula['calificacion'], 1) ?></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="modal" data-bs-target="#movieModal<?= (int) $pelicula['id'] ?>">Ver</button>
                                        <a class="btn btn-sm btn-outline-light" href="form.php?id=<?= (int) $pelicula['id'] ?>">Editar</a>
                                        <form action="delete.php" method="post" onsubmit="return confirm('Eliminar esta pelicula?');">
                                            <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                                            <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php foreach ($peliculas as $pelicula): ?>
            <?php $modalId = 'movieModal' . (int) $pelicula['id']; ?>
            <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content movie-modal">
                        <div class="modal-body p-0">
                            <div class="modal-hero">
                                <button type="button" class="btn-close btn-close-white modal-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                <img class="modal-poster-art" src="<?= h(poster($pelicula)) ?>" alt="Poster de <?= h($pelicula['titulo']) ?>">
                                <div class="modal-copy">
                                    <h2 id="<?= $modalId ?>Label"><?= h($pelicula['titulo']) ?></h2>
                                    <div class="meta-pills">
                                        <span><?= (int) $pelicula['anio'] ?></span>
                                        <?php if ($pelicula['duracion']): ?><span><?= h($pelicula['duracion']) ?></span><?php endif; ?>
                                        <span><?= number_format((float) $pelicula['calificacion'], 1) ?>/10</span>
                                    </div>
                                    <p><?= h($pelicula['descripcion']) ?></p>
                                </div>
                            </div>
                            <div class="modal-details">
                                <div>
                                    <span>Director</span>
                                    <strong><?= h($pelicula['director'] ?: 'Sin dato') ?></strong>
                                </div>
                                <div>
                                    <span>Reparto</span>
                                    <strong><?= h($pelicula['reparto'] ?: 'Sin dato') ?></strong>
                                </div>
                                <div>
                                    <span>Genero</span>
                                    <strong><?= h($pelicula['genero'] ?: 'Sin dato') ?></strong>
                                </div>
                                <div>
                                    <span>Pais / idioma</span>
                                    <strong><?= h($pelicula['pais'] ?: 'Sin dato') ?> &middot; <?= h($pelicula['idioma'] ?: 'Sin dato') ?></strong>
                                </div>
                                <div class="modal-actions">
                                    <a class="btn btn-light" href="form.php?id=<?= (int) $pelicula['id'] ?>"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="delete.php" method="post" onsubmit="return confirm('Eliminar esta pelicula?');">
                                        <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                                        <button class="btn btn-danger" type="submit"><i class="bi bi-trash"></i> Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
