<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$peliculas = $pdo
    ->query('SELECT * FROM peliculas ORDER BY creada_en DESC, id DESC')
    ->fetchAll();

$mensaje = $_GET['mensaje'] ?? null;
$setup = isset($_GET['setup']);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peliculas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="page">
        <header class="topbar">
            <div>
                <h1>Peliculas</h1>
                <p>Alta, vista, edicion y eliminacion de registros.</p>
            </div>
            <a class="button primary" href="form.php">Nueva pelicula</a>
        </header>

        <?php if ($setup): ?>
            <div class="notice success">Base de datos creada correctamente.</div>
        <?php endif; ?>

        <?php if ($mensaje): ?>
            <div class="notice success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <section class="panel">
            <table>
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Director</th>
                        <th>Anio</th>
                        <th>Genero</th>
                        <th>Calificacion</th>
                        <th class="actions">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peliculas as $pelicula): ?>
                        <tr>
                            <td><?= htmlspecialchars($pelicula['titulo']) ?></td>
                            <td><?= htmlspecialchars($pelicula['director']) ?></td>
                            <td><?= (int) $pelicula['anio'] ?></td>
                            <td><?= htmlspecialchars($pelicula['genero']) ?></td>
                            <td><?= number_format((float) $pelicula['calificacion'], 1) ?></td>
                            <td class="actions">
                                <a class="button" href="form.php?id=<?= (int) $pelicula['id'] ?>">Editar</a>
                                <form action="delete.php" method="post" onsubmit="return confirm('Eliminar esta pelicula?');">
                                    <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                                    <button class="button danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$peliculas): ?>
                        <tr>
                            <td colspan="6" class="empty">No hay peliculas registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
