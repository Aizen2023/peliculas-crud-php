<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db(null);
$migration = file_get_contents(__DIR__ . '/../database/migrate.sql');
$pdo->exec($migration);

header('Location: index.php?mensaje=' . urlencode('Base de datos actualizada sin duplicar registros.'));
exit;
