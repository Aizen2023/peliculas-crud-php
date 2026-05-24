CREATE DATABASE IF NOT EXISTS peliculas_crud
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE peliculas_crud;

CREATE TABLE IF NOT EXISTS peliculas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  imdb_id VARCHAR(20) NULL,
  titulo VARCHAR(120) NOT NULL,
  director VARCHAR(120) NOT NULL,
  anio SMALLINT UNSIGNED NOT NULL,
  genero VARCHAR(160) NOT NULL,
  calificacion DECIMAL(3,1) NOT NULL DEFAULT 0.0,
  duracion VARCHAR(40) NULL,
  reparto TEXT NULL,
  descripcion TEXT NULL,
  poster_url VARCHAR(500) NULL,
  pais VARCHAR(120) NULL,
  idioma VARCHAR(120) NULL,
  fuente_api VARCHAR(40) NULL,
  creada_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO peliculas (titulo, director, anio, genero, calificacion, duracion, reparto, descripcion, poster_url, pais, idioma)
VALUES
  ('Spider-Man 2', 'Sam Raimi', 2004, 'Accion, Aventura', 8.3, '127 min', 'Tobey Maguire, Kirsten Dunst, Alfred Molina', 'Peter Parker intenta equilibrar su vida personal con su responsabilidad como Spider-Man mientras enfrenta al Doctor Octopus.', 'https://m.media-amazon.com/images/M/MV5BNGQ0YTQyYTgtNWI2YS00NTE2LWJmNDItNTFlMTUwNmFlZTM0XkEyXkFqcGc@._V1_SX300.jpg', 'United States', 'English'),
  ('El viaje de Chihiro', 'Hayao Miyazaki', 2001, 'Animacion, Aventura, Fantasia', 8.6, '125 min', 'Rumi Hiiragi, Miyu Irino, Mari Natsuki', 'Chihiro entra a un mundo espiritual y debe encontrar la forma de salvar a sus padres y regresar a casa.', 'https://m.media-amazon.com/images/M/MV5BM2E2YzkxYjQtNDAxOC00YjFjLWE2NDctMmJmZjQ3NTczMzQ4XkEyXkFqcGc@._V1_SX300.jpg', 'Japan', 'Japanese'),
  ('Interestelar', 'Christopher Nolan', 2014, 'Aventura, Drama, Ciencia ficcion', 8.7, '169 min', 'Matthew McConaughey, Anne Hathaway, Jessica Chastain', 'Un grupo de exploradores viaja a traves de un agujero de gusano para buscar un nuevo hogar para la humanidad.', 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDUtN2QwOS00OTkzLWE2NzEtYjQ3MzQ1YmI0NTMxXkEyXkFqcGc@._V1_SX300.jpg', 'United States, United Kingdom, Canada', 'English')
ON DUPLICATE KEY UPDATE titulo = titulo;
