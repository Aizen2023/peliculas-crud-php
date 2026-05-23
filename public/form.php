<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$pelicula = [
    'titulo' => '',
    'director' => '',
    'anio' => date('Y'),
    'genero' => '',
    'calificacion' => '0.0',
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
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $id ? 'Editar' : 'Nueva' ?> pelicula</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="page narrow">
        <header class="topbar">
            <div>
                <h1><?= $id ? 'Editar pelicula' : 'Nueva pelicula' ?></h1>
                <p>Completa los datos y guarda el registro.</p>
            </div>
            <a class="button" href="index.php">Volver</a>
        </header>

        <form class="panel form" action="save.php" method="post">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?= $id ?>">
            <?php endif; ?>

            <label>
                Titulo
                <input name="titulo" required maxlength="120" value="<?= htmlspecialchars((string) $pelicula['titulo']) ?>">
            </label>

            <label>
                Director
                <input name="director" required maxlength="120" value="<?= htmlspecialchars((string) $pelicula['director']) ?>">
            </label>

            <label>
                Anio
                <input name="anio" type="number" required min="1888" max="2100" value="<?= (int) $pelicula['anio'] ?>">
            </label>

            <label>
                Genero
                <input name="genero" required maxlength="80" value="<?= htmlspecialchars((string) $pelicula['genero']) ?>">
            </label>

            <label>
                Calificacion
                <input name="calificacion" type="number" required min="0" max="10" step="0.1" value="<?= htmlspecialchars((string) $pelicula['calificacion']) ?>">
            </label>

            <div class="form-actions">
                <button class="button primary" type="submit">Guardar</button>
                <a class="button" href="index.php">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
