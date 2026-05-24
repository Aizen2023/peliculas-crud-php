USE peliculas_crud;

ALTER TABLE peliculas
  ADD COLUMN IF NOT EXISTS imdb_id VARCHAR(20) NULL AFTER id,
  MODIFY COLUMN genero VARCHAR(160) NOT NULL,
  ADD COLUMN IF NOT EXISTS duracion VARCHAR(40) NULL AFTER calificacion,
  ADD COLUMN IF NOT EXISTS reparto TEXT NULL AFTER duracion,
  ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER reparto,
  ADD COLUMN IF NOT EXISTS poster_url VARCHAR(500) NULL AFTER descripcion,
  ADD COLUMN IF NOT EXISTS pais VARCHAR(120) NULL AFTER poster_url,
  ADD COLUMN IF NOT EXISTS idioma VARCHAR(120) NULL AFTER pais,
  ADD COLUMN IF NOT EXISTS fuente_api VARCHAR(40) NULL AFTER idioma;

DELETE p1 FROM peliculas p1
INNER JOIN peliculas p2
  ON p1.titulo = p2.titulo
  AND p1.anio = p2.anio
  AND p1.id > p2.id;

ALTER TABLE peliculas
  ADD UNIQUE KEY IF NOT EXISTS peliculas_titulo_anio_unique (titulo, anio);

UPDATE peliculas
SET
  duracion = COALESCE(duracion, '127 min'),
  reparto = COALESCE(reparto, 'Tobey Maguire, Kirsten Dunst, Alfred Molina'),
  descripcion = COALESCE(descripcion, 'Peter Parker intenta equilibrar su vida personal con su responsabilidad como Spider-Man mientras enfrenta al Doctor Octopus.'),
  poster_url = COALESCE(poster_url, 'https://m.media-amazon.com/images/M/MV5BNGQ0YTQyYTgtNWI2YS00NTE2LWJmNDItNTFlMTUwNmFlZTM0XkEyXkFqcGc@._V1_SX300.jpg'),
  pais = COALESCE(pais, 'United States'),
  idioma = COALESCE(idioma, 'English')
WHERE titulo = 'Spider-Man 2';

UPDATE peliculas
SET
  duracion = COALESCE(duracion, '125 min'),
  reparto = COALESCE(reparto, 'Rumi Hiiragi, Miyu Irino, Mari Natsuki'),
  descripcion = COALESCE(descripcion, 'Chihiro entra a un mundo espiritual y debe encontrar la forma de salvar a sus padres y regresar a casa.'),
  poster_url = COALESCE(poster_url, 'https://m.media-amazon.com/images/M/MV5BM2E2YzkxYjQtNDAxOC00YjFjLWE2NDctMmJmZjQ3NTczMzQ4XkEyXkFqcGc@._V1_SX300.jpg'),
  pais = COALESCE(pais, 'Japan'),
  idioma = COALESCE(idioma, 'Japanese')
WHERE titulo = 'El viaje de Chihiro';

UPDATE peliculas
SET
  duracion = COALESCE(duracion, '169 min'),
  reparto = COALESCE(reparto, 'Matthew McConaughey, Anne Hathaway, Jessica Chastain'),
  descripcion = COALESCE(descripcion, 'Un grupo de exploradores viaja a traves de un agujero de gusano para buscar un nuevo hogar para la humanidad.'),
  poster_url = COALESCE(poster_url, 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDUtN2QwOS00OTkzLWE2NzEtYjQ3MzQ1YmI0NTMxXkEyXkFqcGc@._V1_SX300.jpg'),
  pais = COALESCE(pais, 'United States, United Kingdom, Canada'),
  idioma = COALESCE(idioma, 'English')
WHERE titulo = 'Interestelar';
