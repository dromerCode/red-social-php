# Red social

Mini red social en PHP + MariaDB: registro y login, publicar posts con texto e imagen, comentarios y likes en posts y comentarios. Cada usuario puede borrar sus propios posts y comentarios.

## Arrancar

1. Copia `.env.example` a `.env` y pon tus contraseñas.
2. `docker compose up -d` (crea la BD con `db/schema.sql` y los datos de `db/datos.sql`).
3. `php -S localhost:8000` y abre http://localhost:8000

Usuarios de prueba: `ana@redsocial.test` y `luis@redsocial.test`, contraseña `1234`.
