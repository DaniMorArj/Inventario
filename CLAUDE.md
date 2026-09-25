# InventarioApp

Aplicación web de gestión de inventario informático de una cadena de tiendas. PHP MVC (sin
framework), MariaDB/MySQL, servida con Apache. Versión de portfolio (datos ficticios).

## Stack

- **PHP 8.2** sobre **Apache** (imagen `php:8.2-apache`).
- **MariaDB 11**.
- **PHPMailer** (Composer) para el correo de recuperación vía SMTP.
- FPDF (vendorizado en `lib/fpdf`), JsBarcode y Bootstrap 5 en `view/`.

## Estructura

```
login.php                 Punto de entrada (redirige a controller/index.php)
recuperar_password.php     Entrada recuperación de contraseña
controller/                Controladores
model/                     Modelos (acceso a datos con PDO)
  InventarioDB.php         Conexión centralizada (lee variables de entorno)
view/                      Vistas + css/js/img
lib/fpdf/                  FPDF
inventario.sql             Dump de esquema + datos (carga inicial de la BD)
Dockerfile                 Imagen de la app
docker-compose.yml         Entorno local (app + MariaDB)
Docs/                      Documentación (manual, memoria, presentación)
```

> **Rutas sensibles a mayúsculas/minúsculas:** todo en minúsculas
> (`controller/`, `model/`, `view/`, `lib/`). Linux (contenedor/hosting) distingue
> mayúsculas; no introduzcas referencias tipo `../View/...`.

## Variables de entorno

Toda la configuración va por entorno (ver `.env.example`). Sin secretos en el repo.

| Variable | Descripción |
|---|---|
| `DB_HOST` `DB_PORT` `DB_NAME` `DB_USER` `DB_PASS` | Conexión a la base de datos |
| `APP_BASE_URL` | URL pública (enlaces de los correos; correcto detrás de proxy) |
| `SMTP_HOST` `SMTP_PORT` `SMTP_SECURE` `SMTP_USER` `SMTP_PASS` `SMTP_FROM` `SMTP_FROM_NAME` | SMTP (recuperación de contraseña) |
| `APP_LOGIN_PASSWORD` | Contraseña del muro de acceso a toda la demo (ver más abajo). Vacía = sin muro |

Si `DB_*` no están definidas, `InventarioDB` cae a los valores locales de XAMPP
(`localhost` / `root` / sin contraseña). Si `SMTP_HOST` está vacío, no se envía correo
(no rompe en desarrollo local).

## Desarrollo local (Docker)

```bash
docker compose up --build
```

- App: http://localhost:8080
- La BD carga `inventario.sql` automáticamente la primera vez.
- Parar: `docker compose down` (añade `-v` para borrar también los datos de la BD).

## Despliegue

- Build pack: **Dockerfile**. Puerto expuesto: **80** (el contenedor debe escuchar en
  el puerto que indique la variable `PORT` del hosting; ver `Docs/despliegue.md`).
- Base de datos MariaDB/MySQL gestionada por el hosting.
- Variables de entorno en el panel del hosting (nunca en el repo).

## Muro de acceso a la demo (`APP_LOGIN_PASSWORD`)

Además del login propio de la aplicación (usuarios de la tabla `usuario`), hay un
**muro previo** a toda la app (`lib/AccessGate.php`, incluido en `index.php` y
`login.php`): si `APP_LOGIN_PASSWORD` está definida, pide una contraseña única antes
de dejar pasar a nada, ni siquiera a la pantalla de login real. Sirve para que la demo
pública no quede abierta a cualquiera mientras el código no tiene arreglada la
inyección SQL descrita abajo. Sin esa variable, no hay muro (cómodo en local).

## Pendiente / notas — IMPORTANTE

- **Inyección SQL conocida y sin arreglar**, en varios modelos (no solo
  `model/Usuario.php`: también `Configuracion.php`, `TiendaAlta.php`, `StockAlta.php`,
  `Pack.php`, `Envio.php`, `Recepcion.php`, `Devolucion.php`). Concatenan valores
  directamente en el SQL en vez de usar sentencias preparadas. **Por eso este
  repositorio se mantiene privado** y la demo pública va detrás del muro de acceso
  de arriba. Antes de reutilizar este código para algo real, hay que parametrizar
  todas esas consultas.
