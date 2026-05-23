CREATE DATABASE IF NOT EXISTS peliculas_crud
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE peliculas_crud;

CREATE TABLE IF NOT EXISTS peliculas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(120) NOT NULL,
  director VARCHAR(120) NOT NULL,
  anio SMALLINT UNSIGNED NOT NULL,
  genero VARCHAR(80) NOT NULL,
  calificacion DECIMAL(3,1) NOT NULL DEFAULT 0.0,
  creada_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO peliculas (titulo, director, anio, genero, calificacion)
VALUES
  ('Spider-Man 2', 'Sam Raimi', 2004, 'Accion', 8.3),
  ('El viaje de Chihiro', 'Hayao Miyazaki', 2001, 'Animacion', 8.6),
  ('Interestelar', 'Christopher Nolan', 2014, 'Ciencia ficcion', 8.7)
ON DUPLICATE KEY UPDATE titulo = titulo;
