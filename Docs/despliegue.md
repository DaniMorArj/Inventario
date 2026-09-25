# Despliegue

La app es un contenedor Docker (PHP 8.2 + Apache) con una base de datos MariaDB/MySQL
aparte. Se puede desplegar en cualquier hosting que soporte Dockerfile — por ejemplo
Render (gratis) + una base de datos MySQL/MariaDB gestionada.

## Requisitos

- Base de datos **MySQL o MariaDB** gestionada (el esquema usa sintaxis estándar,
  compatible con ambos). El dump inicial es `inventario.sql`.
- El contenedor debe escuchar en el puerto que indique la variable de entorno
  `PORT` del hosting — `docker-entrypoint.sh` ya se encarga de reconfigurar Apache
  para eso al arrancar, no hace falta tocar nada.

## Pasos

1. Provisiona una base de datos MySQL/MariaDB y anota host, puerto, usuario,
   contraseña y nombre de base de datos.
2. Importa el esquema inicial ejecutando `inventario.sql` contra esa base de datos
   (una sola vez).
3. Despliega el repositorio con **Dockerfile** como build pack.
4. Define las variables de entorno: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`,
   `DB_PASS`, `APP_BASE_URL`, y si quieres correo de recuperación de contraseña
   `SMTP_HOST`/`SMTP_PORT`/`SMTP_SECURE`/`SMTP_USER`/`SMTP_PASS`/`SMTP_FROM`/`SMTP_FROM_NAME`.
5. **Define `APP_LOGIN_PASSWORD`** — sin esto, la demo queda abierta a cualquiera
   (ver `CLAUDE.md`, sección del muro de acceso).
6. Verifica que la app responde en la raíz (`/`) — debería pedir primero la
   contraseña del muro de acceso, y después el login normal de la aplicación
   (`admin@demo.local` / `Admin1234`).

## Notas

- Los secretos van solo en las variables de entorno del hosting, nunca en el repo.
- No hay migraciones: si cambias el esquema de una tabla ya creada, aplica el
  `ALTER TABLE` a mano contra la base de datos de producción.
