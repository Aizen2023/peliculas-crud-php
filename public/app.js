const fields = [
  'imdb_id',
  'titulo',
  'director',
  'anio',
  'genero',
  'calificacion',
  'duracion',
  'reparto',
  'descripcion',
  'poster_url',
  'pais',
  'idioma',
  'fuente_api',
];

function setMessage(type, text) {
  const box = document.getElementById('apiMessage');
  if (!box) return;

  box.className = `alert alert-${type} mt-3`;
  box.textContent = text;
}

function updatePreview() {
  const title = document.getElementById('titulo')?.value || 'Titulo de la pelicula';
  const year = document.getElementById('anio')?.value || '';
  const genre = document.getElementById('genero')?.value || 'Genero';
  const poster = document.getElementById('poster_url')?.value || 'https://placehold.co/360x540/0f172a/ffffff?text=Poster';

  document.getElementById('titlePreview').textContent = title;
  document.getElementById('metaPreview').textContent = `${year} · ${genre}`;
  document.getElementById('posterPreview').src = poster;
}

function fillMovie(movie) {
  fields.forEach((field) => {
    const input = document.getElementById(field);
    if (input && Object.prototype.hasOwnProperty.call(movie, field)) {
      input.value = movie[field] ?? '';
    }
  });

  updatePreview();
}

document.getElementById('searchApi')?.addEventListener('click', async () => {
  const title = document.getElementById('apiTitle').value.trim();

  if (!title) {
    setMessage('warning', 'Escribe un titulo para buscar.');
    return;
  }

  setMessage('info', 'Buscando informacion...');

  try {
    const response = await fetch(`api_search.php?titulo=${encodeURIComponent(title)}`);
    const payload = await response.json();

    if (!response.ok || !payload.ok) {
      setMessage('warning', payload.message || 'No se encontro informacion.');
      return;
    }

    fillMovie(payload.movie);
    setMessage('success', 'Datos importados. Revisa la informacion y guarda la pelicula.');
  } catch (error) {
    setMessage('danger', 'No se pudo consultar la API.');
  }
});

['titulo', 'anio', 'genero', 'poster_url'].forEach((id) => {
  document.getElementById(id)?.addEventListener('input', updatePreview);
});
