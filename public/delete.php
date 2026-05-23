<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM peliculas WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: index.php?mensaje=' . urlencode('Pelicula eliminada.'));
exit;
