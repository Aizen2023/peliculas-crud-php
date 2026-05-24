# Peliculas CRUD

Aplicacion sencilla en PHP con MySQL/MariaDB para registrar, ver, editar y eliminar peliculas.

## Requisitos

- XAMPP con Apache y MySQL activos.
- Usuario MySQL `root` sin password, como viene por defecto en XAMPP local.

## Enlaces locales

- App: http://localhost/peliculas-crud/public/
- Setup de base de datos: http://localhost/peliculas-crud/public/setup.php
- phpMyAdmin: http://localhost/phpmyadmin/

## Base de datos

La base se llama `peliculas_crud` y la tabla principal es `peliculas`.
Tambien se puede importar `database/schema.sql` desde phpMyAdmin.

## Datos de peliculas

La app guarda portada, descripcion, reparto, director, generos, pais, idioma, duracion y calificacion en MySQL.

Tambien incluye busqueda opcional con OMDb. Para usarla:

1. Crea una API key en https://www.omdbapi.com/apikey.aspx
2. Edita `config/database.php`
3. Coloca la clave en `OMDB_API_KEY`

```php
const OMDB_API_KEY = 'tu_clave';
```

Si no configuras la clave, puedes capturar todos los datos manualmente.
