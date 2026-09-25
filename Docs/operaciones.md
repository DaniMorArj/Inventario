# Operaciones

Cómo arrancar, probar y mantener InventarioApp en local. El entorno local usa
**Docker** (php:8.2-apache +
MariaDB): lo que funciona en local funciona en el servidor.

## Requisitos

- Docker Desktop (Windows/Mac) o Docker Engine + Compose v2.

## Arrancar

```bash
docker compose up --build -d
```

- App: **http://localhost:8080**
- La base de datos carga `inventario.sql` (esquema + datos) automáticamente la
  primera vez que se crea el volumen.
- El código está **montado en vivo** (bind-mount): editar cualquier `.php` y recargar
  el navegador refleja el cambio sin reconstruir.

## Parar / resetear

```bash
docker compose down        # para los contenedores (conserva los datos)
docker compose down -v     # para y BORRA la base de datos (reset limpio)
```

## Servicios (docker-compose.yml)

| Servicio | Imagen | Notas |
|---|---|---|
| `app` | build local (Dockerfile) | Apache + PHP 8.2, puerto 8080→80 |
| `db`  | `mariadb:11` | Puerto 3307→3306 solo para inspección local |

Variables de entorno de desarrollo (definidas en `docker-compose.yml`): `DB_*`
apuntan al servicio `db`; `SMTP_*` vacías (en local no se envía correo, no rompe).

## Acceso a la base de datos (local)

```bash
docker compose exec db mariadb -uinventario -pinventario inventario
```

O con un cliente externo: host `localhost`, puerto `3307`, usuario `inventario`,
contraseña `inventario`, base `inventario`.

## Usuarios de prueba

Los usuarios del dump tienen la contraseña en bcrypt (texto plano desconocido). Para
pruebas locales se puede fijar una contraseña conocida a un usuario existente
ejecutando PHP dentro del contenedor (`password_hash` + `UPDATE usuario`). Este
cambio solo afecta a la BD local y se pierde con `docker compose down -v`.

## Comprobaciones rápidas

```bash
# ¿responde la app?
curl -s -o /dev/null -w "%{http_code}\n" http://localhost:8080/

# ¿errores PHP en los logs?
docker compose logs app | grep -iE "PHP (Fatal|Parse|Warning)"

# tablas cargadas
docker compose exec db mariadb -uinventario -pinventario inventario -e "SHOW TABLES;"
```

## Notas de mantenimiento

- Añadir una dependencia PHP: editar `composer.json` y reconstruir (`--build`).
- Cambios en el `Dockerfile` requieren `docker compose up --build`.
- El dump `inventario.sql` solo se aplica al crear la BD; para recargarlo, resetear
  con `down -v`.
