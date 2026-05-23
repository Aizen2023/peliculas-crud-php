<?php

declare(strict_types=1);

require __DIR__ . '/../config/database.php';

$pdo = db(null);
$sql = file_get_contents(__DIR__ . '/../database/schema.sql');
$pdo->exec($sql);

header('Location: index.php?setup=1');
exit;
